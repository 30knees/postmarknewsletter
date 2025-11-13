<?php
/**
 * Example: How to send a newsletter using the Postmark Newsletter module
 *
 * IMPORTANT: This is an example file. Copy this to your own script location
 * and modify as needed. Never run this directly from the examples folder.
 */

// Include PrestaShop configuration
require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once dirname(__FILE__) . '/../classes/NewsletterQueue.php';

// Check if running from command line or admin
if (!defined('_PS_ADMIN_DIR_')) {
    die('This script should only be run by administrators.');
}

/**
 * Example 1: Send a simple newsletter
 */
function sendSimpleNewsletter()
{
    $queue = new NewsletterQueue();

    $subject = 'Monthly Newsletter - ' . date('F Y');

    $htmlContent = file_get_contents(dirname(__FILE__) . '/newsletter-template.html');

    $textContent = "Hello {firstname}!\n\n" .
        "Welcome to our monthly newsletter.\n\n" .
        "Visit our store: https://yourstore.com\n\n" .
        "Best regards,\nYour Store Team";

    echo "Sending newsletter to all subscribers...\n";

    $stats = $queue->sendNewsletter($subject, $htmlContent, $textContent);

    echo "Newsletter sent!\n";
    echo "Total subscribers: {$stats['total_subscribers']}\n";
    echo "Total sent: {$stats['total_sent']}\n";
    echo "Total failed: {$stats['total_failed']}\n";
    echo "Execution time: {$stats['execution_time']} seconds\n";

    if (!empty($stats['errors'])) {
        echo "\nErrors:\n";
        foreach ($stats['errors'] as $error) {
            echo "- $error\n";
        }
    }
}

/**
 * Example 2: Create and send a campaign
 */
function sendCampaign()
{
    $queue = new NewsletterQueue();

    // Create campaign
    $campaignId = $queue->createCampaign(
        'December Special Offers',
        'Up to 50% Off - Limited Time!',
        file_get_contents(dirname(__FILE__) . '/newsletter-template.html'),
        "Hello {firstname}!\n\nDon't miss our special offers!"
    );

    if ($campaignId) {
        echo "Campaign created with ID: $campaignId\n";

        // Send campaign
        $stats = $queue->sendNewsletter(
            'Up to 50% Off - Limited Time!',
            file_get_contents(dirname(__FILE__) . '/newsletter-template.html'),
            "Hello {firstname}!\n\nDon't miss our special offers!",
            $campaignId
        );

        echo "Campaign sent!\n";
        echo "Stats: {$stats['total_sent']}/{$stats['total_subscribers']} sent\n";
    } else {
        echo "Failed to create campaign\n";
    }
}

/**
 * Example 3: Get subscriber list
 */
function getSubscribers()
{
    $module = Module::getInstanceByName('postmarknewsletter');

    $total = $module->getTotalSubscribers();
    echo "Total newsletter subscribers: $total\n\n";

    $subscribers = $module->getNewsletterSubscribers(10, 0);

    echo "First 10 subscribers:\n";
    foreach ($subscribers as $subscriber) {
        echo "- {$subscriber['firstname']} {$subscriber['lastname']} <{$subscriber['email']}>\n";
    }
}

/**
 * Example 4: Check bounce statistics
 */
function checkBounceStats()
{
    require_once dirname(__FILE__) . '/../classes/BounceHandler.php';

    $stats = BounceHandler::getBounceStats();

    echo "Bounce Statistics:\n";
    echo "Total bounces: {$stats['total_bounces']}\n";
    echo "Hard bounces: {$stats['hard_bounces']}\n";
    echo "Soft bounces: {$stats['soft_bounces']}\n";
    echo "Auto-unsubscribed: {$stats['auto_unsubscribed']}\n\n";

    echo "Recent bounces:\n";
    $recentBounces = BounceHandler::getRecentBounces(5);
    foreach ($recentBounces as $bounce) {
        echo "- {$bounce['email']} ({$bounce['bounce_type']}) - {$bounce['bounced_at']}\n";
    }
}

// Uncomment the function you want to run:

// sendSimpleNewsletter();
// sendCampaign();
// getSubscribers();
// checkBounceStats();

echo "\nExample script loaded. Uncomment a function to run it.\n";
