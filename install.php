#!/usr/bin/php
<?php

class bmInstaller
{

  private $settings = array();

  private function processSetting($key, $value)
  {
    $this->settings[$key] = $value;
  }

  public function execute()
  {
    global $argv;
    $php = shell_exec('php -v');

    if (!preg_match('/PHP 5\.[23]/', $php))
    {
      print "PHP 5.2.x or later is required to use P@W.\n";
      exit;
    }

    if (!function_exists('mysql_connect'))
    {
      print "php5-mysql extension is required to use P@W.\n";
      exit;
    }

    $arguments = array_slice($argv, 1);
    $argumentCount = count($arguments);
    for ($i = 0; $i < $argumentCount; ++$i)
    {
      if ($i + 1 < $argumentCount)
      {
        $this->processSetting($arguments[$i], $arguments[$i + 1]);
        $i++;
      }
    }
    
    $user = shell_exec('id ' . $this->settings['-u']);

    if (mb_stripos($user, 'No such user') !== false)
    {
      print "User '" . $this->settings['-u'] . "' not found. Would you like to create one? [Y/n]: ";
      $fp = fopen('php://stdin', 'r');
      $answer = fgetc($fp);
      fclose($fp);
      if (ucfirst($answer == 'Y'))
      {
        $password = trim(shell_exec('pwgen -s -1'));
        $passwordHash = trim(shell_exec('mkpasswd 1234567'));
        $isPassword = ($password != '' && mb_stripos($password, 'not found') === false);
        $isPasswordHash = ($passwordHash != '' && mb_stripos($passwordHash, 'not found') === false);
        if (!$isPassword)
        {
          
          if (!$isPasswordHash)
          {
            print "Installer cannot generate password for '" . $this->settings['-u'] . "'. You should use passwd " . $this->settings['-p'] . " to set it manually.\n";
          }
          else
          {
            print "Installer cannot generate password for '" . $this->settings['-u'] . "'. Please provide it now: ";
            $fp = fopen('php://stdin', 'r');
            $password = trim(fgets($fp));
            fclose($fp);
            $passwordHash = trim(shell_exec('mkpasswd ' . escapeshellarg($password)));
          }
        }
      }
    }
    
    
      
      #useradd -m -d "$HOME_DIRECTORY$1" -p "$PROJECT_PASSWORD_HASH" "$1" -s /bin/bash
      #QUERY="CREATE DATABASE \`$1\`; USE $1; GRANT ALL ON \`$1\`.* TO '$1'@'localhost' IDENTIFIED BY '$PROJECT_PASSWORD';"
      #mysql -sse "$QUERY"
      #mkdir "$HOME_DIRECTORY$1/logs/"
      #mkdir "$HOME_DIRECTORY$1/www/"
      #mkdir "$HOME_DIRECTORY$1/conf/"
      #chown -vfR $1:$1 "$HOME_DIRECTORY$1"

      #echo "user: $PROJECT_NAME\n"
      #echo "pass: $PROJECT_PASSWORD\n"
    
    
  }
}

$installer = new bmInstaller();
$installer->execute();

#!/bin/sh

#useradd -d /mymedia/projects/$1 -s /bin/bash -U -m $1
#mkdir /mymedia/projects/$1/conf/
#mkdir /mymedia/projects/$1/www/
#mkdir /mymedia/projects/$1/logs/
#mkdir /mymedia/projects/$1/lib/
#mkdir /mymedia/projects/$1/templates/
#mkdir /mymedia/projects/$1/controllers/
#mkdir /mymedia/projects/$1/locale/
#mkdir /mymedia/projects/$1/www/modules/
#mkdir -p /mymedia/projects/$1/templates/admin/
#mkdir -p /mymedia/projects/$1/www/modules/admin/
#mkdir -p /mymedia/projects/$1/www/scripts/ff/
#mkdir -p /mymedia/projects/$1/www/styles/ff/
#mkdir -p /mymedia/projects/$1/www/images/ff/

#chown -vfR $1:$1 /mymedia/projects/$1
#mount -o bind /mymedia/ff/ /mymedia/projects/$1/lib
#mysql -sse "CREATE DATABASE $1;"
#mysql -sse "GRANT ALL ON \`$1\`.* TO '$1'@'%' IDENTIFIED BY '$2';"
#mysql -sse "GRANT ALL ON \`$1\`.* TO '$1'@'localhost' IDENTIFIED BY '$2';"
#mysql -sse "USE $1; CREATE TABLE IF NOT EXISTS \`user\` ( \`id\` int(10) unsigned NOT NULL AUTO_INCREMENT, \`password\` varchar(32) CHARACTER SET utf8 NOT NULL, \`status\` varchar(32) CHARACTER SET utf8 NOT NULL, \`email\` varchar(255) CHARACTER SET utf8 NOT NULL, \`homePage\` varchar(255) CHARACTER SET utf8 NOT NULL, \`avatar\` varchar(32) CHARACTER SET utf8 DEFAULT NULL, \`name\` varchar(255) CHARACTER SET utf8 NOT NULL, \`firstname\` varchar(100) CHARACTER SET utf8 DEFAULT NULL, \`lastname\` varchar(100) CHARACTER SET utf8 DEFAULT NULL, \`birthday\` date DEFAULT NULL, \`sex\` int(10) unsigned NOT NULL, \`joinDate\` datetime NOT NULL, \`offset\` float NOT NULL DEFAULT '3', \`type\` int(10) unsigned NOT NULL DEFAULT '0', \`lastActivity\` datetime NOT NULL, \`lastVisit\` datetime NOT NULL, \`timeOnline\` int(10) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (\`id\`), KEY \`password\` (\`password\`), KEY \`type\` (\`type\`), KEY \`email\` (\`email\`), KEY \`lastActivity\` (\`lastActivity\`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2;"
#mysql -sse "USE $1; INSERT IGNORE INTO \`user\` (\`id\`, \`password\`, \`status\`, \`email\`, \`homePage\`, \`avatar\`, \`name\`, \`firstname\`, \`lastname\`, \`birthday\`, \`sex\`, \`joinDate\`, \`offset\`, \`type\`, \`lastActivity\`, \`lastVisit\`, \`timeOnline\`) VALUES (1, '', '', 'guest@$1', '', '', 'Гость', '', '', '0000-00-00', 0, '0000-00-00 00:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0);"
#mysql -sse "USE $1; CREATE TABLE IF NOT EXISTS \`session\` (\`id\` varchar(32) CHARACTER SET utf8 NOT NULL, \`ipAddress\` varchar(15) CHARACTER SET utf8 DEFAULT NULL, \`userId\` varchar(50) CHARACTER SET utf8 NOT NULL, \`userKey\` int(10) unsigned NOT NULL DEFAULT '0', \`userAgent\` varchar(255) CHARACTER SET utf8 DEFAULT NULL, \`location\` varchar(255) CHARACTER SET utf8 NOT NULL, \`createTime\` int(10) unsigned DEFAULT NULL, \`lastActivity\` int(10) unsigned NOT NULL DEFAULT '0', \`lastVisit\` int(10) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (\`id\`), KEY \`ipAddress\` (\`ipAddress\`), KEY \`lastactivity\` (\`lastActivity\`), KEY \`userid\` (\`userId\`), KEY \`userKey\` (\`userKey\`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;"
#mysql -sse "USE $1; CREATE TABLE IF NOT EXISTS \`link_user_session\` (\`userId\` int(10) NOT NULL, \`sessionHash\` varchar(32) CHARACTER SET utf8 NOT NULL, \`ipAddress\` varchar(15) CHARACTER SET utf8 NOT NULL, PRIMARY KEY (\`userId\`,\`sessionHash\`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;"
#mysql $1 < /mymedia/projects/ff/sql.sql
#sh /mymedia/ctl/update_ff_admin.sh $1

?>
