<?php

  $cron = shell_exec('crontab -l -u ff');
  
  $rules = preg_split('/\s*\r?\n\s*/', $cron, -1, PREG_SPLIT_NO_EMPTY);
  
  var_dump($rules);
  
  foreach ($rules as $rule)
  {
    if (preg_match('//'))
    {
    }
    else if (preg_match('/^\s*(\*|\d+)\s+(\*|\d+)\s+(\*|\d+)\s+(\*|\d+)$/'))
    {
    }
  }

?>