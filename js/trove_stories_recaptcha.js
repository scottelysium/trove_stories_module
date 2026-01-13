
(function (Drupal) {
    Drupal.behaviors.trove_stories_form = {
        attach: function(context, settings) {

            once('trove_stories_form', '#webform-submission-trove-story-add-form').forEach(function (trove_form_element) { //should only loop once, just dealing with array
                
                if (typeof grecaptcha === 'undefined') {
                    const recaptchaWarn = trove_form_element.querySelector('.recaptchaInvalid');
                    recaptchaWarn.classList.add("show");
                }


                /*
                    We have two buttons:
                    1) a 'fake' submission button that first fetches the recaptcha and fills a hidden field for the backend for validate
                    2) The real but hidden submit button that we call .click() on only after the recaptcha token has been fetched.

                    Why:
                    We still want the form to do a normal webform submission but we
                    also need to fetch the recaptcha token on submit click but before 
                    the actual submission process. But adding a submission handler on the 
                    form and calling the recaptcha code there skips over the async 
                    recaptcha api call unless we preventDefault,
                    but preventDefault then requires another form.submit() call after the 
                    recaptcha api call is complete - this causes the submission handler to run
                    again in an infinite loop or would require some messy boolean logic.
                    The solution here is to have a fake submission button that after clicking
                    grabs the recaptcha, and then call the click event on the real form submission 
                    button. This also triggers the organic HTML5 form validation rules.
                    */
                const recaptchaSubmitButton = trove_form_element.querySelector('#recaptcha-submit');
                const hiddenSubmitButton = trove_form_element.querySelector('#edit-submit');

                recaptchaSubmitButton.addEventListener('click', function (event) {
                    
                    grecaptcha.ready(function() {
                        grecaptcha.ready(function() {
                            grecaptcha.execute(settings.trove_stories.trove_stories_recaptcha_site_key, {action: 'submit'}).then(
                                    function(token) {
                                        trove_form_element.tswf_recaptcha_token.value = token;
                                        hiddenSubmitButton.click();
                                    }
                                );
                        });
                    });
                });

                //this is the REAL submit button, but we stop it submitting if the recapthca token is not provided.
                hiddenSubmitButton.addEventListener('click', function (event) {
                    
                    if (!trove_form_element.tswf_recaptcha_token.value) {
                        event.preventDefault();
                        console.error("No recaptcha token provided");
                    }
                });

            });
        }
    };
})(Drupal);

