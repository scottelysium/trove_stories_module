<?php
namespace Drupal\trove_stories\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Config\FileStorage;

/**
 * Controller for trove_stories custom operations.
 */
class UpdateTroveStoriesConfigController extends ControllerBase {

    public function updateConfig(Request $request) {

        //$config_path = drupal_get_path('module', 'example') . '/config/install';
        $config_path = \Drupal::service('extension.list.module')->getPath('trove_stories') . '/config/optional/';
        
        //$active_storage = \Drupal::service('config.storage');
        //$active_storage->write($name, $source->read($name));
        // $source   = new FileStorage($config_path);
        // $config_storage = \Drupal::service('config.storage');
        // $config_factory = \Drupal::configFactory();

        // foreach ($configsNames as $name) {
        //     $config_storage->write($name, $source->read($name));
        //     $config_factory->getEditable($name)->set('uuid', $uuid_service->generate())->save();
        // }

        $files = glob($config_path . '*.yml');

        foreach ($files as $filepath) {
           $source = new FileStorage($filepath);
           echo "1";
        }
            
        $this->messenger()->addStatus("Configuration has been updated!" . $config_path);

        return new RedirectResponse('/admin/config/system/trove_stories');
        
    }
}