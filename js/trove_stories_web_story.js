(function (Drupal) {
    

    document.addEventListener("DOMContentLoaded", function(event) {

        
        const storyGrid = document.querySelector(".story-grid");
    
        const storySliderWrapper = document.querySelector(".story-slider-wrapper");

        if (!storySliderWrapper) return;

        /** offset gallery to align with story-grid plus 120px indent*/
        storySliderWrapper.style.left = (storyGrid.offsetLeft + 120) + "px";

        storySliderWrapper.classList.add("show"); //makes nice fade in

        /** gallery slider functions */
        const rightButton = document.getElementById("story-button-right");
        const leftButton = document.getElementById("story-button-left");
        const storyGallerySlider = document.getElementById("story-gallery-slider");
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