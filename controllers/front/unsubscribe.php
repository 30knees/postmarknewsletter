<?php
/**
 * Unsubscribe Controller
 *
 * Handles newsletter unsubscribe requests
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PostmarkNewsletterUnsubscribeModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    /**
     * Initialize content
     */
    public function initContent()
    {
        parent::initContent();

        $token = Tools::getValue('token');
        $confirmed = Tools::isSubmit('confirm');

        $this->context->smarty->assign(array(
            'token' => $token,
            'module_dir' => $this->module->getPathUri(),
        ));

        // Handle one-click unsubscribe (RFC 8058)
        if (Tools::isSubmit('List-Unsubscribe') || $_SERVER['REQUEST_METHOD'] === 'POST' && !$confirmed) {
            $this->handleOneClickUnsubscribe($token);
            return;
        }

        // Validate token
        if (empty($token)) {
            $this->context->smarty->assign(array(
                'error' => $this->module->l('Invalid unsubscribe link.', 'unsubscribe'),
                'success' => false,
            ));
            $this->setTemplate('module:postmarknewsletter/views/templates/front/unsubscribe.tpl');
            return;
        }

        $tokenData = $this->getTokenData($token);

        if (!$tokenData) {
            $this->context->smarty->assign(array(
                'error' => $this->module->l('Invalid or expired unsubscribe link.', 'unsubscribe'),
                'success' => false,
            ));
            $this->setTemplate('module:postmarknewsletter/views/templates/front/unsubscribe.tpl');
            return;
        }

        // Check if already used
        if ($tokenData['used_at']) {
            $this->context->smarty->assign(array(
                'error' => $this->module->l('This unsubscribe link has already been used.', 'unsubscribe'),
                'success' => false,
            ));
            $this->setTemplate('module:postmarknewsletter/views/templates/front/unsubscribe.tpl');
            return;
        }

        // Check if token is too old (90 days)
        $createdAt = strtotime($tokenData['created_at']);
        if (time() - $createdAt > 90 * 24 * 60 * 60) {
            $this->context->smarty->assign(array(
                'error' => $this->module->l('This unsubscribe link has expired.', 'unsubscribe'),
                'success' => false,
            ));
            $this->setTemplate('module:postmarknewsletter/views/templates/front/unsubscribe.tpl');
            return;
        }

        // Handle confirmation
        if ($confirmed) {
            $result = $this->processUnsubscribe($tokenData);

            if ($result) {
                $this->context->smarty->assign(array(
                    'success' => true,
                    'email' => $tokenData['email'],
                    'message' => $this->module->l('You have been successfully unsubscribed from our newsletter.', 'unsubscribe'),
                ));
            } else {
                $this->context->smarty->assign(array(
                    'error' => $this->module->l('An error occurred while processing your request. Please try again.', 'unsubscribe'),
                    'success' => false,
                ));
            }
        } else {
            // Show confirmation form
            $this->context->smarty->assign(array(
                'show_confirm' => true,
                'email' => $tokenData['email'],
            ));
        }

        $this->setTemplate('module:postmarknewsletter/views/templates/front/unsubscribe.tpl');
    }

    /**
     * Get token data from database
     *
     * @param string $token Unsubscribe token
     * @return array|false Token data or false if not found
     */
    private function getTokenData($token)
    {
        return Db::getInstance()->getRow('
            SELECT *
            FROM ' . _DB_PREFIX_ . 'postmark_unsubscribe_tokens
            WHERE token = "' . pSQL($token) . '"
        ');
    }

    /**
     * Process unsubscribe request
     *
     * @param array $tokenData Token data
     * @return bool Success status
     */
    private function processUnsubscribe($tokenData)
    {
        $email = $tokenData['email'];
        $idCustomer = $tokenData['id_customer'];

        // Update customer newsletter status
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'customer
                SET newsletter = 0
                WHERE email = "' . pSQL($email) . '"';

        $result = Db::getInstance()->execute($sql);

        if ($result) {
            // Mark token as used
            Db::getInstance()->update(
                'postmark_unsubscribe_tokens',
                array('used_at' => date('Y-m-d H:i:s')),
                'id_token = ' . (int)$tokenData['id_token']
            );

            // Log the unsubscribe
            PrestaShopLogger::addLog(
                'Postmark Newsletter: User unsubscribed - ' . $email,
                1,
                null,
                'Customer',
                $idCustomer,
                true
            );

            return true;
        }

        return false;
    }

    /**
     * Handle one-click unsubscribe (RFC 8058)
     *
     * @param string $token Unsubscribe token
     */
    private function handleOneClickUnsubscribe($token)
    {
        header('Content-Type: application/json');

        if (empty($token)) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid token'));
            exit;
        }

        $tokenData = $this->getTokenData($token);

        if (!$tokenData || $tokenData['used_at']) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid or used token'));
            exit;
        }

        $result = $this->processUnsubscribe($tokenData);

        if ($result) {
            http_response_code(200);
            echo json_encode(array('success' => true, 'message' => 'Unsubscribed successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Failed to unsubscribe'));
        }

        exit;
    }

    /**
     * Set page meta
     */
    public function setMedia()
    {
        parent::setMedia();

        $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/front.css');
    }

    /**
     * Get breadcrumb links
     *
     * @return array
     */
    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $breadcrumb['links'][] = array(
            'title' => $this->module->l('Newsletter Unsubscribe', 'unsubscribe'),
            'url' => '',
        );

        return $breadcrumb;
    }
}
