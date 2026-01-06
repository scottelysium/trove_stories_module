<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        // 2. Return the JsonResponse object instead of a render array
        return new JsonResponse($data);
    }

    // public function searchWebsiteStories() {
        
    // }
}