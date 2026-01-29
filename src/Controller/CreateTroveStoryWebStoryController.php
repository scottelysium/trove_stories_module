<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\Core\File\FileExists;
use Drupal\paragraphs\Entity\Paragraph;

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

        //$webStoryNodeTitle = $storySubmissionNode->get('title')->value;
        $webStoryTitle = $storySubmissionNode->get('field_tss_story_title')->value; //note different from default title
        //$webStoryUse = $storySubmissionNode->get('field_tss_story_use')->value;
        $webStoryAbout = $storySubmissionNode->get('field_tss_story_about')->value;
        $webStoryAuthor = $storySubmissionNode->get('field_tss_story_author')->value;
        $webStoryEmail = $storySubmissionNode->get('field_tss_story_email')->value;
        $webStoryInspiration = $storySubmissionNode->get('field_tss_story_inspiration')->value;
        //$webStoryName = $storySubmissionNode->get('field_tss_story_name')->value;
        //$webStoryPostcode = $storySubmissionNode->get('field_tss_story_postcode')->value;
        //$webStoryBirth = $storySubmissionNode->get('field_tss_story_year_of_birth')->value;

        //get links
        $webStoryLinks = $storySubmissionNode->get('field_tss_story_links')->getValue();

        $privateImageIds = $storySubmissionNode->get('field_tss_private_image_ids')->getValue();

        $newWebStoryImageIds = [];

        foreach($privateImageIds as $privateImageId) {
            $target_id = $privateImageId['value'];

            $source_file = File::load($target_id);

            if ($source_file instanceof File) { //we want the original File the media points to.

                //Prepare the public destination URI
                $filename = $source_file->getFilename();
                $destination = 'public://' . $filename;

                // Use FileRepository to copy the file and create a new File entity
                // This creates a physical copy and a new entry in the 'file_managed' table
                /** @var \Drupal\file\FileRepositoryInterface $file_repository */
                $file_repository = \Drupal::service('file.repository');
                $new_file = $file_repository->copy($source_file, $destination, FileExists::Rename);

                $new_file_media = Media::create([ //we create a new 'image' media type that references this newly cloned file.
                    'bundle' => 'image',
                    'uid' => \Drupal::currentUser()->id(),
                    
                    'status' => 1, //published
                    'name' => $new_file->getFilename(),
                    'field_media_image' => [
                        'target_id' => $new_file->id(),
                        'alt' => $new_file->getFilename(),
                        'title' => $new_file->getFilename(),
                        ],
                ]);

                $new_file_media->save();

                $newWebStoryImageIds[]['target_id'] = $new_file_media->id();

            }
        }

        //setup the formatting of the various fields that go into the main content field
        $formated_content = "";
        if (!empty($webStoryAbout)) {
            $formated_content .= "<p>" . $webStoryAbout . "</p>";
        }
        
        if (!empty($webStoryInspiration)) {
            $formated_content .= "<h2>What Inspired this project</h2>";
            $formated_content .= "<p>" . $webStoryInspiration . "</p>";
        }

        //reate the paragraph field for the story gallery
        $paragraphGallery = Paragraph::create([
            'type' => 'trove_story_gallery_images',
            'field_ptsws_gallery_images' => $newWebStoryImageIds,
        ]);
        $paragraphGallery->save();

        $trove_story_web_story = Node::create(array(
            'type' => 'trove_story_web_story',
            'title' => $webStoryTitle, // 'story title' field becomes node title.
            'langcode' => 'en',
            'uid' => \Drupal::currentUser()->id(), 
            'status' => 0, //not published by default
            //'field_tsws_story_use' => $webStoryUse,
            'field_tsws_content' => [
                'value' => $formated_content,
                'format' => 'basic_html'
            ],
            'field_tsws_story_about' => $webStoryAbout,
            'field_tsws_story_links' => $webStoryLinks,
            'field_tsws_story_author' => $webStoryAuthor,
            'field_tsws_story_email' => $webStoryEmail,
            'field_tsws_story_gallery' => [
                [
                    'target_id' => $paragraphGallery->id(),
                    'target_revision_id' => $paragraphGallery->getRevisionId(),
                ],
            ],
            'field_tsws_story_inspiration' => $webStoryInspiration,
            //'field_tsws_story_name' => $webStoryName,
            //'field_tsws_story_category' => //Category field defaults to 'none'
            //'field_tsws_story_postcode' => $webStoryPostcode,
            //'field_tsws_story_title' => $webStoryTitle,
            //'field_tsws_story_year_of_birth' => $webStoryBirth
        ));

        $trove_story_web_story->save();
        
        $trove_story_web_story_edit_link = $trove_story_web_story->toUrl('edit-form')->toString();
        $trove_story_web_story_link = $trove_story_web_story->toUrl()->toString();

        $this->messenger()->addStatus("The story submission has been turned into a new <strong><a href='" . 
        $trove_story_web_story_link . "'>trove website story</a> item</strong>");

        return new RedirectResponse($trove_story_web_story_edit_link);
    }
}
