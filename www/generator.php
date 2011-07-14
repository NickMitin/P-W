<?php
  if ($argc == 3) {
    $_SERVER['DOCUMENT_ROOT'] = $argv[1];
    $_SERVER['REQUEST_URI'] = $argv[2];
  }
  
  require_once($_SERVER['DOCUMENT_ROOT'] . '/global.php');
  
  print $application->generator->generate($_SERVER['REQUEST_URI']);
  
?>
