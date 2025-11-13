<?php
/**
 * Postmark API Client
 *
 * Handles all communication with Postmark API
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PostmarkClient
{
    const API_BASE_URL = 'https://api.postmarkapp.com';
    const API_VERSION = '1.0';

    private $apiToken;
    private $timeout = 30;

    /**
     * Constructor
     *
     * @param string $apiToken Postmark Server API Token
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Test the API connection
     *
     * @return bool
     */
    public function testConnection()
    {
        try {
            $response = $this->request('GET', '/server');
            return isset($response['ID']);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Send a single email
     *
     * @param array $data Email data
     * @return array Response from Postmark
     */
    public function sendEmail($data)
    {
        return $this->request('POST', '/email', $data);
    }

    /**
     * Send batch emails (up to 500)
     *
     * @param array $messages Array of email messages
     * @return array Response from Postmark
     */
    public function sendEmailBatch($messages)
    {
        if (count($messages) > 500) {
            throw new Exception('Postmark batch limit is 500 emails per request');
        }

        return $this->request('POST', '/email/batch', $messages);
    }

    /**
     * Get server information
     *
     * @return array
     */
    public function getServer()
    {
        return $this->request('GET', '/server');
    }

    /**
     * Get delivery statistics
     *
     * @return array
     */
    public function getDeliveryStats()
    {
        return $this->request('GET', '/deliverystats');
    }

    /**
     * Get bounces
     *
     * @param array $params Query parameters
     * @return array
     */
    public function getBounces($params = array())
    {
        $queryString = http_build_query($params);
        $endpoint = '/bounces' . ($queryString ? '?' . $queryString : '');
        return $this->request('GET', $endpoint);
    }

    /**
     * Get bounce by ID
     *
     * @param int $bounceId
     * @return array
     */
    public function getBounce($bounceId)
    {
        return $this->request('GET', '/bounces/' . $bounceId);
    }

    /**
     * Activate a bounced email
     *
     * @param int $bounceId
     * @return array
     */
    public function activateBounce($bounceId)
    {
        return $this->request('PUT', '/bounces/' . $bounceId . '/activate');
    }

    /**
     * Get outbound messages
     *
     * @param array $params Query parameters
     * @return array
     */
    public function getOutboundMessages($params = array())
    {
        $queryString = http_build_query($params);
        $endpoint = '/messages/outbound' . ($queryString ? '?' . $queryString : '');
        return $this->request('GET', $endpoint);
    }

    /**
     * Get message details
     *
     * @param string $messageId
     * @return array
     */
    public function getMessageDetails($messageId)
    {
        return $this->request('GET', '/messages/outbound/' . $messageId . '/details');
    }

    /**
     * Get message opens
     *
     * @param array $params Query parameters
     * @return array
     */
    public function getMessageOpens($params = array())
    {
        $queryString = http_build_query($params);
        $endpoint = '/messages/outbound/opens' . ($queryString ? '?' . $queryString : '');
        return $this->request('GET', $endpoint);
    }

    /**
     * Get message clicks
     *
     * @param array $params Query parameters
     * @return array
     */
    public function getMessageClicks($params = array())
    {
        $queryString = http_build_query($params);
        $endpoint = '/messages/outbound/clicks' . ($queryString ? '?' . $queryString : '');
        return $this->request('GET', $endpoint);
    }

    /**
     * Make HTTP request to Postmark API
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint
     * @param array|null $data Request data
     * @return array Response data
     * @throws Exception
     */
    private function request($method, $endpoint, $data = null)
    {
        $url = self::API_BASE_URL . $endpoint;

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Postmark-Server-Token: ' . $this->apiToken
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'GET':
            default:
                // GET is the default
                break;
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }

        $responseData = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = 'Postmark API Error (HTTP ' . $httpCode . ')';
            if (isset($responseData['Message'])) {
                $errorMessage .= ': ' . $responseData['Message'];
            }
            if (isset($responseData['ErrorCode'])) {
                $errorMessage .= ' (Error Code: ' . $responseData['ErrorCode'] . ')';
            }
            throw new Exception($errorMessage);
        }

        return $responseData;
    }

    /**
     * Set request timeout
     *
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int)$timeout;
    }
}
