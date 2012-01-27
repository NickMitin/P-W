<?php

  final class bm404Page extends bmHTMLPage
  {
    public function __construct($application, $parameters = array())
    {
      parent::__construct($application, $parameters);
      $this->title = '404';
    }

    public function generate()
    {
      $result = parent::generate();
      
      eval('$result .= "' . $this->application->getTemplate('/errors/404').  '";');
      
      return $result;
    }
  }  
  
?>