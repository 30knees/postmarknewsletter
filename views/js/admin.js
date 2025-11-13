/**
 * Postmark Newsletter - Admin JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {

        // Copy webhook URL to clipboard
        const webhookCodeElements = document.querySelectorAll('.postmark-webhook-info code');

        webhookCodeElements.forEach(function(element) {
            element.style.cursor = 'pointer';
            element.title = 'Click to copy';

            element.addEventListener('click', function() {
                const text = this.textContent;

                // Create temporary textarea
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();

                try {
                    document.execCommand('copy');

                    // Show success feedback
                    const originalText = this.textContent;
                    this.textContent = 'Copied!';
                    this.style.backgroundColor = '#5cb85c';
                    this.style.color = '#fff';

                    setTimeout(function() {
                        element.textContent = originalText;
                        element.style.backgroundColor = '#fff';
                        element.style.color = '#333';
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                }

                document.body.removeChild(textarea);
            });
        });

        // Test connection button handler
        const testConnectionBtn = document.querySelector('button[name="testPostmarkConnection"]');

        if (testConnectionBtn) {
            testConnectionBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const apiToken = document.querySelector('input[name="POSTMARK_API_TOKEN"]');

                if (!apiToken || !apiToken.value) {
                    alert('Please enter your Postmark API Token first.');
                    return;
                }

                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="icon-spinner icon-spin"></i> Testing connection...';
                this.disabled = true;

                // Submit form to test connection
                const form = this.closest('form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'testPostmarkConnection';
                input.value = '1';
                form.appendChild(input);
                form.submit();
            });
        }

        // Send test newsletter button handler
        const testNewsletterForm = document.querySelector('form button[name="sendTestNewsletter"]');

        if (testNewsletterForm) {
            testNewsletterForm.closest('form').addEventListener('submit', function(e) {
                const emailInput = this.querySelector('input[name="test_email"]');

                if (!emailInput || !emailInput.value) {
                    e.preventDefault();
                    alert('Please enter a test email address.');
                    return;
                }

                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    e.preventDefault();
                    alert('Please enter a valid email address.');
                    return;
                }

                // Show confirmation
                if (!confirm('Send a test newsletter to ' + emailInput.value + '?')) {
                    e.preventDefault();
                }
            });
        }

        // Auto-hide success messages after 5 seconds
        const successAlerts = document.querySelectorAll('.alert-success, .postmark-alert-success');

        successAlerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';

                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });

        // Form validation
        const configForm = document.querySelector('form[action*="configure=postmarknewsletter"]');

        if (configForm) {
            configForm.addEventListener('submit', function(e) {
                const apiToken = document.querySelector('input[name="POSTMARK_API_TOKEN"]');
                const fromEmail = document.querySelector('input[name="POSTMARK_FROM_EMAIL"]');
                const fromName = document.querySelector('input[name="POSTMARK_FROM_NAME"]');

                let errors = [];

                if (!apiToken || !apiToken.value) {
                    errors.push('Postmark API Token is required.');
                }

                if (!fromEmail || !fromEmail.value) {
                    errors.push('From Email is required.');
                }

                if (fromEmail && fromEmail.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(fromEmail.value)) {
                        errors.push('From Email must be a valid email address.');
                    }
                }

                if (!fromName || !fromName.value) {
                    errors.push('From Name is required.');
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Please fix the following errors:\n\n' + errors.join('\n'));
                }
            });
        }
    });
})();
