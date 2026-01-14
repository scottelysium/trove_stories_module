(function (Drupal) {

    Drupal.behaviors.trove_stories_js_webform = {
        attach: function(context, settings) {

            /* For link items, check if the url contains https/nla.gov.au and append helpful icons */
            once('trove_stories_form', 'input[id^="edit-tswf-links-items-"]').forEach(function (linkElement) {

                validateTroveUrl(linkElement); //run once on load/ajax to reset all validations

                linkElement.addEventListener("input", function(e) {
                    validateTroveUrl(e.target);
                });
            });

            function validateTroveUrl(linkElement) {
                if (!linkElement.value) {
                    linkElement.parentElement.classList.remove("urlpass");
                    linkElement.parentElement.classList.remove("urlfail");
                    return;
                }

                urlText = linkElement.value.trim();
                
                if (
                        (['https://', 'http://'].some(protocol => urlText.toLowerCase().startsWith(protocol))) //starts with http(s)
                        &&
                        (urlText.includes("nla.gov.au")) //string contains nla.gov.au
                        &&
                        (!urlText.includes(" ")) //no empty spaces
                )
                    {
                    linkElement.parentElement.classList.remove("urlfail");
                    linkElement.parentElement.classList.add("urlpass");
                } else {
                    linkElement.parentElement.classList.remove("urlpass");
                    linkElement.parentElement.classList.add("urlfail");
                }
            }
        }
    }
})(Drupal);