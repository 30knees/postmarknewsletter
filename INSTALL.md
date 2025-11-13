# Installation Instructions

## Method 1: Manual Installation

1. **Download the module**
   - Download the latest release as a ZIP file
   - Or clone this repository: `git clone https://github.com/yourusername/prestashop-postmark-newsletter.git`

2. **Upload to PrestaShop**
   - Copy the entire `prestashop-postmark-newsletter` folder to your PrestaShop's `modules/` directory
   - Rename the folder to `postmarknewsletter` (without the "prestashop-" prefix)
   - Your path should be: `/path/to/prestashop/modules/postmarknewsletter/`

3. **Set permissions**
   ```bash
   chmod -R 755 modules/postmarknewsletter/
   chown -R www-data:www-data modules/postmarknewsletter/
   ```

4. **Install via PrestaShop Admin**
   - Log in to your PrestaShop admin panel
   - Go to **Modules > Module Manager**
   - Search for "Postmark Newsletter"
   - Click **Install**

## Method 2: ZIP Upload (Coming Soon)

1. Download the release ZIP file
2. Go to **Modules > Module Manager** in PrestaShop admin
3. Click **Upload a module**
4. Select the ZIP file
5. The module will be automatically installed

## Post-Installation Setup

### 1. Configure Postmark Account

1. Create a free account at [postmarkapp.com](https://postmarkapp.com)
2. Create a new **Server** in Postmark
3. Verify your sender email address or domain
4. Get your **Server API Token** from server settings

### 2. Configure Module

1. In PrestaShop admin, go to **Modules > Module Manager**
2. Search for "Postmark Newsletter" and click **Configure**
3. Enter your settings:
   - **Postmark Server API Token**: Your API token from Postmark
   - **From Email**: Your verified sender email (e.g., newsletter@yourdomain.com)
   - **From Name**: Sender name (e.g., "Your Store Name")
   - **Message Stream**: Use "broadcast" for newsletters (default)
   - Enable tracking options as needed
   - Configure bounce settings
4. Click **Save**
5. Click **Test Connection** to verify your setup

### 3. Set Up Webhooks

1. Copy the **Webhook URL** shown in the module configuration
2. Go to your Postmark server settings
3. Navigate to **Webhooks**
4. Click **Add Webhook**
5. Paste the webhook URL
6. Enable these webhook types:
   - ✅ Bounce
   - ✅ Delivery
   - ✅ Spam Complaint
7. Save the webhook

## Verification

Test your installation:

1. Go to the module configuration page
2. Click **Test Connection** - should show success
3. Enter a test email address
4. Click **Send Test Email**
5. Check that you receive the test email
6. Check Postmark dashboard for delivery confirmation

## Troubleshooting

### Module doesn't appear in Module Manager

- Check folder name is exactly `postmarknewsletter` (lowercase, no spaces)
- Verify file permissions
- Check PrestaShop error logs

### Test Connection fails

- Verify API token is correct
- Check your server can reach api.postmarkapp.com
- Verify cURL extension is enabled in PHP

### Webhooks not working

- Verify webhook URL is accessible from internet
- Check your server accepts POST requests
- Review PrestaShop error logs
- Test webhook URL manually with curl

## Requirements

- PrestaShop 8.0 or higher
- PHP 7.2 or higher
- cURL PHP extension enabled
- HTTPS recommended for webhooks
- Active Postmark account

## Uninstallation

To uninstall the module:

1. Go to **Modules > Module Manager**
2. Find "Postmark Newsletter"
3. Click **Uninstall**
4. Confirm the action

**Note**: Uninstalling will delete all module data including:
- Newsletter logs
- Bounce records
- Unsubscribe tokens
- Campaign data

## Need Help?

- Check the [README.md](README.md) for usage instructions
- Review the [examples](examples/) folder for code samples
- Open an issue on GitHub for bugs or questions
