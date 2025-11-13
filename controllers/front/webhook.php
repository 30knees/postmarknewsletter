<?php
/**
 * Postmark Webhook Controller
 *
 * Handles incoming webhooks from Postmark
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/../../classes/BounceHandler.php';

class PostmarkNewsletterWebhookModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    /**
     * Initialize controller
     */
    public function init()
    {
        parent::init();

        // Disable PrestaShop output
        header('Content-Type: application/json');
    }

    /**
     * Process POST request
     */
    public function postProcess()
    {
        try {
            // Get raw POST data
            $rawData = file_get_contents('php://input');

            if (empty($rawData)) {
                $this->sendResponse(400, array('error' => 'No data received'));
                return;
            }

            // Parse JSON data
            $data = json_decode($rawData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->sendResponse(400, array('error' => 'Invalid JSON data'));
                return;
            }

            // Log webhook request for debugging
            PrestaShopLogger::addLog(
                'Postmark Webhook: Received ' . (isset($data['RecordType']) ? $data['RecordType'] : 'unknown') . ' event',
                1,
                null,
                null,
                null,
                true
            );

            // Verify webhook signature (optional but recommended)
            if (!$this->verifyWebhookSignature($rawData)) {
                PrestaShopLogger::addLog('Postmark Webhook: Invalid signature', 3);
                $this->sendResponse(401, array('error' => 'Invalid signature'));
                return;
            }

            // Process based on record type
            if (!isset($data['RecordType'])) {
                $this->sendResponse(400, array('error' => 'Missing RecordType'));
                return;
            }

            $result = false;
            switch ($data['RecordType']) {
                case 'Bounce':
                    $result = BounceHandler::handleBounce($data);
                    break;

                case 'SpamComplaint':
                    $result = BounceHandler::handleSpamComplaint($data);
                    break;

                case 'Delivery':
                    $result = BounceHandler::handleDelivery($data);
                    break;

                case 'Open':
                    $result = $this->handleOpen($data);
                    break;

                case 'Click':
                    $result = $this->handleClick($data);
                    break;

                case 'SubscriptionChange':
                    $result = $this->handleSubscriptionChange($data);
                    break;

                default:
                    PrestaShopLogger::addLog('Postmark Webhook: Unknown record type: ' . $data['RecordType'], 2);
                    $this->sendResponse(200, array('message' => 'Event type not handled'));
                    return;
            }

            if ($result) {
                $this->sendResponse(200, array('message' => 'Webhook processed successfully'));
            } else {
                $this->sendResponse(500, array('error' => 'Failed to process webhook'));
            }

        } catch (Exception $e) {
            PrestaShopLogger::addLog('Postmark Webhook Error: ' . $e->getMessage(), 3);
            $this->sendResponse(500, array('error' => 'Internal server error'));
        }
    }

    /**
     * Verify webhook signature
     *
     * @param string $rawData Raw POST data
     * @return bool
     */
    private function verifyWebhookSignature($rawData)
    {
        // Postmark doesn't send signatures by default
        // You would need to implement custom verification if needed
        // For now, we'll return true but you can implement your own security check

        // Example: Check for specific header or IP whitelist
        // $signature = isset($_SERVER['HTTP_X_POSTMARK_SIGNATURE']) ? $_SERVER['HTTP_X_POSTMARK_SIGNATURE'] : '';

        return true;
    }

    /**
     * Handle email open event
     *
     * @param array $data Open event data
     * @return bool
     */
    private function handleOpen($data)
    {
        if (!isset($data['MessageID'])) {
            return false;
        }

        // You could log opens to a separate table if needed
        PrestaShopLogger::addLog(
            'Postmark Webhook: Email opened - MessageID: ' . $data['MessageID'],
            1
        );

        return true;
    }

    /**
     * Handle link click event
     *
     * @param array $data Click event data
     * @return bool
     */
    private function handleClick($data)
    {
        if (!isset($data['MessageID'])) {
            return false;
        }

        // You could log clicks to a separate table if needed
        PrestaShopLogger::addLog(
            'Postmark Webhook: Link clicked - MessageID: ' . $data['MessageID'],
            1
        );

        return true;
    }

    /**
     * Handle subscription change event
     *
     * @param array $data Subscription change data
     * @return bool
     */
    private function handleSubscriptionChange($data)
    {
        if (!isset($data['Recipient'])) {
            return false;
        }

        $email = $data['Recipient'];
        $suppressionReason = isset($data['SuppressSending']) ? $data['SuppressSending'] : null;

        // If Postmark suppressed sending, unsubscribe the user
        if ($suppressionReason) {
            $sql = 'UPDATE ' . _DB_PREFIX_ . 'customer
                    SET newsletter = 0
                    WHERE email = "' . pSQL($email) . '"';

            $result = Db::getInstance()->execute($sql);

            PrestaShopLogger::addLog(
                'Postmark Webhook: Unsubscribed ' . $email . ' due to: ' . $suppressionReason,
                1
            );

            return $result;
        }

        return true;
    }

    /**
     * Send JSON response
     *
     * @param int $statusCode HTTP status code
     * @param array $data Response data
     */
    private function sendResponse($statusCode, $data)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Display method (required by PrestaShop)
     */
    public function display()
    {
        // Webhooks use postProcess, nothing to display
    }
}
