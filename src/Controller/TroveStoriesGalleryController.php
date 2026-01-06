<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for creating the page showing the trove stories gallery page.
 */
class TroveStoriesGalleryController extends ControllerBase {
    public function createPage() {

        return [
            '#theme' => 'page__trove_stories_gallery_page',
        ];

    }
}
