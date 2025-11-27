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
class CreateTroveStoryDisplayController extends ControllerBase {

    public function createStory(Request $request) {

        //load webform submission data
        $submission_id = $request->query->get('submission_id');

        /** @var Drupal\webform\Entity\WebformSubmission $submission */
        $submission = \Drupal::entityTypeManager()->getStorage('webform_submission')->load($submission_id);

        $submissionData = $submission->getData();

        $uploaded_media_ids = [];

        foreach($submissionData["upload_images"] as $file_id) {
            
            $file = File::load($file_id);
            $media = Media::create([
            'bundle' => 'image',
            'uid' => \Drupal::currentUser()->id(),
            'status' => 1,
            'name' => $file->getFilename(),
            'field_media_image' => [
                'target_id' => $file->id(),
                'alt' => $file->getFilename(),
                'title' => $file->getFilename(),
                ],
            ]);

            $media->save();

            $uploaded_media_ids[] = ['target_id' => $media->id()];
        }

        /*
        2471, 2472,2473 these are stored in the file_managed table, and the ids reference these.
        $submissionData["upload_images"]
        $submissionData["trove_urls"]
        $submissionData["display_name_story_author"]
        $submissionData["email"]
        $submissionData["name"]
        $submissionData["postcode"]
        $submissionData["story_title"]
        $submissionData["tell_us_about_your_story"]
        $submissionData["use_trove"]
        $submissionData["what_inspired_you_to_make_this_project"]
        $submissionData["year_of_birth"]
        */

        
        //create new trove story display from the data
        $trove_story_display = Node::create(array(
            'type' => 'trove_story_display',
            'title' => $submissionData["story_title"],
            'langcode' => 'en',
            'uid' => \Drupal::currentUser()->id(), 
            'status' => 0,
            'field_tsd_story_use' => $submissionData["use_trove"],
            'field_tsd_story_about' => $submissionData["tell_us_about_your_story"],
            'field_tsd_story_links' => $submissionData["trove_urls"],
            'field_tsd_story_author' => $submissionData["display_name_story_author"],
            'field_tsd_story_email' => $submissionData["email"],
            'field_tsd_story_images' => $uploaded_media_ids,
            'field_tsd_story_inspiration' => $submissionData["what_inspired_you_to_make_this_project"],
            'field_tsd_story_name' => $submissionData["name"],
            //DONT FORGET CATEGORY
            'field_tsd_story_postcode' => $submissionData["postcode"],
            'field_tsd_story_title' => $submissionData["story_title"],
            'field_tsd_story_year_of_birth' => $submissionData["year_of_birth"]
        ));

        $trove_story_display->save();

        $trove_story_display_link = $trove_story_display->toUrl()->toString();

        //get the original page url that sent the request so we can send them back there
        $destination = $request->query->get('destination');

        $this->messenger()->addStatus("The webform submission has been turned into a new <strong><a href='" . $trove_story_display_link . "'>trove story</a></strong>");
        
        if ($destination) {
            return new RedirectResponse($destination);
        }

        $this->redirect('system.admin_content');
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