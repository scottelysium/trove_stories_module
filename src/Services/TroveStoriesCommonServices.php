<?php
namespace Drupal\trove_Stories\Services;
use Drupal\node\NodeInterface;

class TroveStoriesCommonServices {

    public function getTroveStoriesFormId() {
        // $config = \Drupal::config('trove_stories.settings');
        // $selected_form_id = $config->get('trove_stories_selected_form');
        // return $selected_form_id;
        return "trove_story";
    }

  
    public function isTroveStoryForm() {

        $route_match = \Drupal::routeMatch();
        $route_name = $route_match->getRouteName();
        
        $webform = $route_match->getParameter('webform'); //this can return an object OR a simple id string.
        $selected_form_id = $this->getTroveStoriesFormId();

        if ($route_name === 'entity.webform.canonical' && $webform->id() === $selected_form_id) {
            return true;
        }

       // echo $route_name;

       //if (strpos($route_name, 'entity.webform.') === 0 || strpos($route_name, 'entity.webform_submission.') === 0) {

        //This checks if we are on the trove stories form
        // if (strpos($route_name, 'entity.webform.') === 0) {
        //     if (
        //         ($webform instanceof \Drupal\webform\WebformInterface && $webform->id() === $selected_form_id) //if an object we need to check id
        //         || 
        //         (is_string($webform) && $webform === $selected_form_id)) //if a string we assume it must be the id.
        //     { 
        //         return true;
        //     }
        // }

        return false;
    }

    public function isTroveStoryConfirmation() {
        $route_match = \Drupal::routeMatch();
        $route_name = $route_match->getRouteName(); 

        if ($route_name === "entity.webform.confirmation") { //confirmation page route
        
            $webform = $route_match->getParameter('webform');

            if (empty($webform)) return false;

            if ($webform->id() === $this->getTroveStoriesFormId()) {
                return true;
            }
        }

        return false;
    }

    public function isTroveStorySubmission() {
        
        $route_match = \Drupal::routeMatch();
        $route_node = $route_match->getParameter('node');
        
        if ($route_node instanceof NodeInterface) {
            if ($route_node->bundle() === 'trove_story_submission') {
                return true;
            }
        }

        return false;
    }

    public function isTroveStoryWebStory() {
    
        $route_match = \Drupal::routeMatch();
        $route_node = $route_match->getParameter('node');
        
        if ($route_node instanceof NodeInterface) {
            if ($route_node->bundle() === 'trove_story_web_story') {
                return true;
            }
        }

        return false;
    }

    public function isTroveStoryGalleryPage() {
        
        $route_match = \Drupal::routeMatch();

        $route_name = $route_match->getRouteName();

        // route: trove_stories.trove_stories
        if ($route_name === "trove_stories.trove_stories") {
            return true;
        }

        return false;
    }

}