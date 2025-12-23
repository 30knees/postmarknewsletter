{*
* Postmark Newsletter - Admin Configuration Template
*}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-envelope"></i> {l s='Postmark Newsletter Module' mod='postmarknewsletter'}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <p>{l s='Send professional newsletters using Postmark with automatic bounce handling and easy unsubscribe functionality.' mod='postmarknewsletter'}</p>

                <div class="alert alert-info">
                    <h4>{l s='Getting Started' mod='postmarknewsletter'}</h4>
                    <ol>
                        <li>{l s='Create a Postmark account at' mod='postmarknewsletter'} <a href="https://postmarkapp.com" target="_blank">postmarkapp.com</a></li>
                        <li>{l s='Get your Server API Token from your Postmark account' mod='postmarknewsletter'}</li>
                        <li>{l s='Verify your sender email address in Postmark' mod='postmarknewsletter'}</li>
                        <li>{l s='Configure the settings below' mod='postmarknewsletter'}</li>
                        <li>{l s='Set up the webhook URL in your Postmark account (see below)' mod='postmarknewsletter'}</li>
                    </ol>
                </div>
            </div>
        </div>

        {if isset($stats)}
        <div class="row">
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-body text-center">
                        <p class="text-muted">{l s='Total Subscribers' mod='postmarknewsletter'}</p>
                        <h3>{$stats.total_subscribers|default:0}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-body text-center">
                        <p class="text-muted">{l s='Emails Sent' mod='postmarknewsletter'}</p>
                        <h3>{$stats.total_sent|default:0}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-body text-center">
                        <p class="text-muted">{l s='Total Bounces' mod='postmarknewsletter'}</p>
                        <h3>{$stats.total_bounces|default:0}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-body text-center">
                        <p class="text-muted">{l s='Auto Unsubscribed' mod='postmarknewsletter'}</p>
                        <h3>{$stats.auto_unsubscribed|default:0}</h3>
                    </div>
                </div>
            </div>
        </div>
        {/if}
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <i class="icon-paper-plane"></i> {l s='Send Newsletter' mod='postmarknewsletter'}
    </div>
    <div class="panel-body">
        <div class="alert alert-info">
            <strong>{l s='Send to:' mod='postmarknewsletter'}</strong> {$stats.total_subscribers|default:0} {l s='active subscribers' mod='postmarknewsletter'}
        </div>

        <form method="post" action="" id="newsletter-form">
            <div class="form-group">
                <label for="newsletter_subject">{l s='Subject *' mod='postmarknewsletter'}</label>
                <input type="text" name="newsletter_subject" id="newsletter_subject" class="form-control" placeholder="{l s='Your newsletter subject' mod='postmarknewsletter'}" required>
            </div>

            <div class="form-group">
                <label for="newsletter_html">{l s='HTML Content *' mod='postmarknewsletter'}</label>
                <textarea name="newsletter_html" id="newsletter_html" class="form-control" rows="15" placeholder="{l s='Paste your HTML newsletter content here...' mod='postmarknewsletter'}" required></textarea>
                <p class="help-block">
                    {l s='Paste your complete HTML newsletter here. Use {firstname}, {lastname}, and {email} for personalization.' mod='postmarknewsletter'}<br>
                    {l s='An unsubscribe link will be automatically added if not present.' mod='postmarknewsletter'}
                </p>
            </div>

            <div class="form-group">
                <label for="newsletter_text">{l s='Plain Text Content (Optional)' mod='postmarknewsletter'}</label>
                <textarea name="newsletter_text" id="newsletter_text" class="form-control" rows="8" placeholder="{l s='Plain text version for email clients that do not support HTML...' mod='postmarknewsletter'}"></textarea>
                <p class="help-block">{l s='If left empty, a plain text version will be auto-generated from the HTML.' mod='postmarknewsletter'}</p>
            </div>

            <div class="form-group">
                <label for="campaign_name">{l s='Campaign Name (Optional)' mod='postmarknewsletter'}</label>
                <input type="text" name="campaign_name" id="campaign_name" class="form-control" placeholder="{l s='e.g., Monthly Newsletter - December 2025' mod='postmarknewsletter'}">
                <p class="help-block">{l s='For tracking purposes in your reports.' mod='postmarknewsletter'}</p>
            </div>

            <div class="alert alert-warning">
                <i class="icon-warning"></i> {l s='This will send the newsletter to all active subscribers immediately. This action cannot be undone.' mod='postmarknewsletter'}
            </div>

            <button type="submit" name="sendNewsletter" class="btn btn-primary btn-lg" onclick="return confirm('{l s='Are you sure you want to send this newsletter to all subscribers?' mod='postmarknewsletter' js=1}');">
                <i class="icon-paper-plane"></i> {l s='Send Newsletter Now' mod='postmarknewsletter'}
            </button>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs"></i> {l s='Quick Actions' mod='postmarknewsletter'}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h4>{l s='Send Test Newsletter' mod='postmarknewsletter'}</h4>
                <p>{l s='Send a test email to verify your Postmark configuration.' mod='postmarknewsletter'}</p>
                <form method="post" action="">
                    <div class="form-group">
                        <label>{l s='Test Email Address' mod='postmarknewsletter'}</label>
                        <input type="email" name="test_email" class="form-control" placeholder="test@example.com" required>
                    </div>
                    <button type="submit" name="sendTestNewsletter" class="btn btn-default">
                        <i class="icon-envelope"></i> {l s='Send Test Email' mod='postmarknewsletter'}
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <h4>{l s='Postmark Statistics' mod='postmarknewsletter'}</h4>
                <p>{l s='View your Postmark delivery statistics and performance metrics.' mod='postmarknewsletter'}</p>
                <a href="https://account.postmarkapp.com" target="_blank" class="btn btn-default">
                    <i class="icon-bar-chart"></i> {l s='View Postmark Dashboard' mod='postmarknewsletter'}
                </a>
            </div>
        </div>
    </div>
</div>
