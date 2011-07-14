<?php

ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

function pawAutoload($className)
{
  if (file_exists(projectRoot . '/controllers/' . $className . '.php'))
  {
    require_once(projectRoot . '/controllers/' . $className . '.php');
  } 
  else if (file_exists(projectRoot . '/lib/' . $className . '.php'))
  {
    require_once(projectRoot . '/lib/' . $className . '.php');
  }
}

spl_autoload_register('pawAutoload', true);  

mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

if (!isset($_SERVER['HTTP_USER_AGENT']))
{
  $_SERVER['HTTP_USER_AGENT'] = 'N/A';
}
                                    
header('cache-control: no-cache', true);
header('content-type: text/html; charset=utf-8', true);

require($_SERVER['DOCUMENT_ROOT'] . '/../conf/application.conf');

$application = new bmApplication(null);

?>