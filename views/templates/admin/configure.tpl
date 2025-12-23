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
            <input type="hidden" name="campaign_id" id="campaign_id" value="{if isset($loaded_campaign)}{$loaded_campaign.id_campaign|intval}{else}0{/if}">

            {if isset($loaded_campaign)}
            <div class="alert alert-success">
                <i class="icon-info-circle"></i> {l s='Editing campaign:' mod='postmarknewsletter'} <strong>{$loaded_campaign.name|escape:'htmlall':'UTF-8'}</strong>
                <a href="?" class="btn btn-sm btn-default pull-right">{l s='Clear / New Newsletter' mod='postmarknewsletter'}</a>
            </div>
            {/if}

            <div class="form-group">
                <label for="newsletter_subject">{l s='Subject *' mod='postmarknewsletter'}</label>
                <input type="text" name="newsletter_subject" id="newsletter_subject" class="form-control" placeholder="{l s='Your newsletter subject' mod='postmarknewsletter'}" value="{if isset($loaded_campaign)}{$loaded_campaign.subject|escape:'htmlall':'UTF-8'}{/if}" required>
            </div>

            <div class="form-group">
                <label for="newsletter_html">{l s='HTML Content *' mod='postmarknewsletter'}</label>
                <textarea name="newsletter_html" id="newsletter_html" class="form-control" rows="15" placeholder="{l s='Paste your HTML newsletter content here...' mod='postmarknewsletter'}" required>{if isset($loaded_campaign)}{$loaded_campaign.html_content|escape:'htmlall':'UTF-8'}{/if}</textarea>
                <p class="help-block">
                    {l s='Paste your complete HTML newsletter here. Use {firstname}, {lastname}, and {email} for personalization.' mod='postmarknewsletter'}<br>
                    {l s='An unsubscribe link will be automatically added if not present.' mod='postmarknewsletter'}
                </p>
            </div>

            <div class="form-group">
                <label for="newsletter_text">{l s='Plain Text Content (Optional)' mod='postmarknewsletter'}</label>
                <textarea name="newsletter_text" id="newsletter_text" class="form-control" rows="8" placeholder="{l s='Plain text version for email clients that do not support HTML...' mod='postmarknewsletter'}">{if isset($loaded_campaign)}{$loaded_campaign.text_content|escape:'htmlall':'UTF-8'}{/if}</textarea>
                <p class="help-block">{l s='If left empty, a plain text version will be auto-generated from the HTML.' mod='postmarknewsletter'}</p>
            </div>

            <div class="form-group">
                <label for="campaign_name">{l s='Campaign Name (Optional)' mod='postmarknewsletter'}</label>
                <input type="text" name="campaign_name" id="campaign_name" class="form-control" placeholder="{l s='e.g., Monthly Newsletter - December 2025' mod='postmarknewsletter'}" value="{if isset($loaded_campaign)}{$loaded_campaign.name|escape:'htmlall':'UTF-8'}{/if}">
                <p class="help-block">{l s='For tracking purposes in your reports.' mod='postmarknewsletter'}</p>
            </div>

            <div class="alert alert-warning">
                <i class="icon-warning"></i> {l s='This will send the newsletter to all active subscribers immediately. This action cannot be undone.' mod='postmarknewsletter'}
            </div>

            <div class="btn-group btn-group-lg" role="group">
                <button type="submit" name="saveNewsletterDraft" class="btn btn-default">
                    <i class="icon-save"></i> {l s='Save as Draft' mod='postmarknewsletter'}
                </button>
                <button type="submit" name="sendNewsletter" class="btn btn-primary" onclick="return confirm('{l s='Are you sure you want to send this newsletter to all subscribers?' mod='postmarknewsletter' js=1}');">
                    <i class="icon-paper-plane"></i> {l s='Send Newsletter Now' mod='postmarknewsletter'}
                </button>
            </div>
        </form>

        <div class="well" style="margin-top: 20px;">
            <h4><i class="icon-flask"></i> {l s='Test Newsletter' mod='postmarknewsletter'}</h4>
            <p>{l s='Send the newsletter content above to a test email address before sending to all subscribers.' mod='postmarknewsletter'}</p>
            <form method="post" action="" id="test-newsletter-form">
                {* Pass the newsletter content as hidden fields *}
                <input type="hidden" name="newsletter_subject_test" id="newsletter_subject_test" value="">
                <input type="hidden" name="newsletter_html_test" id="newsletter_html_test" value="">
                <input type="hidden" name="newsletter_text_test" id="newsletter_text_test" value="">
                <div class="row">
                    <div class="col-md-6">
                        <input type="email" name="test_newsletter_email" id="test_newsletter_email" class="form-control" placeholder="test@example.com" required>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" onclick="sendTestNewsletter()">
                            <i class="icon-flask"></i> {l s='Send Test Newsletter' mod='postmarknewsletter'}
                        </button>
                    </div>
                </div>
            </form>
            <script>
            function sendTestNewsletter() {
                // Copy values from main form to test form
                document.getElementById('newsletter_subject_test').value = document.getElementById('newsletter_subject').value;
                document.getElementById('newsletter_html_test').value = document.getElementById('newsletter_html').value;
                document.getElementById('newsletter_text_test').value = document.getElementById('newsletter_text').value;

                // Validate
                if (!document.getElementById('newsletter_subject').value) {
                    alert('{l s='Please fill in the subject field first.' mod='postmarknewsletter' js=1}');
                    return false;
                }
                if (!document.getElementById('newsletter_html').value) {
                    alert('{l s='Please fill in the HTML content field first.' mod='postmarknewsletter' js=1}');
                    return false;
                }
                if (!document.getElementById('test_newsletter_email').value) {
                    alert('{l s='Please enter a test email address.' mod='postmarknewsletter' js=1}');
                    return false;
                }

                // Submit the test form
                var form = document.getElementById('test-newsletter-form');
                var button = document.createElement('input');
                button.type = 'hidden';
                button.name = 'sendTestNewsletterContent';
                button.value = '1';
                form.appendChild(button);
                form.submit();
            }
            </script>
        </div>
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

<div class="panel">
    <div class="panel-heading">
        <i class="icon-list"></i> {l s='Campaign History' mod='postmarknewsletter'}
    </div>
    <div class="panel-body">
        {if isset($campaigns) && count($campaigns) > 0}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{l s='ID' mod='postmarknewsletter'}</th>
                        <th>{l s='Campaign Name' mod='postmarknewsletter'}</th>
                        <th>{l s='Subject' mod='postmarknewsletter'}</th>
                        <th>{l s='Status' mod='postmarknewsletter'}</th>
                        <th>{l s='Recipients' mod='postmarknewsletter'}</th>
                        <th>{l s='Sent' mod='postmarknewsletter'}</th>
                        <th>{l s='Created' mod='postmarknewsletter'}</th>
                        <th>{l s='Actions' mod='postmarknewsletter'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$campaigns item=campaign}
                    <tr>
                        <td>{$campaign.id_campaign|intval}</td>
                        <td><strong>{$campaign.name|escape:'htmlall':'UTF-8'}</strong></td>
                        <td>{$campaign.subject|escape:'htmlall':'UTF-8'|truncate:50}</td>
                        <td>
                            {if $campaign.status == 'draft'}
                                <span class="badge badge-info">{l s='Draft' mod='postmarknewsletter'}</span>
                            {elseif $campaign.status == 'sending'}
                                <span class="badge badge-warning">{l s='Sending' mod='postmarknewsletter'}</span>
                            {elseif $campaign.status == 'sent'}
                                <span class="badge badge-success">{l s='Sent' mod='postmarknewsletter'}</span>
                            {elseif $campaign.status == 'failed'}
                                <span class="badge badge-danger">{l s='Failed' mod='postmarknewsletter'}</span>
                            {/if}
                        </td>
                        <td>{$campaign.total_recipients|intval}</td>
                        <td>{$campaign.total_sent|intval}</td>
                        <td>{$campaign.created_at|escape:'htmlall':'UTF-8'|date_format:"%Y-%m-%d %H:%M"}</td>
                        <td>
                            <div class="btn-group-action">
                                <form method="post" action="" style="display: inline;">
                                    <input type="hidden" name="campaign_id" value="{$campaign.id_campaign|intval}">
                                    <button type="submit" name="loadCampaign" class="btn btn-default btn-sm" title="{l s='Load & Edit' mod='postmarknewsletter'}">
                                        <i class="icon-edit"></i>
                                    </button>
                                </form>
                                <form method="post" action="" style="display: inline;" onsubmit="return confirm('{l s='Are you sure you want to delete this campaign?' mod='postmarknewsletter' js=1}');">
                                    <input type="hidden" name="campaign_id" value="{$campaign.id_campaign|intval}">
                                    <button type="submit" name="deleteCampaign" class="btn btn-danger btn-sm" title="{l s='Delete' mod='postmarknewsletter'}">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {else}
        <div class="alert alert-info">
            <i class="icon-info-circle"></i> {l s='No campaigns found. Create your first newsletter above!' mod='postmarknewsletter'}
        </div>
        {/if}
    </div>
</div>
