{*
* Postmark Newsletter - Unsubscribe Template
*}

{extends file='page.tpl'}

{block name='page_title'}
    {l s='Newsletter Unsubscribe' mod='postmarknewsletter'}
{/block}

{block name='page_content'}
    <div class="postmark-unsubscribe-container">
        {if isset($success) && $success}
            <div class="alert alert-success" role="alert">
                <i class="material-icons">check_circle</i>
                <h3>{l s='Unsubscribed Successfully' mod='postmarknewsletter'}</h3>
                <p>{$message}</p>
                <p>{l s='Email address:' mod='postmarknewsletter'} <strong>{$email}</strong></p>
                <p>{l s='You will no longer receive our newsletter. We\'re sorry to see you go!' mod='postmarknewsletter'}</p>
                <p>
                    <a href="{$urls.base_url}" class="btn btn-primary">
                        {l s='Return to Homepage' mod='postmarknewsletter'}
                    </a>
                </p>
            </div>
        {elseif isset($error)}
            <div class="alert alert-danger" role="alert">
                <i class="material-icons">error</i>
                <h3>{l s='Error' mod='postmarknewsletter'}</h3>
                <p>{$error}</p>
                <p>
                    <a href="{$urls.base_url}" class="btn btn-primary">
                        {l s='Return to Homepage' mod='postmarknewsletter'}
                    </a>
                </p>
            </div>
        {elseif isset($show_confirm) && $show_confirm}
            <div class="unsubscribe-confirm-box">
                <div class="alert alert-warning" role="alert">
                    <i class="material-icons">warning</i>
                    <h3>{l s='Confirm Unsubscribe' mod='postmarknewsletter'}</h3>
                    <p>{l s='Are you sure you want to unsubscribe from our newsletter?' mod='postmarknewsletter'}</p>
                    <p>{l s='Email address:' mod='postmarknewsletter'} <strong>{$email}</strong></p>
                </div>

                <form method="post" action="">
                    <input type="hidden" name="token" value="{$token}">
                    <input type="hidden" name="confirm" value="1">

                    <div class="form-actions">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="material-icons">unsubscribe</i>
                            {l s='Yes, Unsubscribe Me' mod='postmarknewsletter'}
                        </button>
                        <a href="{$urls.base_url}" class="btn btn-secondary btn-lg">
                            {l s='No, Keep My Subscription' mod='postmarknewsletter'}
                        </a>
                    </div>
                </form>

                <div class="unsubscribe-info">
                    <h4>{l s='What happens when you unsubscribe?' mod='postmarknewsletter'}</h4>
                    <ul>
                        <li>{l s='You will stop receiving our promotional newsletters' mod='postmarknewsletter'}</li>
                        <li>{l s='You will still receive important transactional emails (order confirmations, shipping updates, etc.)' mod='postmarknewsletter'}</li>
                        <li>{l s='You can resubscribe at any time from your account settings' mod='postmarknewsletter'}</li>
                    </ul>
                </div>
            </div>
        {/if}
    </div>
{/block}

{block name='page_footer'}
    <style>
        .postmark-unsubscribe-container {
            max-width: 700px;
            margin: 40px auto;
            padding: 20px;
        }

        .unsubscribe-confirm-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-actions {
            margin: 30px 0;
            text-align: center;
        }

        .form-actions .btn {
            margin: 10px;
        }

        .unsubscribe-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .unsubscribe-info ul {
            list-style-type: none;
            padding-left: 0;
        }

        .unsubscribe-info ul li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
        }

        .unsubscribe-info ul li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }

        .alert i.material-icons {
            vertical-align: middle;
            margin-right: 10px;
        }
    </style>
{/block}
