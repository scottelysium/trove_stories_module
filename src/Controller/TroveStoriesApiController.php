<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\image\Entity\ImageStyle;

/**
 * Controller for returning json data for the trove stories page (listing items, searching etc.)
 */
class TroveStoriesApiController extends ControllerBase {
    public function getWebsiteStories() {

        /*
            You MAY want to cache this data:
            To make a JSON response cacheable in Drupal 10, you need to switch from the standard JsonResponse to Drupal’s CacheableJsonResponse.
        */
        $data = [
            'status' => 'success',
            'timestamp' => time(),
            'payload' => [
                ['id' => 1, 'name' => 'Project Alpha'],
                ['id' => 2, 'name' => 'Project Beta'],
            ],
        ];

        $web_stories = \Drupal::entityTypeManager()
            ->getStorage('node')
            ->loadByProperties([
                'type' => 'trove_story_web_story',
                'status' => 1, // Only published nodes
            ]);

        $story_gallery_items = [];

        foreach ($web_stories as $web_story) {
            //$story_gallery_items[]['test']['story_title'] = $web_story->get('field_tsws_story_title')->value;
            $story_title = $web_story->get('field_tsws_story_title')->value;

            

            //$story_gallery_items[]['test']['story_vlaue2'] = "moo";
            $thumbnails = [];
            $image_entities = $web_story->get('field_tsws_story_images')->referencedEntities();
            foreach ($image_entities as $image_entity) {
                $file_entity = $image_entity->get('field_media_image')->entity;
                $uri = $file_entity->getFileUri();

                // Load the style and build the URL
                $style = ImageStyle::load('thumbnail');
                $styled_url = $style->buildUrl($uri);
                //$story_gallery_items[]['story_images'] = $styled_url;
                $thumbnails[] = $styled_url; //these urls MUST be public
            }

            $story_gallery_items[] = [
                'story_title' => $web_story->get('field_tsws_story_title')->value,
                'image_urls' => $thumbnails
            ];

            
        }

        // 2. Return the JsonResponse object instead of a render array
        return new JsonResponse($story_gallery_items);
    }

    // public function searchWebsiteStories() {
        
    // }
}