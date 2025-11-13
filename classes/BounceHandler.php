<?php
/**
 * Bounce Handler
 *
 * Handles bounce processing and automatic unsubscribes
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class BounceHandler
{
    /**
     * Hard bounce types that should trigger immediate unsubscribe
     */
    const HARD_BOUNCE_TYPES = array(
        'HardBounce',
        'AddressChange',
        'BadEmailAddress',
        'SpamNotification',
        'SpamComplaint',
        'ManuallyDeactivated',
        'Unsubscribe',
        'DmarcPolicy',
        'TemplateRenderingFailed'
    );

    /**
     * Soft bounce types
     */
    const SOFT_BOUNCE_TYPES = array(
        'SoftBounce',
        'MailboxFull',
        'MessageDelayed',
        'AutoResponder',
        'Transient'
    );

    /**
     * Handle bounce webhook data
     *
     * @param array $data Bounce data from Postmark webhook
     * @return bool Success status
     */
    public static function handleBounce($data)
    {
        if (!isset($data['Email']) || !isset($data['Type'])) {
            PrestaShopLogger::addLog('BounceHandler: Invalid bounce data received', 3);
            return false;
        }

        $email = $data['Email'];
        $bounceType = $data['Type'];
        $messageId = isset($data['MessageID']) ? $data['MessageID'] : null;
        $description = isset($data['Description']) ? $data['Description'] : '';
        $bouncedAt = isset($data['BouncedAt']) ? $data['BouncedAt'] : date('Y-m-d H:i:s');

        // Log the bounce
        $bounceId = self::logBounce($email, $messageId, $bounceType, $description, $bouncedAt);

        if (!$bounceId) {
            PrestaShopLogger::addLog('BounceHandler: Failed to log bounce for ' . $email, 3);
            return false;
        }

        // Update newsletter log status
        self::updateNewsletterLogStatus($messageId, 'bounced');

        // Check if we should auto-unsubscribe
        if (self::shouldAutoUnsubscribe($email, $bounceType)) {
            self::unsubscribeEmail($email, $bounceId);
            PrestaShopLogger::addLog('BounceHandler: Auto-unsubscribed ' . $email . ' due to ' . $bounceType, 1);
        }

        return true;
    }

    /**
     * Handle spam complaint webhook data
     *
     * @param array $data Spam complaint data from Postmark webhook
     * @return bool Success status
     */
    public static function handleSpamComplaint($data)
    {
        if (!isset($data['Email'])) {
            PrestaShopLogger::addLog('BounceHandler: Invalid spam complaint data received', 3);
            return false;
        }

        $email = $data['Email'];
        $messageId = isset($data['MessageID']) ? $data['MessageID'] : null;
        $bouncedAt = isset($data['BouncedAt']) ? $data['BouncedAt'] : date('Y-m-d H:i:s');

        // Log as spam bounce
        $bounceId = self::logBounce($email, $messageId, 'SpamComplaint', 'User marked email as spam', $bouncedAt);

        if ($bounceId) {
            // Always unsubscribe on spam complaints
            self::unsubscribeEmail($email, $bounceId);
            self::updateNewsletterLogStatus($messageId, 'spam');
            PrestaShopLogger::addLog('BounceHandler: Auto-unsubscribed ' . $email . ' due to spam complaint', 1);
        }

        return true;
    }

    /**
     * Handle delivery webhook data
     *
     * @param array $data Delivery data from Postmark webhook
     * @return bool Success status
     */
    public static function handleDelivery($data)
    {
        if (!isset($data['MessageID'])) {
            return false;
        }

        $messageId = $data['MessageID'];
        return self::updateNewsletterLogStatus($messageId, 'delivered');
    }

    /**
     * Log a bounce to the database
     *
     * @param string $email
     * @param string|null $messageId
     * @param string $bounceType
     * @param string $bounceReason
     * @param string $bouncedAt
     * @return int|false Bounce ID or false on failure
     */
    private static function logBounce($email, $messageId, $bounceType, $bounceReason, $bouncedAt)
    {
        $result = Db::getInstance()->insert('postmark_bounces', array(
            'email' => pSQL($email),
            'message_id' => pSQL($messageId),
            'bounce_type' => pSQL($bounceType),
            'bounce_reason' => pSQL($bounceReason),
            'bounced_at' => pSQL($bouncedAt),
            'unsubscribed' => 0
        ));

        if ($result) {
            return Db::getInstance()->Insert_ID();
        }

        return false;
    }

    /**
     * Update newsletter log status
     *
     * @param string $messageId
     * @param string $status
     * @return bool
     */
    private static function updateNewsletterLogStatus($messageId, $status)
    {
        if (!$messageId) {
            return false;
        }

        return Db::getInstance()->update(
            'postmark_newsletter_log',
            array('status' => pSQL($status)),
            'message_id = "' . pSQL($messageId) . '"'
        );
    }

    /**
     * Determine if email should be auto-unsubscribed
     *
     * @param string $email
     * @param string $bounceType
     * @return bool
     */
    private static function shouldAutoUnsubscribe($email, $bounceType)
    {
        // Check if hard bounce auto-unsubscribe is enabled
        if (in_array($bounceType, self::HARD_BOUNCE_TYPES)) {
            return (bool)Configuration::get('POSTMARK_AUTO_UNSUBSCRIBE_HARD');
        }

        // Check if soft bounce auto-unsubscribe is enabled
        if (in_array($bounceType, self::SOFT_BOUNCE_TYPES)) {
            if (!(bool)Configuration::get('POSTMARK_AUTO_UNSUBSCRIBE_SOFT')) {
                return false;
            }

            // Check if threshold is reached
            $threshold = (int)Configuration::get('POSTMARK_SOFT_BOUNCE_THRESHOLD');
            $softBounceCount = self::getSoftBounceCount($email);

            return $softBounceCount >= $threshold;
        }

        return false;
    }

    /**
     * Get count of soft bounces for an email
     *
     * @param string $email
     * @return int
     */
    private static function getSoftBounceCount($email)
    {
        $softBounceTypes = "'" . implode("','", self::SOFT_BOUNCE_TYPES) . "'";

        return (int)Db::getInstance()->getValue('
            SELECT COUNT(*)
            FROM ' . _DB_PREFIX_ . 'postmark_bounces
            WHERE email = "' . pSQL($email) . '"
            AND bounce_type IN (' . $softBounceTypes . ')
            AND unsubscribed = 0
        ');
    }

    /**
     * Unsubscribe an email address
     *
     * @param string $email
     * @param int $bounceId
     * @return bool
     */
    private static function unsubscribeEmail($email, $bounceId)
    {
        // Update customer newsletter status
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'customer
                SET newsletter = 0
                WHERE email = "' . pSQL($email) . '"';

        $result = Db::getInstance()->execute($sql);

        // Mark bounce as processed (unsubscribed)
        if ($result && $bounceId) {
            Db::getInstance()->update(
                'postmark_bounces',
                array('unsubscribed' => 1),
                'id_bounce = ' . (int)$bounceId
            );
        }

        return $result;
    }

    /**
     * Check if email is bounced
     *
     * @param string $email
     * @return bool
     */
    public static function isEmailBounced($email)
    {
        $count = (int)Db::getInstance()->getValue('
            SELECT COUNT(*)
            FROM ' . _DB_PREFIX_ . 'postmark_bounces
            WHERE email = "' . pSQL($email) . '"
            AND unsubscribed = 1
        ');

        return $count > 0;
    }

    /**
     * Get bounce statistics
     *
     * @return array
     */
    public static function getBounceStats()
    {
        $stats = Db::getInstance()->getRow('
            SELECT
                COUNT(*) as total_bounces,
                SUM(CASE WHEN bounce_type IN ("' . implode('","', self::HARD_BOUNCE_TYPES) . '") THEN 1 ELSE 0 END) as hard_bounces,
                SUM(CASE WHEN bounce_type IN ("' . implode('","', self::SOFT_BOUNCE_TYPES) . '") THEN 1 ELSE 0 END) as soft_bounces,
                SUM(CASE WHEN unsubscribed = 1 THEN 1 ELSE 0 END) as auto_unsubscribed
            FROM ' . _DB_PREFIX_ . 'postmark_bounces
        ');

        return $stats;
    }

    /**
     * Get recent bounces
     *
     * @param int $limit
     * @return array
     */
    public static function getRecentBounces($limit = 10)
    {
        return Db::getInstance()->executeS('
            SELECT *
            FROM ' . _DB_PREFIX_ . 'postmark_bounces
            ORDER BY bounced_at DESC
            LIMIT ' . (int)$limit
        );
    }

    /**
     * Clean old bounce records
     *
     * @param int $days Number of days to keep
     * @return bool
     */
    public static function cleanOldBounces($days = 365)
    {
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'postmark_bounces
                WHERE bounced_at < DATE_SUB(NOW(), INTERVAL ' . (int)$days . ' DAY)';

        return Db::getInstance()->execute($sql);
    }
}
