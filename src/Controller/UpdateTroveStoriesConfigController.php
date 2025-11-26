<?php
// namespace Drupal\trove_stories\Controller;
// use Drupal\Core\Controller\ControllerBase;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;
// use Drupal\Core\Config\FileStorage;

// /**
//  * Controller for trove_stories custom operations.
//  */
// class UpdateTroveStoriesConfigController extends ControllerBase {

//     /*
//     So this function essentially runs what would happen when you 
//     uninstall and reinstall a module. 
//     It will look at the configuration in the modules
//     config/optional and install configuration for each of these files.

//     Note: it will not delete fields and content types. If a field or
//     content type yml is removed from config/optional - it will NOT delete that
//     content type. Same goes with fields.

//     If a field or content type is deleted on the site, it will reinstall it.


//     */
//     public function updateConfig(Request $request) {

//         //$config_path = drupal_get_path('module', 'example') . '/config/install';
//         $config_path = \Drupal::service('extension.list.module')->getPath('trove_stories') . '/config/optional/';

//         $files = glob($config_path . '*.yml');

//         foreach ($files as $filepath) {
           
           
//            $name = basename($filepath, '.yml');
//            //$source = new FileStorage($filepath);
//            $source = new FileStorage($config_path);
//            $rt = $source->read($name);
//            $config_storage = \Drupal::service('config.storage');

//            //this writes new files??
//            $r = $config_storage->write($name, $source->read($name));

//            //this updates current files?
//            $config_factory = \Drupal::configFactory();
//            $active_config = $config_factory->getEditable($name);
            
//             foreach ($rt as $key => $value) {
//                 $active_config->set($key, $value);
//             }

//             $active_config->save(TRUE);
            
//         }
            
//         //maybe force a cache clear here

//         $this->messenger()->addStatus("Configuration has been updated!" . $config_path);

//         return new RedirectResponse('/admin/config/system/trove_stories');
        
//     }
// }