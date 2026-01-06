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
            if (!str_contains($currentform->id(), "template_")) { //filter out the 'template' forms
                $select_form_ids[$currentform->id()] = $currentform->label();
            }
        }

        
        // $form['trove_stories_selected_form'] = [
        //     '#type' => 'select',
        //     '#title' => $this->t('Choose webform'),
        //     '#description' => $this->t('Select a form to use for Trove Stories'),
        //     '#options' => [
        //         'none' => $this->t('None'),
        //         ...$select_form_ids
        //     ],
        //     '#default_value' => $config->get('trove_stories_selected_form'),
        // ];

        $form['trove_stories_recaptcha_fieldset'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Recaptcha site keys'),
            '#description' => $this->t('Provide your recaptcha site key to protect the form from spam and bots'),
        ];

        $form['trove_stories_recaptcha_fieldset']['trove_stories_recaptcha_site_key'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Recaptcha site key'),
            //'#description' => $this->t('Provide your recaptcha site key to protect the form from spam and bots'),
            '#default_value' => $config->get('trove_stories_recaptcha_site_key'),
        ];

        $form['trove_stories_recaptcha_fieldset']['trove_stories_recaptcha_secret_key'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Recaptcha SECRET key'),
            //'#description' => $this->t('Provide your recaptcha site key to protect the form from spam and bots'),
            '#default_value' => $config->get('trove_stories_recaptcha_secret_key'),
        ];

        return $form;

    }

    /**
 * Custom submit handler for the action button.
 */
    public function submitUpdateConfig(array &$form, FormStateInterface $form_state) {
        $route_url = Url::fromRoute('trove_stories.update_trove_stories_config');
        $form_state->setRedirectUrl($route_url);
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        //todo if needed
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {

        $config = $this->config('trove_stories.settings');

        //$config->set('trove_stories_selected_form', $form_state->getValue('trove_stories_selected_form'));
        $config->set('trove_stories_recaptcha_site_key', $form_state->getValue('trove_stories_recaptcha_site_key'));
        $config->set('trove_stories_recaptcha_secret_key', $form_state->getValue('trove_stories_recaptcha_secret_key'));

        $config->save();

        /*IMPORTANT (for recaptcha): 
            because out module relies on hook_library_info_build() to dynamically
            insert the recaptcha api,
            which is heavily cached, we need to ensure a library cache clear so
            the recent values in this form are used in the hook.
        */
        \Drupal::service('library.discovery')->clearCachedDefinitions();

        return parent::submitForm($form, $form_state);
    }

};