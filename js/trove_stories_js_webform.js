(function (Drupal) {

    Drupal.behaviors.trove_stories_js_webform = {
        attach: function(context, settings) {

            /* For link items, check if the url contains https/nla.gov.au and append helpful icons */
            once('trove_stories_form', '.js-form-item-tswf-links input[id^="edit-tswf-links-items-"]').forEach(function (linkElement) {

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

            /** Target the generated button that only shows after selecting some images to upload and disable it */
            once('trove_stories_form', '.js-form-item-tswf-upload-images input[id^="edit-tswf-upload-images-remove-button-"]').forEach(function (removeButton) {
                removeButton.disabled = true;
            });

            /** Target the checkboxes of image uploads after images have been selected */
            once('trove_stories_form', '.js-form-item-tswf-upload-images input[id^="edit-tswf-upload-images-file-"]').forEach(function (checkElement) {
                checkElement.addEventListener("click", function(e) {
                    toggleRemoveButton();
                });
            });

            /* if at least one checkbox is checked then enable the removeButton, otherwise disable */
            function toggleRemoveButton() {
                
                const checkBoxes = document.querySelectorAll(".js-form-item-tswf-upload-images input[id^='edit-tswf-upload-images-file-']");
                const removeButton = document.querySelector(".js-form-item-tswf-upload-images input[id^='edit-tswf-upload-images-remove-button-']");
                
                let atLeastOneChecked = false;
                checkBoxes.forEach((box, i) => {
                    if (box.checked) {
                        atLeastOneChecked = true;
                    }
                });

                if (atLeastOneChecked) {
                    removeButton.disabled = false;
                } else {
                    removeButton.disabled = true;
                }

            }
        }
    }
})(Drupal);