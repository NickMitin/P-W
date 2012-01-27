#!/usr/bin/php  
<?php
         
  error_reporting(0);
  ini_set(display_errors, 0);
    
  $_SERVER['DOCUMENT_ROOT'] = '/mymedia/projects/ff/www/';
  $_SERVER['REQUEST_URI'] = '';
  require_once($_SERVER['DOCUMENT_ROOT'] . 'global.php');
  $application->api->isLogMode = true;
  
  $application->api->createDataObjectMap('a1');
  $application->api->createDataObjectMap('a2');
  $application->api->createDataObjectMap('a3');
  $application->api->createDataObjectMap('a4');
  $application->api->createDataObjectMap('a5');
  $application->api->renameDataObjectMap('a1', 'b1');
  $application->api->renameDataObjectMap('a2', 'b2');
  $application->api->renameDataObjectMap('a3', 'b3');
  $application->api->renameDataObjectMap('a4', 'b4');
  $application->api->renameDataObjectMap('a5', 'b5');
  $application->api->deleteDataObjectMap('a1');
  $application->api->deleteDataObjectMap('a2');
  $application->api->deleteDataObjectMap('a3');
  $application->api->deleteDataObjectMap('a4');
  $application->api->deleteDataObjectMap('a5');
  $application->api->renameDataObjectMap('b1', 'c1');
  $application->api->renameDataObjectMap('b2', 'c2');
  $application->api->deleteDataObjectMap('b5');
  $application->api->deleteDataObjectMap('b4');
  $application->api->deleteDataObjectMap('b3');
  $application->api->deleteDataObjectMap('c2');
  $application->api->deleteDataObjectMap('c1');
  echo "\n";
    
?>