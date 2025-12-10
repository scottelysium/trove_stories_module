

(function (Drupal) {
    Drupal.behaviors.trove_stories = {
        attach: function(context, settings) {
            
            //console.log("attach loaded?");
            //get elements via context.querySelector
            //const formElement = context.querySelector('.webform-submission-contact-form');

            once('trove_stories', 'html').forEach(function (element) {
                console.log("only load once??");
            })

        }
    };
})(Drupal);


