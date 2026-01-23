


(function (Drupal) {

    document.addEventListener("DOMContentLoaded", function(event) {

        const mediaQuery = window.matchMedia("(max-width: 650px)"); //falls into single col
        const mobile = mediaQuery.matches;
        //console.log ("mobile: " + mobile);

        let loadMoreOffset = 0;
        let amountPerLoad = 6; //the amount loaded in the next 'load more' call

        fetchStoryItems(0); //initial fetch, hardcode zero as offset variable updates

        //load more button
        const loadMoreButton = document.getElementById("trove-stories-load-more");

        //search box
        const inputSearchBox = document.getElementById("trove-stories-searchbox");

        //clear search button (cross)
        const buttonClearSearch = document.getElementById("clearSearchButton");
        
        //masony cols
        const masonyCol1 = document.querySelector(".masonry-col1");
        const masonyCol2 = document.querySelector(".masonry-col2");
        const masonyCol3 = document.querySelector(".masonry-col3");

        inputSearchBox.addEventListener("input", function(e) {

            //show the clear search button now that we are searching
            buttonClearSearch.classList.add('show');

            // hide load more button with toggle class.
            loadMoreButton.classList.add("hide");

            //if empty (eg backspace deleting to nothing)
            if (!e.target.value) {
                loadMoreButton.classList.remove("hide");
                buttonClearSearch.classList.remove('show');
                clearList();
                loadMoreOffset = 0;
                fetchStoryItems(0); //default back to first load
                return;
            }

            const searchString = encodeURIComponent(e.target.value);
            searchStoryItems(searchString);
        });

        buttonClearSearch.addEventListener("click", function() {
            loadMoreButton.classList.remove("hide");
            buttonClearSearch.classList.remove('show');
            inputSearchBox.value = ''; //clear text written in.
            clearList();
            loadMoreOffset = 0;
            fetchStoryItems(0); //default back to first load
        });
        
        loadMoreButton.addEventListener("click", function() {
            loadMoreOffset = loadMoreOffset + amountPerLoad;
            fetchStoryItems(loadMoreOffset);
        });

        async function fetchStoryItems(offset) {
            try {
                const storiesResponse = await fetch(`/trove-stories/api/website_stories/${offset}/${amountPerLoad}`);
                const data = await storiesResponse.json();
                //console.log(data);
                if (data) {
                    createList(data.story_gallery_items);
                    
                    //if we go past the total available, hide the load more button.
                    if ((loadMoreOffset + amountPerLoad) >= data.total) {
                        loadMoreButton.classList.add("hide");
                    } else {
                        loadMoreButton.classList.remove("hide");
                    }
                }

            } catch (e) {
                console.log("Error fetching stories:");
                console.error(e);
            }
        }

        async function searchStoryItems(searchString) {
            try {
                const storiesResponse = await fetch(`trove-stories/api/search_website_stories/${searchString}`);
                const data = await storiesResponse.json();
                //console.log(data);
                if (data) {
                    clearList();
                    createList(data.story_gallery_items);
                }

            } catch (e) {
                console.log("Error searching stories:");
                console.error(e);
            }
        }


        function clearList() {
            masonyCol1.innerHTML = '';
            masonyCol2.innerHTML = '';
            masonyCol3.innerHTML = '';
        }

        function createList(storyItems) {
            const listContainer = document.getElementById("trove-stories-gallery-list");
            if (!listContainer) return;

            if (mobile) {
                createMobileList(storyItems);
                return;
            }

            const number_of_cols = 3;
            //const number_of_cols = mobile ? 1 : 3;


            //console.log(storyItems.length);
            for (let i = 0; i < storyItems.length; i = i + number_of_cols) {

                /* when loop by 3, and access the index 1 ahead and 2 ahead */

                let colIndexOne = i;
                let colIndexTwo = i + 1;
                let colIndexThree = i + 2;

                const colStoryObjectOne = storyItems[colIndexOne];

                if (typeof colStoryObjectOne !== 'undefined') {
                    const storyElementColOne = document.createElement("div");
                    storyElementColOne.classList.add("storyItem", "troveStoriesFade");
                    storyElementColOne.innerHTML = generateHtmlStoryItem(colStoryObjectOne, colIndexOne);
                    masonyCol1.appendChild(storyElementColOne);
                }
                
                const colStoryObjectTwo = storyItems[colIndexTwo];

                if (typeof colStoryObjectTwo !== 'undefined') {
                    const storyElementColTwo = document.createElement("div");
                    storyElementColTwo.classList.add("storyItem", "troveStoriesFade");
                    storyElementColTwo.innerHTML = generateHtmlStoryItem(colStoryObjectTwo, colIndexTwo);
                    masonyCol2.appendChild(storyElementColTwo);
                }

                const colStoryObjectThree = storyItems[colIndexThree];

                if (typeof colStoryObjectThree !== 'undefined') {
                    const storyElementColThree = document.createElement("div");
                    storyElementColThree.classList.add("storyItem", "troveStoriesFade");
                    storyElementColThree.innerHTML = generateHtmlStoryItem(colStoryObjectThree, colIndexThree);
                    masonyCol3.appendChild(storyElementColThree);
                }
                
            }
        }

        function createMobileList(storyItems) {

            //for mobile we stik them all in the first col and hide the others.
            storyItems.forEach((storyItem, i) => {
                if (typeof storyItem !== 'undefined') {
                    const storyElementColMobile = document.createElement("div");
                    storyElementColMobile.classList.add("storyItem", "troveStoriesFade");
                    storyElementColMobile.innerHTML = generateHtmlStoryItem(storyItem, i);
                    masonyCol1.appendChild(storyElementColMobile);
                }
            });
        }

        function generateHtmlStoryItem(item, currentIndex) {
            //console.log("currentIndex" + (loadMoreOffset + currentIndex));

            const totalIndex = (loadMoreOffset + currentIndex);

           // console.log(totalIndex % amountPerLoad);

            let indexByAmount = totalIndex % amountPerLoad; //totalindex keeps going up, but we % back down to 0-5 for the six colours.

            let colorClass = '';

            switch (indexByAmount) {
                case 0:
                colorClass = 'green';
                break;
                case 1:
                colorClass = 'blue';
                break;
                case 2:
                colorClass = 'yellow';
                break;
                case 3:
                colorClass = 'red';
                break;
                case 4:
                colorClass = 'orange';
                break;
                case 5:
                colorClass = 'purple';
                break;
            }

            let htmlContent = "";

            htmlContent += "<a href='" + item.story_link + "'>";
            htmlContent += "<img class='troveStoriesFade' src='" + item.thumbnail_url + "' alt='trove stories gallery item' />";
            htmlContent += "<h2 class='storyItemTitle'>" + item.story_title + "</h2>";
            htmlContent += "<div class='colorBar " + colorClass + "'></div>";
            htmlContent += "</a>";

            return htmlContent;
        }

        

    });


})(Drupal);



// (function (Drupal) {
//     Drupal.behaviors.trove_stories_gallery_page = {
//         attach: function(context, settings) {

//             once('trove_stories_gallery_page', '#trove-stories-gallery').forEach(function (trove_form_element) {
//                 console.log("hello this should only show on the trove stories gallery page.")
//             })
//         }
//     };
// })(Drupal);