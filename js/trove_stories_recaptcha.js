
(function (Drupal) {
    Drupal.behaviors.trove_stories_form = {
        attach: function(context, settings) {

            
            once('trove_stories_form', '#webform-submission-trove-story-add-form').forEach(function (trove_form_element) { //should only loop once, just dealing with array
                
                if (typeof grecaptcha === 'undefined') {
                    console.log("recaptcha is not valid");
                    const recaptchaWarn = trove_form_element.querySelector('.recaptchaInvalid');
                    console.log(recaptchaWarn);
                    recaptchaWarn.classList.add("show");
                }


                /*
                    We have two buttons:
                    1) a 'fake' submission button that first fetches the recaptcha and fills a hidden field for the backend for validate
                    2) The real but hidden submit button that we call .click() on only after the recaptcha token has been fetched.

                    Why:
                    We still want the form to do a normal webform submission but we
                    also need to fetch the recaptcha token before submission. Simply
                    adding a submission handler on the form and calling the recaptcha code
                    there skips over the async recaptcha api call unless we preventDefault,
                    but preventDefault then requires another form.submit() call after the 
                    recaptcha api call is complete - this causes the submission handler to run
                    again in an infinite loop or would require some messy boolean logic.
                    The solution here is to have a fake submission button that after clicking
                    grabs the recaptcha, and then call the click event on the real form submission 
                    button. This also triggers the natural HTML5 form validation rules.
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
                        //TODO: add class/error message into the submit button template.
                    }
                });

            });
        }
    };
})(Drupal);

// console.log("test");

// const form = document.getElementById("webform-submission-trove-story-add-form");//webform-submission-test-add-form

// //document.getElementById("webform-submission-trove-story-add-form").submit();
// //document.getElementById("webform-submission-test-add-form").submit();
// //document.getElementById("edit-submit").click(); << this works
// //edit-submit

// console.log(form);

// form.addEventListener("submit", function (event) {
//     event.preventDefault();
//     console.log("normal submit");
//     event.target.submit();
// });

// (function (Drupal) {
//     Drupal.behaviors.trove_stories_form = {
//         attach: function(context, settings) {
            
//             //console.log("attach loaded?");
//             //get elements via context.querySelector
//             //const formElement = context.querySelector('.webform-submission-contact-form');
//             //const trove_form_element = context.querySelector('#webform-submission-trove-story-add-form');
//             once('trove_stories_form', '#webform-submission-trove-story-add-form').forEach(function (trove_form_element) { //should only loop once, just dealing with array
//                 //console.log("only load once??");

//                 //webform-submission-trove-story-add-form
//                 // console.log("once has found the form");
//                 // console.log(trove_form_element);
//                 // console.log(trove_form_element.op);
//                 // console.log(trove_form_element.stories_hidden_recaptcha);
//                 // console.log(settings);
//                 // console.log(settings.trove_stories.trove_stories_recaptcha_site_key);

//                 /**
//                  * 
//                  OTHER SOLUTIONS:
//                  - remove submit button
//                  - wrap submit button in div and grab onclick on that div ?
//                  - or look into why file upload has extra submit button - change it?
//                    - set the recaptcha token some other way.. like a fetch/post?
//                    - try ajax submission?
//                    - 
// SOLUTION >>>>>> 
 //                   what about creating a new button in a template that mimics the  submit button (but keep the submit button), then hide the submit button - we run our click on that button?
                       // the onlick of that button will fill the recaptcha - and THEN you can submit the form?
                       //you can event just do the .click event directly on the REAL submit button button then.
//                  */

//                 //override submit handler for form
//                 let setRecaptchaToken = false;
//                trove_form_element.addEventListener('submit', function (event) {
//                 // trove_form_element.addEventListener('submit', function (event) {
//                 //     event.preventDefault();
//                 // });
//                 //trove_form_element.op.addEventListener('click', function (event) {
//                     console.log("SUBMIT EVENT");
//                     if (!setRecaptchaToken) {
//                         event.preventDefault();
//                     }
//                    // event.stopImmediatePropagation();

//                     // const clickedElement = event.target;
//                     // console.log(clickedElement);
//                     setRecaptchaToken = true;
//                     //now fetch the recaptcha token and put it in the hidden field
//                     grecaptcha.ready(function() {
//                         console.log("recaptcha is ready!");
//                         grecaptcha.execute(settings.trove_stories.trove_stories_recaptcha_site_key, {action: 'submit'}).then(function(token) {
                            
//                             console.log(token);
//                             //trove_form_element.stories_hidden_recaptcha.value = token;
//                            // trove_form_element.submit();
//                            //set a global boool here?
//                            console.log("clicking event happens on:");
//                            console.log(trove_form_element.op);
                           
//                            console.log("setRecaptchaToken is" + setRecaptchaToken);
//                             trove_form_element.op.click();
//                         });
//                     }); //if this fails we stil should submit

//                    //trove_form_element.submit();

//                 });
//             })

//         }
//     };
// })(Drupal);


