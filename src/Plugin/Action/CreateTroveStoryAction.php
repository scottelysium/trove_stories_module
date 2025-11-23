<?php

//REMOVE: bulk action feature not really needed as each webform submission now has a "convert to trove story" button"

// namespace Drupal\trove_Stories\Plugin\Action;


// use Drupal\Core\Action\ActionBase;
// use Drupal\Core\Session\AccountInterface;
// use Drupal\node\Entity\Node;

// /**
//  * Provides an action with a confirmation form before updating taxonomy terms.
//  *
//  * @Action(
//  *   id = "create_trove_story_action",
//  *   label = @Translation("Convert a webform submission to a trove story"),
//  *   type = "webform_submission"
//  * )
//  */
// class CreateTroveStoryAction extends ActionBase {
    
//     /**
//     * {@inheritdoc}
//     */
//     public function execute($entity = NULL) {

//         if ($entity && $entity->data) {

//             $trove_story = Node::create([ //not working?
//                 'type' => 'trove_story',
//                 'title' => 'New trove story!!!', 
//                 'uid' => \Drupal::currentUser()->id(), // Set the author to the current user.
//                 'status' => 0, // Set to 1 for published, 0 for unpublished.
//             ]);

//             $trove_story->save();

//         }
//     }

//     public function access($object, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
//         echo "<br>ACESS function called<br><br>";
//         if (!$object) {
//             echo "<br>ACESS function called OBJKECT IS NULL<br><br>";
//         }

//         $access = $object->access('update', $account, TRUE); //EntityInterface object access method

//         return $return_as_object ? $access : $access->isAllowed();
//     }

// }