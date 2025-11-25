<?php

namespace Drupal\trove_stories\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Module settings form.
 */
class TroveStoriesSettingsForm extends ConfigFormBase {

    public function getFormId() {
        return 'trove_stories_form';
    }

    protected function getEditableConfigNames() {
        return ['trove_stories.settings'];
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $form = parent::buildForm($form, $form_state);

        $config = $this->config('trove_stories.settings');

        $entityTypeManager = \Drupal::service('entity_type.manager');
        $formsList = $entityTypeManager->getStorage('webform')->loadMultiple();

        $select_form_ids = [];

        foreach ($formsList as $currentform) {
            //echo $currentform->label() . "<br>";
            //echo $currentform->id() . "<br>";
            if (!str_contains($currentform->id(), "template_")) { //filter out the 'template' forms
                $select_form_ids[$currentform->id()] = $currentform->label();
            }
            
        }

        
        

        $form['trove_stories_selected_form'] = [

            '#type' => 'select',
            '#title' => $this->t('Choose webform'),
            '#description' => $this->t('Select a form to use for Trove Stories'),
            '#options' => [
                'none' => $this->t('None'),
                ...$select_form_ids
            ],
            '#default_value' => $config->get('trove_stories_selected_form'),

        ];

        $form['trove_stories_update_settings'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Update configurtation'),
            '#description' => $this->t('This is a custom action that will reinstall the configuration for this module after a manual update.'),
        ];

        $form['trove_stories_update_settings']['update_config'] = [
            '#type' => 'submit',
            '#value' => $this->t('Reinstall configuration'),
            '#submit' => ['::submitUpdateConfig'], 
            '#limit_validation_errors' => [],
        ];

        return $form;

    }

    /**
 * Custom submit handler for the action button.
 */
    public function submitUpdateConfig(array &$form, FormStateInterface $form_state) {
        // Redirect to your custom route.
        // $url = Url::fromRoute('my_module.custom_route'); 
        // $form_state->setRedirectUrl($url);
        $route_url = Url::fromRoute('trove_stories.update_trove_stories_config');
        $form_state->setRedirectUrl($route_url);
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        //todo if needed
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('trove_stories.settings');
       // $config->set('trove_stories.trove_stories_selected_form', $form_state->getValue('trove_stories_selected_form'));
        $config->set('trove_stories_selected_form', $form_state->getValue('trove_stories_selected_form'));
        $config->save();
        return parent::submitForm($form, $form_state);
    }

};