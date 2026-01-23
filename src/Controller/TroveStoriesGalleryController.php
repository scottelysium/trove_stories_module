<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for creating the page showing the trove stories gallery page.
 */
class TroveStoriesGalleryController extends ControllerBase {
    // public function createPage() {

    //     return [
    //         '#theme' => 'page__trove_stories_gallery_page',
    //     ];

    // }

    public function createPage() {

        $config = \Drupal::config('trove_stories.settings');
        $banner_text_heading = $config->get('trove_stories_gallery_banner_text_heading');
        $banner_text_message = $config->get('trove_stories_gallery_banner_text_message');

        return [
            '#theme' => 'page__trove_stories_gallery_page',
            '#banner_text_heading' => $banner_text_heading,
            '#banner_text_message' => $banner_text_message,
        ];

    }
}
