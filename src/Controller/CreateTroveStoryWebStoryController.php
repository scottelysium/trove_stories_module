<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
//use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
//use Drupal\webform\Entity\WebformSubmission;

/**
 * Controller for creating a trove story display
 */
class CreateTroveStoryWebStoryController extends ControllerBase {

    public function createStory(Request $request) {

        //load webform submission data
        $submission_id = $request->query->get('submission_id');
        
        $destination = $request->query->get('destination');

        $storySubmissionNode = Node::load($submission_id);

        if ($storySubmissionNode->bundle() !== 'trove_story_submission') {
            return new RedirectResponse($destination);
        }

        $webStoryNodeTitle = $storySubmissionNode->get('title')->value;
        $webStoryTitle = $storySubmissionNode->get('field_tss_story_title')->value; //note different from default title
        $webStoryUse = $storySubmissionNode->get('field_tss_story_use')->value;
        $webStoryAbout = $storySubmissionNode->get('field_tss_story_about')->value;
        $webStoryAuthor = $storySubmissionNode->get('field_tss_story_author')->value;
        $webStoryEmail = $storySubmissionNode->get('field_tss_story_email')->value;
        $webStoryInspiration = $storySubmissionNode->get('field_tss_story_inspiration')->value;
        $webStoryName = $storySubmissionNode->get('field_tss_story_name')->value;
        $webStoryPostcode = $storySubmissionNode->get('field_tss_story_postcode')->value;
        $webStoryBirth = $storySubmissionNode->get('field_tss_story_year_of_birth')->value;

        //get links
        $webStoryLinks = $storySubmissionNode->get('field_tss_story_links')->getValue();

        //get images
        $webStoryImages = $storySubmissionNode->get('field_tss_story_images')->getValue(); //this has the media ids already set no extra porcessing needed.

        $trove_story_web_story = Node::create(array(
            'type' => 'trove_story_web_story',
            'title' => $webStoryNodeTitle,
            'langcode' => 'en',
            'uid' => \Drupal::currentUser()->id(), 
            'status' => 0, //not published
            'field_tsws_story_use' => $webStoryUse,
            'field_tsws_story_about' => $webStoryAbout,
            'field_tsws_story_links' => $webStoryLinks, //might not work
            'field_tsws_story_author' => $webStoryAuthor,
            'field_tsws_story_email' => $webStoryEmail,
            'field_tsws_story_images' => $webStoryImages,
            'field_tsws_story_inspiration' => $webStoryInspiration,
            'field_tsws_story_name' => $webStoryName,
            //'field_tsws_story_category' => //Category field defaults to 'none'
            'field_tsws_story_postcode' => $webStoryPostcode,
            'field_tsws_story_title' => $webStoryTitle,
            'field_tsws_story_year_of_birth' => $webStoryBirth
        ));

        $trove_story_web_story->save();

        
        $trove_story_web_story_edit_link = $trove_story_web_story->toUrl('edit-form')->toString();
        $trove_story_web_story_link = $trove_story_web_story->toUrl()->toString();

        $this->messenger()->addStatus("The story submission has been turned into a new <strong><a href='" . 
        $trove_story_web_story_link . "'>trove website story</a> item</strong>");

        //return $this->redirect($trove_story_web_story_link);
        return new RedirectResponse($trove_story_web_story_edit_link);

        // if ($destination) {
        //     return new RedirectResponse($destination);
        // }

    }
}

/*
<?php

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


class MyModuleController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * Flags a node as spam.
   *
   * @param \Drupal\node\NodeInterface $node
   * The node to be flagged.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   * A redirect response back to the node list.
   
  public function flagSpam(NodeInterface $node) {
    // 1. Perform the action.
    // In a real-world scenario, you might add a flag, update a field,
    // or send a notification. For this example, we'll just show a message.
    
    // Add logic here to flag the node as spam.
    // For example:
    // $node->set('field_is_spam', TRUE);
    // $node->save();

    // 2. Add a message to the user.
    $this->messenger()->addStatus($this->t('The node %title has been flagged as spam.', ['%title' => $node->label()]));

    // 3. Redirect the user back to the content list page.
    return $this->redirect('system.admin_content');
  }
}
*/

/*
YML
my_module.node.flag_spam:
  path: '/admin/content/node/{node}/flag-spam'
  defaults:
    _controller: '\Drupal\my_module\Controller\MyModuleController::flagSpam'
    _title: 'Flag as Spam'
  requirements:
    _permission: 'access content'
*/

/*
function my_module_entity_operation(EntityInterface $entity) {
  $operations = [];

  // Add a custom operation for 'article' nodes.
  if ($entity->getEntityTypeId() == 'node' && $entity->bundle() == 'article') {
    $operations['flag_as_spam'] = [
      'title' => t('Flag as spam'),
      'url' => Url::fromRoute('my_module.node.flag_spam', ['node' => $entity->id()]),
      'weight' => 50,
    ];
  }

  return $operations;
}
*/