<?php

  $documentRoot = $_SERVER['DOCUMENT_ROOT'];
  $serverRoot = preg_replace('/www\/?$/', '', $documentRoot);
  
  $cachers = '<option value="bmPHPSession">PHP Session</option>';
  if (function_exists('xcache_isset'))
  {
    $cachers .= '<option value="bmXCache">XCache</option>';
  }
  $template = addcslashes(trim(file_get_contents('main.html')), '"');
  eval('$result = "' . $template . '";');
  
  echo $result;

?>