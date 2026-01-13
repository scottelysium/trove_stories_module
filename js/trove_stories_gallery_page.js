


(function (Drupal) {

    document.addEventListener("DOMContentLoaded", function(event) {

        let loadMoreOffset = 0;
        let amountPerLoad = 2; //the amount loaded in the next 'load more' call

        fetchStoryItems(0); //initial fetch, hardcode zero as offset variable updates

        //setup search box
        const searchBox = document.getElementById("trove-stories-searchbox");
        searchBox.addEventListener("input", function(e) {
            console.log("Search box onchange");
            console.log(e.target.value);
            const searchString = encodeURIComponent(e.target.value);
            console.log(searchString);
            searchStoryItems(searchString);
        });

        //setup load more button
        const loadMoreButton = document.getElementById("trove-stories-load-more");
        loadMoreButton.addEventListener("click", function() {
            
            loadMoreOffset = loadMoreOffset + amountPerLoad;
            console.log("loadMoreOffset is " + loadMoreOffset);
            fetchStoryItems(loadMoreOffset);
        });

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

        async function fetchStoryItems(offset) {
            try {
                const storiesResponse = await fetch(`/trove-stories/api/website_stories/${offset}/${amountPerLoad}`);
                const data = await storiesResponse.json();
                console.log(data);
                if (data) {
                    createList(data);
                }

            } catch (e) {
                console.log("Error fetching stories:");
                console.error(e);
            }
        }

        function clearList() {
            const listContainer = document.getElementById("trove-stories-gallery-list");
            listContainer.innerHTML = '';
        }

        function createList(storyItems) {
            const listContainer = document.getElementById("trove-stories-gallery-list");
            if (!listContainer) return;

            storyItems.forEach((item, i) => {
                const storyItemElement = document.createElement("div");
                storyItemElement.innerHTML = generateHtmlStoryItem(item);
                listContainer.appendChild(storyItemElement);
            });
        }

        function generateHtmlStoryItem(item) {
            let htmlContent = "";

            htmlContent += "<h3>" + item.story_title + "</h3>";
            htmlContent += "<img src='" + item.thumbnail_url + "' alt='trove stories gallery item' />";

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