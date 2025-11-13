{*
* Postmark Newsletter - Customer Stats Template
*}

{if isset($postmark_stats)}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-envelope"></i> {l s='Postmark Newsletter Statistics' mod='postmarknewsletter'}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>{l s='Total Emails Sent:' mod='postmarknewsletter'}</strong> {$postmark_stats.total_sent|default:0}</p>
            </div>
            <div class="col-md-6">
                <p><strong>{l s='Total Bounced:' mod='postmarknewsletter'}</strong> {$postmark_stats.total_bounced|default:0}</p>
            </div>
        </div>
    </div>
</div>
{/if}
