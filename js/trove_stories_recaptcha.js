
console.log("test");

const form = document.getElementById("webform-submission-trove-story-add-form");

console.log(form);

form.addEventListener("submit", function (event) {
    event.preventDefault();
    console.log("normal submit");
    event.target.submit();
});

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
//                 console.log("once has found the form");
//                 console.log(trove_form_element);
//                 console.log(trove_form_element.op);
//                 console.log(trove_form_element.stories_hidden_recaptcha);
//                 console.log(settings);
//                 console.log(settings.trove_stories.trove_stories_recaptcha_site_key);

//                 //override submit handler for form
//                 trove_form_element.addEventListener('submit', function (event) {
//                 // trove_form_element.addEventListener('submit', function (event) {
//                 //     event.preventDefault();
//                 // });
//                 //trove_form_element.op.addEventListener('click', function (event) {
//                     console.log("SUBMIT EVENT");
//                     event.preventDefault();
//                     event.stopImmediatePropagation();

//                     // const clickedElement = event.target;
//                     // console.log(clickedElement);

//                     //now fetch the recaptcha token and put it in the hidden field
//                     grecaptcha.ready(function() {
//                         console.log("recaptcha is ready!");
//                         grecaptcha.execute(settings.trove_stories.trove_stories_recaptcha_site_key, {action: 'submit'}).then(function(token) {
                            
//                             console.log(token);
//                             trove_form_element.stories_hidden_recaptcha.value = token;
//                             trove_form_element.submit();
//                             //trove_form_element.op.click();
//                         });
//                     }); //if this fails we stil should submit

//                    //trove_form_element.submit();

//                 });
//             })

//         }
//     };
// })(Drupal);


