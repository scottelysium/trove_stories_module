<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\image\Entity\ImageStyle;
use Drupal\media\Entity\Media;

/**
 * Controller for returning json data for the trove stories page (listing items, searching etc.)
 */
class TroveStoriesApiController extends ControllerBase {


    public function searchWebsiteStories($searchString) { //ensure searchString is Urlencoded 

        // $data = [
        //     'searchString' => $searchString,
        //     'timestamp' => time(),
        //     'payload' => [
        //         ['id' => 1, 'name' => 'Project Alpha'],
        //         ['id' => 2, 'name' => 'Project Beta'],
        //     ],
        // ];

        $storage = \Drupal::entityTypeManager()->getStorage('node');
        $query = $storage->getQuery();
        $query->condition('type', 'trove_story_web_story')
                ->condition('status', 1)
               // ->condition('field_tsws_story_title', rawurldecode($searchString), 'CONTAINS') //this is just LIKE '%$searchString%'
                ->condition('title', rawurldecode($searchString), 'CONTAINS')
                ->sort('created', 'DESC');
        
        $query->accessCheck(TRUE); //fails if user can't access throws error if not included

        $story_ids = $query->execute();
        
        /** @var \Drupal\node\NodeInterface[] $web_stories */
        $searched_web_stories = $storage->loadMultiple($story_ids);

        $story_gallery_items = $this->processStoriesJson($searched_web_stories);
        
        return new JsonResponse($story_gallery_items);
    }

    public function getWebsiteStories($offset, $amountPerLoad) {
        
        $storage = \Drupal::entityTypeManager()->getStorage('node');
        $query = $storage->getQuery();
        $query->condition('type', 'trove_story_web_story')
                ->condition('status', 1)
                ->sort('created', 'DESC');

        // offset and limit: range($start, $length)
        // This skips the first '$offset' results and takes the next '$amountPerLoad'.
        $query->range($offset, $amountPerLoad);

        $query->accessCheck(TRUE); //fails if user can't access throws error if not included

        $story_ids = $query->execute();

        /** @var \Drupal\node\NodeInterface[] $web_stories */
        $web_stories = $storage->loadMultiple($story_ids);

        $story_gallery_items = $this->processStoriesJson($web_stories);
        

        return new JsonResponse($story_gallery_items);
    }

    private function processStoriesJson($web_stories) {
        $story_gallery_items = [];
        $thumbnail_url = "";

        foreach ($web_stories as $web_story) {
            
            /** @var \Drupal\Core\Field\EntityReferenceFieldItemListInterface $images */
            $paragraph_thumbnails = $web_story->get('field_tsws_story_thumbnail')->referencedEntities();

            foreach ($paragraph_thumbnails as $paragraph_thumbnail) {
                
                //first check if there is a 'browse' thumbnail, if so we always use this.
                if (!$paragraph_thumbnail->get('field_ptsws_browse_thumbnail')->isEmpty()) {
                    //first get the media reference field
                    $browse_media = $paragraph_thumbnail->get('field_ptsws_browse_thumbnail')->entity;
                    if ($browse_media instanceof Media) {
                        
                        $thumb_file = $browse_media->get('field_media_image')->entity;

                        $uri = $thumb_file->getFileUri();
                        $style = ImageStyle::load('trove_story_thumbnail_style'); //Thumbnail size (320 width × auto height)
                        $thumbnail_url = $style->buildUrl($uri);;
                    }
                } else {
                    //check for category thumbnail value
                    if (!$paragraph_thumbnail->get('field_ptsws_category_thumb')->isEmpty()) {
                        $category_thumb_value = $paragraph_thumbnail->get('field_ptsws_category_thumb')->value;
                        $module_path = \Drupal::service('extension.path.resolver')->getPath('module', 'trove_stories'); // eg "modules/custom/trove_stories"
                        
                        $thumb_path = "/" . $module_path . "/images/thumbnail_category_images/";

                        $thumb_filename = "";

                        switch ($category_thumb_value) {
                            case "thumb_newspapers_and_gazettes":
                                $thumb_filename = "newspapers_and_gazettes.jpg";
                                break;
                            case "thumb_magazines_and_newsletters":
                                $thumb_filename = "magazines_and_newsletters.jpg";
                                break;
                            case "thumb_images_maps_and_artefacts":
                                $thumb_filename = "images_maps_and_artefacts.jpg";
                                break;
                            case "thumb_research_and_reports":
                                $thumb_filename = "research_and_reports.jpg";
                                break;
                            case "thumb_books_libraries":
                                $thumb_filename = "books_and_libraries.jpg";
                                break;
                            case "thumb_diaries_letters_archives":
                                $thumb_filename = "diaries_letters_and_archives.jpg";
                                break;
                            case "thumb_music_audio_and_video":
                                $thumb_filename = "music_audio_and_video.jpg";
                                break;
                            case "thumb_people_and_organisations":
                                $thumb_filename = "people_and_organisations.jpg";
                                break;
                            case "thumb_websites":
                                $thumb_filename = "websites.jpg";
                                break;
                            case "thumb_lists":
                                $thumb_filename = "lists.jpg";
                                break;
                        }
                        
                        $thumbnail_url = $thumb_path . $thumb_filename;
                        
                        
                    } else {
                        //if the category thumbnail is also empty then we need a default
                    }

                }
                
            }

            
            
            // /** @var \Drupal\node\NodeInterface[] $image_entities */
            // $image_entities = $images->referencedEntities();

            // foreach ($image_entities as $image_entity) {
            //     $file_entity = $image_entity->get('field_media_image')->entity;
            //     $uri = $file_entity->getFileUri();

            //     // Load the style and build the URL
            //     $style = ImageStyle::load('thumbnail');
            //     $styled_url = $style->buildUrl($uri);
            //     $thumbnails[] = $styled_url; 
            // }

            $story_gallery_items[] = [
                //'story_title' => $web_story->get('field_tsws_story_title')->value,
                'story_title' => $web_story->get('title')->value,
                'story_link' => $web_story->toUrl()->toString(),
                //'image_urls' => $thumbnails,
                'thumbnail_url' => $thumbnail_url
            ];
            
        }

        return $story_gallery_items;
    }

}