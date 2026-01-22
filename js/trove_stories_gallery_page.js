


(function (Drupal) {

    document.addEventListener("DOMContentLoaded", function(event) {

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
            // console.log("Search box onchange");
            
            // console.log(e.target.value);

            //show the clear search button now that we are searching
            buttonClearSearch.classList.add('show');

            // hide load more button with toggle class.
            loadMoreButton.classList.add("hide");

            //if empty (eg backspace deleting to nothing)
            if (!e.target.value) {
                loadMoreButton.classList.remove("hide");
                buttonClearSearch.classList.remove('show');
                clearList();
                fetchStoryItems(0); //default back to first load
                return;
            }

            const searchString = encodeURIComponent(e.target.value);
           // console.log(searchString);
            searchStoryItems(searchString);
        });

        buttonClearSearch.addEventListener("click", function() {
            loadMoreButton.classList.remove("hide");
            buttonClearSearch.classList.remove('show');
            inputSearchBox.value = ''; //clear text written in.
            clearList();
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
                    createList(data);
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
                console.log(data);
                if (data) {
                    clearList();
                    createList(data);
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

            const number_of_cols = 3;

            console.log(storyItems.length);
            for (let i = 0; i < storyItems.length; i = i + number_of_cols) {

                /* when loop by 3, and access the index 1 before and 1 after per loop. */

                let colIndexOne = i;
                let colIndexTwo = i + 1;
                let colIndexThree = i + 2;

                const colStoryObjectOne = storyItems[colIndexOne];

                if (typeof colStoryObjectOne !== 'undefined') {
                    const storyElementColOne = document.createElement("div");
                    storyElementColOne.classList.add("storyItem", "troveStoriesFade");
                    storyElementColOne.innerHTML = generateHtmlStoryItem(colStoryObjectOne);
                    //console.log("creating element");
                    masonyCol1.appendChild(storyElementColOne);
                }
                
                const colStoryObjectTwo = storyItems[colIndexTwo];

                if (typeof colStoryObjectTwo !== 'undefined') {
                    const storyElementColTwo = document.createElement("div");
                    storyElementColTwo.classList.add("storyItem", "troveStoriesFade");
                    storyElementColTwo.innerHTML = generateHtmlStoryItem(colStoryObjectTwo);
                    //console.log("creating element");
                    masonyCol2.appendChild(storyElementColTwo);
                }

                const colStoryObjectThree = storyItems[colIndexThree];

                if (typeof colStoryObjectThree !== 'undefined') {
                    const storyElementColThree = document.createElement("div");
                    storyElementColThree.classList.add("storyItem", "troveStoriesFade");
                    storyElementColThree.innerHTML = generateHtmlStoryItem(colStoryObjectThree);
                    //console.log("creating element");
                    masonyCol3.appendChild(storyElementColThree);
                }
                
            }
        }

        function generateHtmlStoryItem(item) {
            let htmlContent = "";

            htmlContent += "<a href='" + item.story_link + "'>";
            htmlContent += "<img class='troveStoriesFade' src='" + item.thumbnail_url + "' alt='trove stories gallery item' />";
            htmlContent += "<h2 class='storyItemTitle'>" + item.story_title + "</h2>";
            htmlContent += "<div class='colorBar'></div>";
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