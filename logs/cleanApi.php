#!/usr/bin/php  
<?php
         
  error_reporting(0);
  ini_set(display_errors, 0);
    
  $_SERVER['DOCUMENT_ROOT'] = '/mymedia/projects/ff/www/';
  $_SERVER['REQUEST_URI'] = '';
  require_once($_SERVER['DOCUMENT_ROOT'] . 'global.php');
  $application->api->isLogMode = true;
  
  echo "\n";
    
?>