<?php
  final class bmApplication extends bmCustomApplication
  {
  public function __construct($application, $parameters = array())
    {      
      parent::__construct($application, $parameters);

      $this->session = new bmSession($this, array());
      $this->user->lastactivity = time();
    }
  }
  
?>
