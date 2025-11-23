<?php

namespace Drupal\trove_stories\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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

        return $form;

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