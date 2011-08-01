<?php

  $hostname = $_POST['hostname'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $database = $_POST['database'];
  
  $serverRoot = $_POST['serverRoot'];
  
  $adminEmail = $_POST['adminEmail'];
  $adminPassword = $_POST['adminPassword'];
  
  $dbDefault = addcslashes(trim(file_get_contents('db_default.conf')), '"');
  eval('$dbConfig = "' . $dbDefault . '";');
  
  $dbFile = fopen($serverRoot . '/conf/db_default.conf', 'w');
  fwrite($dbFile, $dbConfig);
  
  $conf = file_get_contents($serverRoot . '/conf/application.conf');
  
  $conf = preg_replace(
    '/define\(\'C_SESSION_COOKIE_DOMAIN\', \'.*?\'\);/ism', 
    "define('C_SESSION_COOKIE_DOMAIN', '." . $_SERVER['SERVER_NAME'] . "');",
    $conf
  );
  
  file_put_contents($serverRoot . '/conf/application.conf', $conf);
  
  shell_exec("mysql -u" . $username . " -p" . $password . ' ' . $database . ' < ' . $serverRoot . 'sql.sql');  
  
  $mysqlLink = mysql_connect($hostname, $username, $password);
  mysql_select_db($database);
  mysql_query("INSERT INTO `user` SET `email` = '" . mysql_real_escape_string($adminEmail) . "', `password` = '" . md5($adminPassword) . "', `type` = 100;", $mysqlLink);
  
  header('Location: /admin/');
  
?>