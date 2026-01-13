(function (Drupal) {

    // Drupal.behaviors.trove_stories_js_webform = {
    //     attach: function(context, settings) {
    //         once('trove_stories_form', '#webform-submission-trove-story-add-form').forEach(function (trove_form_element) {
    //             console.log("trove_stories_js_webform");
    //         });
    //     }
    // }
    document.addEventListener("DOMContentLoaded", function(event) {

        const linkElements = document.querySelectorAll('[id^="edit-tswf-links-items-"]');
        console.log(linkElements);

        const linkAddButtonElement = document.querySelector('[id^="edit-tswf-links-add-submit"]');
        console.log(linkAddButtonElement);
        linkAddButtonElement.addEventListener("click", function() {
            console.log("add links button clicked");
        });

    });
    

})(Drupal);