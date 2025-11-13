<?php
/**
 * Newsletter Queue
 *
 * Handles batch sending of newsletters via Postmark
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/PostmarkClient.php';

class NewsletterQueue
{
    const BATCH_SIZE = 500; // Postmark's max batch size
    const BATCH_DELAY = 1; // Delay between batches in seconds

    private $postmarkClient;
    private $fromEmail;
    private $fromName;
    private $messageStream;
    private $trackOpens;
    private $trackLinks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $apiToken = Configuration::get('POSTMARK_API_TOKEN');
        $this->postmarkClient = new PostmarkClient($apiToken);
        $this->fromEmail = Configuration::get('POSTMARK_FROM_EMAIL');
        $this->fromName = Configuration::get('POSTMARK_FROM_NAME');
        $this->messageStream = Configuration::get('POSTMARK_MESSAGE_STREAM', 'broadcast');
        $this->trackOpens = (bool)Configuration::get('POSTMARK_TRACK_OPENS', 1);
        $this->trackLinks = (bool)Configuration::get('POSTMARK_TRACK_LINKS', 1);
    }

    /**
     * Send newsletter to all subscribers
     *
     * @param string $subject Email subject
     * @param string $htmlContent HTML email content
     * @param string $textContent Plain text email content
     * @param int|null $campaignId Campaign ID (optional)
     * @return array Result with statistics
     */
    public function sendNewsletter($subject, $htmlContent, $textContent = null, $campaignId = null)
    {
        $startTime = microtime(true);
        $stats = array(
            'total_subscribers' => 0,
            'total_sent' => 0,
            'total_failed' => 0,
            'batches_sent' => 0,
            'errors' => array()
        );

        // Get all newsletter subscribers
        $module = Module::getInstanceByName('postmarknewsletter');
        $subscribers = $module->getNewsletterSubscribers();
        $stats['total_subscribers'] = count($subscribers);

        if (empty($subscribers)) {
            PrestaShopLogger::addLog('NewsletterQueue: No subscribers found', 2);
            return $stats;
        }

        // Split into batches
        $batches = array_chunk($subscribers, self::BATCH_SIZE);
        $stats['total_batches'] = count($batches);

        // Send each batch
        foreach ($batches as $batchIndex => $batch) {
            try {
                $result = $this->sendBatch($batch, $subject, $htmlContent, $textContent, $campaignId);
                $stats['total_sent'] += $result['sent'];
                $stats['total_failed'] += $result['failed'];
                $stats['batches_sent']++;

                if (!empty($result['errors'])) {
                    $stats['errors'] = array_merge($stats['errors'], $result['errors']);
                }

                // Delay between batches to avoid rate limiting
                if ($batchIndex < count($batches) - 1) {
                    sleep(self::BATCH_DELAY);
                }
            } catch (Exception $e) {
                $errorMsg = 'Batch ' . ($batchIndex + 1) . ' failed: ' . $e->getMessage();
                $stats['errors'][] = $errorMsg;
                $stats['total_failed'] += count($batch);
                PrestaShopLogger::addLog('NewsletterQueue: ' . $errorMsg, 3);
            }
        }

        $stats['execution_time'] = round(microtime(true) - $startTime, 2);

        // Update campaign status if provided
        if ($campaignId) {
            $this->updateCampaignStats($campaignId, $stats);
        }

        PrestaShopLogger::addLog(
            'NewsletterQueue: Newsletter sent - ' . $stats['total_sent'] . '/' . $stats['total_subscribers'] . ' subscribers',
            1
        );

        return $stats;
    }

    /**
     * Send a single batch of emails
     *
     * @param array $recipients Array of recipient data
     * @param string $subject Email subject
     * @param string $htmlContent HTML content
     * @param string $textContent Text content
     * @param int|null $campaignId Campaign ID
     * @return array Result statistics
     */
    private function sendBatch($recipients, $subject, $htmlContent, $textContent, $campaignId = null)
    {
        $messages = array();
        $result = array(
            'sent' => 0,
            'failed' => 0,
            'errors' => array()
        );

        // Prepare messages
        foreach ($recipients as $recipient) {
            $unsubscribeToken = $this->generateUnsubscribeToken($recipient);
            $unsubscribeUrl = $this->getUnsubscribeUrl($unsubscribeToken);

            // Personalize content
            $personalizedHtml = $this->personalizeContent($htmlContent, $recipient);
            $personalizedText = $textContent ? $this->personalizeContent($textContent, $recipient) : null;

            // Add unsubscribe link to content if not already present
            $personalizedHtml = $this->addUnsubscribeLink($personalizedHtml, $unsubscribeUrl);

            $message = array(
                'From' => $this->fromName . ' <' . $this->fromEmail . '>',
                'To' => $recipient['email'],
                'Subject' => $subject,
                'HtmlBody' => $personalizedHtml,
                'MessageStream' => $this->messageStream,
                'TrackOpens' => $this->trackOpens,
                'Metadata' => array(
                    'customer_id' => (string)$recipient['id_customer'],
                )
            );

            // Add text content if provided
            if ($personalizedText) {
                $message['TextBody'] = $personalizedText;
            }

            // Add tracking
            if ($this->trackLinks) {
                $message['TrackLinks'] = 'HtmlAndText';
            }

            // Add unsubscribe headers
            $message['Headers'] = array(
                array(
                    'Name' => 'List-Unsubscribe',
                    'Value' => '<' . $unsubscribeUrl . '>'
                ),
                array(
                    'Name' => 'List-Unsubscribe-Post',
                    'Value' => 'List-Unsubscribe=One-Click'
                )
            );

            // Add campaign ID to metadata if provided
            if ($campaignId) {
                $message['Metadata']['campaign_id'] = (string)$campaignId;
            }

            $messages[] = $message;
        }

        // Send batch via Postmark
        try {
            $response = $this->postmarkClient->sendEmailBatch($messages);

            // Process response
            if (is_array($response)) {
                foreach ($response as $index => $emailResult) {
                    $recipient = $recipients[$index];

                    if (isset($emailResult['ErrorCode']) && $emailResult['ErrorCode'] != 0) {
                        // Failed
                        $result['failed']++;
                        $result['errors'][] = $recipient['email'] . ': ' . $emailResult['Message'];
                        $this->logNewsletter($recipient, null, 'failed', $subject, $campaignId);
                    } else {
                        // Success
                        $result['sent']++;
                        $messageId = isset($emailResult['MessageID']) ? $emailResult['MessageID'] : null;
                        $this->logNewsletter($recipient, $messageId, 'sent', $subject, $campaignId);
                    }
                }
            }
        } catch (Exception $e) {
            $result['failed'] = count($recipients);
            $result['errors'][] = 'Batch send failed: ' . $e->getMessage();
            throw $e;
        }

        return $result;
    }

    /**
     * Generate unsubscribe token for a customer
     *
     * @param array $customer Customer data
     * @return string Unsubscribe token
     */
    private function generateUnsubscribeToken($customer)
    {
        // Check if token already exists and is recent (less than 30 days old)
        $existingToken = Db::getInstance()->getValue('
            SELECT token
            FROM ' . _DB_PREFIX_ . 'postmark_unsubscribe_tokens
            WHERE email = "' . pSQL($customer['email']) . '"
            AND created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
            AND used_at IS NULL
            ORDER BY created_at DESC
            LIMIT 1
        ');

        if ($existingToken) {
            return $existingToken;
        }

        // Generate new token
        $token = bin2hex(random_bytes(32));

        Db::getInstance()->insert('postmark_unsubscribe_tokens', array(
            'id_customer' => (int)$customer['id_customer'],
            'email' => pSQL($customer['email']),
            'token' => pSQL($token),
            'created_at' => date('Y-m-d H:i:s'),
            'used_at' => null
        ));

        return $token;
    }

    /**
     * Get unsubscribe URL
     *
     * @param string $token Unsubscribe token
     * @return string Unsubscribe URL
     */
    private function getUnsubscribeUrl($token)
    {
        $context = Context::getContext();
        return $context->link->getModuleLink('postmarknewsletter', 'unsubscribe', array('token' => $token), true);
    }

    /**
     * Personalize content with customer data
     *
     * @param string $content Email content
     * @param array $customer Customer data
     * @return string Personalized content
     */
    private function personalizeContent($content, $customer)
    {
        $replacements = array(
            '{firstname}' => isset($customer['firstname']) ? $customer['firstname'] : '',
            '{lastname}' => isset($customer['lastname']) ? $customer['lastname'] : '',
            '{email}' => $customer['email'],
        );

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Add unsubscribe link to HTML content
     *
     * @param string $html HTML content
     * @param string $unsubscribeUrl Unsubscribe URL
     * @return string HTML with unsubscribe link
     */
    private function addUnsubscribeLink($html, $unsubscribeUrl)
    {
        $unsubscribeLink = '<p style="text-align: center; font-size: 12px; color: #999;">
            <a href="' . $unsubscribeUrl . '" style="color: #999;">Unsubscribe from this newsletter</a>
        </p>';

        // Check if unsubscribe link already exists
        if (stripos($html, 'unsubscribe') !== false) {
            return $html;
        }

        // Add before closing body tag or at the end
        if (stripos($html, '</body>') !== false) {
            return str_ireplace('</body>', $unsubscribeLink . '</body>', $html);
        } else {
            return $html . $unsubscribeLink;
        }
    }

    /**
     * Log newsletter send to database
     *
     * @param array $customer Customer data
     * @param string|null $messageId Postmark Message ID
     * @param string $status Send status
     * @param string $subject Email subject
     * @param int|null $campaignId Campaign ID
     * @return bool
     */
    private function logNewsletter($customer, $messageId, $status, $subject, $campaignId = null)
    {
        return Db::getInstance()->insert('postmark_newsletter_log', array(
            'id_customer' => (int)$customer['id_customer'],
            'email' => pSQL($customer['email']),
            'message_id' => pSQL($messageId),
            'message_stream' => pSQL($this->messageStream),
            'subject' => pSQL($subject),
            'sent_at' => date('Y-m-d H:i:s'),
            'status' => pSQL($status)
        ));
    }

    /**
     * Update campaign statistics
     *
     * @param int $campaignId Campaign ID
     * @param array $stats Statistics
     * @return bool
     */
    private function updateCampaignStats($campaignId, $stats)
    {
        return Db::getInstance()->update(
            'postmark_campaigns',
            array(
                'total_recipients' => (int)$stats['total_subscribers'],
                'total_sent' => (int)$stats['total_sent'],
                'status' => 'sent',
                'sent_at' => date('Y-m-d H:i:s')
            ),
            'id_campaign = ' . (int)$campaignId
        );
    }

    /**
     * Create a new campaign
     *
     * @param string $name Campaign name
     * @param string $subject Email subject
     * @param string $htmlContent HTML content
     * @param string $textContent Text content
     * @return int|false Campaign ID or false on failure
     */
    public function createCampaign($name, $subject, $htmlContent, $textContent = null)
    {
        $result = Db::getInstance()->insert('postmark_campaigns', array(
            'name' => pSQL($name),
            'subject' => pSQL($subject),
            'html_content' => pSQL($htmlContent, true),
            'text_content' => pSQL($textContent, true),
            'from_email' => pSQL($this->fromEmail),
            'from_name' => pSQL($this->fromName),
            'status' => 'draft',
            'created_at' => date('Y-m-d H:i:s')
        ));

        if ($result) {
            return Db::getInstance()->Insert_ID();
        }

        return false;
    }

    /**
     * Get campaign by ID
     *
     * @param int $campaignId Campaign ID
     * @return array|false Campaign data or false
     */
    public function getCampaign($campaignId)
    {
        return Db::getInstance()->getRow('
            SELECT *
            FROM ' . _DB_PREFIX_ . 'postmark_campaigns
            WHERE id_campaign = ' . (int)$campaignId
        );
    }

    /**
     * Get all campaigns
     *
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array
     */
    public function getCampaigns($limit = 20, $offset = 0)
    {
        return Db::getInstance()->executeS('
            SELECT *
            FROM ' . _DB_PREFIX_ . 'postmark_campaigns
            ORDER BY created_at DESC
            LIMIT ' . (int)$offset . ', ' . (int)$limit
        );
    }
}
