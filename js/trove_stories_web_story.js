(function (Drupal) {
    

    document.addEventListener("DOMContentLoaded", function(event) {

        const storySliderWrapper = document.querySelector(".story-slider-wrapper");

        if (!storySliderWrapper) return; //no images.

        /* setup image modal popup functionality **/
        const storyGalleryModal = document.getElementById("story-gallery-modal");
        const storyGalleryModalInner = document.getElementById("story-gallery-modal-inner");
        const modalCloseButton = document.getElementById("story-gallery-modal-close");
        modalCloseButton.addEventListener("click", function() {
            
            storyGalleryModal.classList.remove("show");
        });

        

        /** offset gallery to align with story-grid plus 120px indent*/
        const storyGrid = document.querySelector(".story-grid");
        storySliderWrapper.style.left = (storyGrid.offsetLeft + 120) + "px";

        storySliderWrapper.classList.add("show"); //makes nice fade in

        /** gallery slider functions */
        const rightButton = document.getElementById("story-button-right");
        const leftButton = document.getElementById("story-button-left");
        const storyGallerySlider = document.getElementById("story-gallery-slider");
        const storyImageWrappers = document.querySelectorAll(".story-gallery-slide");

        //setup click events for each image
        storyImageWrappers.forEach((imgWrapper, i, elements) => {
            imgWrapper.addEventListener("click", function(e) {
                console.log("event click for img number " + i);
                console.log(e.currentTarget);
                const fullImageUrl = e.currentTarget.getAttribute('data');
                console.log(fullImageUrl);
                const fullImageElement = document.createElement("img");
                fullImageElement.classList.add("modalFade");
                fullImageElement.src = fullImageUrl;
                storyGalleryModalInner.innerHTML = ''; //clear out previous img
                storyGalleryModalInner.appendChild(fullImageElement);
                //.getAttribute('data-item-id')
                storyGalleryModal.classList.add("show");
            });
        });

        // for (let i = 0; i < storyImageWrappers.length; i++) {
        //     console.log(i);
        //     storyImageWrappers[i].addEventListener("click", function() {
        //         console.log("event click for img number " + i);
        //     });
        // }
        

        const storyImages = document.querySelectorAll(".story-gallery-slider img");

        let leftEdgeImgIndex = 0;
        let leftEdgeNegative = 0; //gets added to each time imgs move left


        function toggleLeftButton() {
            if (leftEdgeImgIndex > 0) {
                leftButton.classList.remove("hide");
            } else {
                leftButton.classList.add("hide");
            }
        }

        toggleRightButton();

        function toggleRightButton() {

            if ((storyImages.length < 1)
            || (leftEdgeImgIndex >= (storyImages.length - 1)))
            {
                rightButton.classList.add("hide");
            } else {
                rightButton.classList.remove("hide");
            }
            
        }

        rightButton.addEventListener("click", function() {
            
            if (leftEdgeImgIndex < (storyImages.length - 1)) {
                const currentImgWidth = storyImages[leftEdgeImgIndex].width;
                leftEdgeNegative = leftEdgeNegative - currentImgWidth;
                storyGallerySlider.style.left = `${leftEdgeNegative}px`;
                leftEdgeImgIndex++;
            }

            toggleLeftButton();
            toggleRightButton();
        });

        leftButton.addEventListener("click", function() {
            if (leftEdgeImgIndex < storyImages.length
                && leftEdgeImgIndex > 0
            ) {
                const currentImgWidth = storyImages[leftEdgeImgIndex].width;
                leftEdgeNegative = leftEdgeNegative + currentImgWidth;
                storyGallerySlider.style.left = `${leftEdgeNegative}px`;
                leftEdgeImgIndex--;
            }

            toggleLeftButton();
            toggleRightButton();
        });

    });

    


})(Drupal);