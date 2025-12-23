# Postmark Newsletter Module for PrestaShop 8

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PrestaShop](https://img.shields.io/badge/PrestaShop-8.0+-blue.svg)](https://www.prestashop.com/)
[![PHP](https://img.shields.io/badge/PHP-7.2+-purple.svg)](https://www.php.net/)

A professional, production-ready newsletter module for PrestaShop 8 that integrates with [Postmark](https://postmarkapp.com) to deliver newsletters with enterprise-grade features including automatic bounce handling, one-click unsubscribe, and comprehensive tracking.

## 🎯 Why This Module?

- **Reliable Delivery**: Leverage Postmark's 99.9% uptime and excellent deliverability
- **Automatic List Hygiene**: Bounced emails are automatically removed from your list
- **GDPR Compliant**: Easy one-click unsubscribe, no tracking without consent
- **Professional Features**: Batch sending, personalization, campaign management
- **No Maintenance**: Set it up once, webhooks handle everything automatically
- **Free to Start**: Postmark offers 100 free emails/month, perfect for testing

## ✨ Features

### 📧 Newsletter Sending
- ✅ **Admin UI Interface** - Complete admin panel for creating and sending newsletters
- ✅ **Batch Sending** - Send up to 500 emails per API call for fast delivery
- ✅ **Test Newsletter** - Send test emails with your actual content before going live
- ✅ **Draft System** - Save newsletters as drafts and edit them later
- ✅ **HTML & Plain Text** - Support for both HTML and plain text versions
- ✅ **Personalization** - Use {firstname}, {lastname}, {email} variables

### 📊 Campaign Management
- ✅ **Campaign History** - View all sent newsletters and drafts in one place
- ✅ **Load & Edit** - Load any campaign to edit and resend
- ✅ **Campaign Statistics** - Track recipients, sent count, and status
- ✅ **Delete Campaigns** - Remove old campaigns when no longer needed

### 🛡️ Bounce & Unsubscribe Handling
- ✅ **Automatic Bounce Handling** - Hard and soft bounce detection via webhooks
- ✅ **Smart Auto-Unsubscribe** - Configurable rules for automatic list cleaning
- ✅ **One-Click Unsubscribe** - RFC 8058 compliant List-Unsubscribe header
- ✅ **Beautiful Unsubscribe Page** - User-friendly confirmation page
- ✅ **Suppression List** - Prevent sending to previously bounced emails

### 📈 Tracking & Analytics
- ✅ **Email Tracking** - Track opens and clicks via Postmark dashboard
- ✅ **Statistics Dashboard** - Real-time metrics in PrestaShop admin
- ✅ **Webhook Support** - Automatic processing of Bounce, Delivery, Spam events
- ✅ **Delivery Status** - Track every email's delivery status

### 🔧 Developer Features
- ✅ **Full API Coverage** - Complete Postmark API wrapper included
- ✅ **Programmatic Access** - Send newsletters via PHP code
- ✅ **Multi-Language Support** - 11 languages included out of the box

## 📋 Requirements

- PrestaShop 8.0 or higher
- PHP 7.2 or higher
- cURL PHP extension enabled
- HTTPS (recommended for webhooks)
- Postmark account ([free tier available](https://postmarkapp.com))

## 📦 Installation

**Quick Install:**

1. Clone this repository into your PrestaShop modules directory:
   ```bash
   cd /path/to/prestashop/modules/
   git clone https://github.com/yourusername/prestashop-postmark-newsletter.git postmarknewsletter
   ```

2. Install via PrestaShop Admin:
   - Go to **Modules > Module Manager**
   - Search for "Postmark Newsletter"
   - Click **Install**

**For detailed installation instructions, see [INSTALL.md](INSTALL.md)**

### 2. Configure Postmark Account

1. Create a free account at [postmarkapp.com](https://postmarkapp.com)
2. Create a new **Server** in Postmark
3. Verify your sender email address or domain
4. Get your **Server API Token** from the server settings

### 3. Configure Module

1. In PrestaShop admin, go to **Modules > Module Manager**
2. Search for "Postmark Newsletter" and click **Configure**
3. Enter your configuration:
   - **Postmark Server API Token**: Your API token from Postmark
   - **From Email**: Your verified sender email (e.g., newsletter@yourdomain.com)
   - **From Name**: Sender name (e.g., "Your Store Name")
   - **Message Stream**: Use "broadcast" for newsletters (default)
   - **Track Opens**: Enable to track email opens in Postmark
   - **Track Links**: Enable to track link clicks in Postmark
   - **Auto-unsubscribe Hard Bounces**: Enable (recommended)
   - **Auto-unsubscribe Soft Bounces**: Optional
   - **Soft Bounce Threshold**: Number of soft bounces before auto-unsubscribe (default: 3)
4. Click **Save**

### 4. Set Up Webhooks

1. Copy the **Webhook URL** shown in the module configuration
2. Go to your Postmark server settings
3. Navigate to **Webhooks**
4. Click **Add Webhook**
5. Paste the webhook URL
6. Enable these webhook types:
   - **Bounce**
   - **Delivery**
   - **Spam Complaint**
7. Save the webhook

## 🚀 Usage

### Sending Newsletters via Admin UI (Recommended)

The easiest way to send newsletters is through the PrestaShop admin interface:

1. **Navigate to Module Configuration**
   - Go to **Modules > Module Manager**
   - Search for "Postmark Newsletter"
   - Click **Configure**

2. **Create Your Newsletter**
   - Scroll to the **"Send Newsletter"** section
   - Fill in the form:
     - **Subject** (required): Your email subject line
     - **HTML Content** (required): Paste your complete HTML newsletter
     - **Plain Text Content** (optional): Plain text version (auto-generated if empty)
     - **Campaign Name** (optional): Name for tracking (e.g., "December 2025 Newsletter")

3. **Test Before Sending**
   - In the **"Test Newsletter"** section, enter your email address
   - Click **"Send Test Newsletter"**
   - Check your inbox for the test email with `[TEST]` prefix
   - The test includes a yellow warning banner to distinguish it from real sends

4. **Save as Draft** (Optional)
   - Click **"Save as Draft"** to save without sending
   - Your campaign will appear in the "Campaign History" below
   - You can load and edit it later

5. **Send to All Subscribers**
   - When ready, click **"Send Newsletter Now"**
   - Confirm the dialog (this action cannot be undone)
   - View real-time statistics after sending

6. **View Campaign History**
   - Scroll to **"Campaign History"** panel at the bottom
   - See all your drafts and sent campaigns
   - **Load & Edit** - Click to load campaign into the form
   - **Delete** - Remove campaigns you no longer need

**Personalization Variables:**
Use these in your HTML content:
- `{firstname}` - Replaced with customer's first name
- `{lastname}` - Replaced with customer's last name
- `{email}` - Replaced with customer's email

**Example HTML:**
```html
<!DOCTYPE html>
<html>
<body>
  <h1>Hello {firstname}!</h1>
  <p>Thank you for being a valued customer, {firstname} {lastname}.</p>
  <p>This newsletter was sent to {email}.</p>
  <p><a href="#">View our latest offers</a></p>
</body>
</html>
```

**After Sending:**
- View detailed statistics (sent, failed, execution time)
- Check errors if any sends failed
- Monitor delivery in Postmark dashboard
- Track opens and clicks in Postmark activity

---

### Sending a Newsletter Programmatically

```php
<?php
// Get module instance
$module = Module::getInstanceByName('postmarknewsletter');

// Create newsletter queue
require_once _PS_MODULE_DIR_ . 'postmarknewsletter/classes/NewsletterQueue.php';
$queue = new NewsletterQueue();

// Define your newsletter content
$subject = 'Monthly Newsletter - December 2025';
$htmlContent = '
    <html>
    <body>
        <h1>Hello {firstname}!</h1>
        <p>This is our monthly newsletter.</p>
        <p>Thank you for subscribing!</p>
    </body>
    </html>
';
$textContent = 'Hello {firstname}! This is our monthly newsletter.';

// Send newsletter to all subscribers
$stats = $queue->sendNewsletter($subject, $htmlContent, $textContent);

// View results
echo 'Total subscribers: ' . $stats['total_subscribers'] . "\n";
echo 'Total sent: ' . $stats['total_sent'] . "\n";
echo 'Total failed: ' . $stats['total_failed'] . "\n";
```

### Creating a Campaign

```php
<?php
require_once _PS_MODULE_DIR_ . 'postmarknewsletter/classes/NewsletterQueue.php';
$queue = new NewsletterQueue();

// Create campaign
$campaignId = $queue->createCampaign(
    'December Newsletter',
    'Our Amazing December Offers!',
    '<html><body><h1>Hello {firstname}!</h1>...</body></html>',
    'Hello {firstname}! ...'
);

// Send campaign
$stats = $queue->sendNewsletter(
    'Our Amazing December Offers!',
    '<html><body><h1>Hello {firstname}!</h1>...</body></html>',
    'Hello {firstname}! ...',
    $campaignId
);
```

### Personalization Variables

Use these variables in your email content:

- `{firstname}` - Customer's first name
- `{lastname}` - Customer's last name
- `{email}` - Customer's email address

Example:
```html
<p>Hello {firstname} {lastname}!</p>
<p>Your email is: {email}</p>
```

### Checking Bounce Statistics

```php
<?php
require_once _PS_MODULE_DIR_ . 'postmarknewsletter/classes/BounceHandler.php';

// Get bounce statistics
$stats = BounceHandler::getBounceStats();
echo 'Total bounces: ' . $stats['total_bounces'] . "\n";
echo 'Hard bounces: ' . $stats['hard_bounces'] . "\n";
echo 'Soft bounces: ' . $stats['soft_bounces'] . "\n";
echo 'Auto-unsubscribed: ' . $stats['auto_unsubscribed'] . "\n";

// Get recent bounces
$recentBounces = BounceHandler::getRecentBounces(10);
foreach ($recentBounces as $bounce) {
    echo $bounce['email'] . ' - ' . $bounce['bounce_type'] . "\n";
}
```

### Getting Newsletter Subscribers

```php
<?php
$module = Module::getInstanceByName('postmarknewsletter');

// Get all subscribers
$subscribers = $module->getNewsletterSubscribers();

// Get total count
$total = $module->getTotalSubscribers();

// Get paginated subscribers
$subscribers = $module->getNewsletterSubscribers(100, 0); // 100 subscribers, offset 0
```

## Database Tables

The module creates 4 tables:

### `ps_postmark_newsletter_log`
Logs all sent newsletters with Postmark MessageID for tracking.

### `ps_postmark_bounces`
Tracks all bounces (hard and soft) and auto-unsubscribe status.

### `ps_postmark_unsubscribe_tokens`
Stores unsubscribe tokens for one-click unsubscribe functionality.

### `ps_postmark_campaigns`
Stores newsletter campaigns with statistics.

## Webhooks

The module handles these Postmark webhook events:

### Bounce
- Logs the bounce to database
- Updates newsletter log status to "bounced"
- Auto-unsubscribes if configured (hard bounces or soft bounce threshold reached)

### Spam Complaint
- Logs the complaint
- Immediately unsubscribes the user
- Updates newsletter log status to "spam"

### Delivery
- Updates newsletter log status to "delivered"

### Open (optional)
- Logs email opens (if tracking enabled)

### Click (optional)
- Logs link clicks (if tracking enabled)

## Unsubscribe Flow

### Web-Based Unsubscribe
1. User clicks unsubscribe link in email
2. Redirected to confirmation page
3. User confirms unsubscription
4. Customer's `newsletter` field set to 0
5. Token marked as used

### One-Click Unsubscribe (RFC 8058)
1. Email client sends POST request to unsubscribe URL
2. Module automatically unsubscribes user
3. Returns JSON response
4. No user interaction required

## Security

- **Webhook verification**: Optional signature verification for webhooks
- **Token-based unsubscribe**: Secure 64-character random tokens
- **Token expiration**: Tokens expire after 90 days
- **SQL injection protection**: All queries use PrestaShop's pSQL() function
- **XSS protection**: All output escaped via Smarty templates

## Translations

The module is fully translatable and includes **11 languages** out of the box:

- **German (de)** - Complete German translation
- **English (en)** - Complete English translation
- **French (fr)** - Complete French translation
- **Spanish (es)** - Complete Spanish translation
- **Italian (it)** - Complete Italian translation
- **Dutch (nl)** - Complete Dutch translation
- **Polish (pl)** - Polish translation
- **Romanian (ro)** - Romanian translation
- **Swedish (sv)** - Swedish translation
- **Croatian (hr)** - Croatian translation
- **Czech (cz)** - Czech translation

### Adding Your Own Language

To add a new language translation:

1. Copy `translations/en.php` to `translations/[your_locale].php` (e.g., `fr.php` for French)
2. Translate all text strings in the file
3. The module will automatically use the correct translation based on the customer's language preference

### Translation Keys

All user-facing text uses PrestaShop's `$this->l()` function, making it fully compatible with:
- PrestaShop's built-in translation system
- XLIFF translation export/import
- Third-party translation tools

## Troubleshooting

### Emails not sending
1. Check Postmark API token is correct
2. Verify sender email is verified in Postmark
3. Check PrestaShop logs for errors
4. Test connection using "Test Connection" button

### Webhooks not working
1. Verify webhook URL is correct (copy from module config)
2. Check webhook is enabled in Postmark for correct events
3. Check PrestaShop logs for webhook errors
4. Ensure your server accepts POST requests from Postmark IPs

### Bounces not auto-unsubscribing
1. Verify webhooks are configured correctly
2. Check "Auto-unsubscribe" settings are enabled
3. Review bounce logs in database
4. Check PrestaShop logs for errors

### Email tracking not working
1. Ensure "Track Opens" and "Track Links" are enabled
2. Verify in Postmark dashboard that tracking is enabled for your server
3. Check message stream allows tracking

## Best Practices

1. **Test first**: Always use the "Send Test Newsletter" feature before sending to all subscribers
2. **Save drafts**: Use "Save as Draft" to work on newsletters over time before sending
3. **Use plain text**: Include both HTML and plain text versions for better deliverability
4. **Personalize**: Use {firstname}, {lastname}, and {email} variables to personalize emails
5. **Review campaigns**: Check Campaign History to avoid sending duplicate content
6. **Monitor bounces**: Regularly check bounce statistics in the dashboard
7. **Clean list**: Remove bounced emails to maintain good sender reputation (automatic with webhooks)
8. **GDPR compliance**: Ensure you have consent before sending newsletters
9. **Unsubscribe link**: Always include unsubscribe link (automatically added by module)
10. **Message stream**: Use "broadcast" stream for newsletters (transactional for order emails)
11. **Name campaigns**: Use descriptive campaign names for better tracking and organization

## API Reference

### PostmarkClient Class

```php
$client = new PostmarkClient($apiToken);

// Send single email
$client->sendEmail($data);

// Send batch (up to 500)
$client->sendEmailBatch($messages);

// Get server info
$client->getServer();

// Get delivery stats
$client->getDeliveryStats();

// Get bounces
$client->getBounces(['count' => 10]);
```

### NewsletterQueue Class

```php
$queue = new NewsletterQueue();

// Send newsletter
$queue->sendNewsletter($subject, $htmlContent, $textContent, $campaignId);

// Create campaign
$queue->createCampaign($name, $subject, $htmlContent, $textContent);

// Get campaign
$queue->getCampaign($campaignId);

// Get campaigns
$queue->getCampaigns($limit, $offset);
```

### BounceHandler Class

```php
// Handle bounce webhook
BounceHandler::handleBounce($data);

// Handle spam complaint
BounceHandler::handleSpamComplaint($data);

// Check if email is bounced
BounceHandler::isEmailBounced($email);

// Get bounce stats
BounceHandler::getBounceStats();

// Get recent bounces
BounceHandler::getRecentBounces($limit);
```

## Support

For issues and questions:

1. Check PrestaShop logs: **Advanced Parameters > Logs**
2. Check Postmark activity: https://account.postmarkapp.com
3. Review this documentation
4. Check webhook configuration

## License

MIT License - feel free to use and modify for your needs.

## Credits

Developed for PrestaShop 8.x
Postmark API integration

## Changelog

### Version 1.1.0 (Latest)
- ✨ **NEW**: Complete admin UI for sending newsletters
- ✨ **NEW**: Test newsletter feature - send your actual content to a test email
- ✨ **NEW**: Save newsletters as drafts
- ✨ **NEW**: Campaign history viewer with load/edit/delete actions
- ✨ **NEW**: Load and edit existing campaigns
- ✨ **NEW**: Campaign statistics (recipients, sent count, status)
- 🎨 Improved admin interface with better organization
- 📝 Updated documentation with admin UI usage guide

### Version 1.0.0
- Initial release
- Batch newsletter sending via Postmark (programmatic API)
- Automatic bounce handling with webhooks
- One-click unsubscribe (RFC 8058 compliant)
- Webhook support for Bounce, Delivery, Spam events
- Campaign management (programmatic)
- Email tracking (opens and clicks)
- Multi-language support (11 languages)
- Personalization variables
- Statistics dashboard
