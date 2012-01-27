<?php
  $query = $_GET['query'];
  
  if (preg_match('/query=(.+)$/i', $query, $matches))
  {
    $query = urldecode($matches[1]);
    file_put_contents('data.txt', date('Y.m.d h:i:s', time()) . ' кто-то искал "' . $query . "\"\n", FILE_APPEND);
  }

?>