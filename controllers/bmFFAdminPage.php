<?php

  abstract class bmFFAdminPage extends bmHTMLPage 
  {
    
    public function __construct($application, $parameters = array())
    {                       
      parent::__construct($application, $parameters);
      $this->addScript('ff/jquery');
      $this->addScript('ff/jquery-ui/jquery-ui');
      $this->addScript('ff/swfupload/swfupload');
      $this->addScript('ff/swfupload/swfupload.cookies');
      $this->addScript('ff/swfupload/jquery.swfupload');
      $this->addScript('ff/dataObject/index');
    }
    
    public function generate() 
    {
      
      $this->addCSS('ff/reset');
      $this->addCSS('ff/admin/global');
      $this->addCSS('ff/jquery-ui/jquery-ui');
    
      $this->getHeader();
      $result = parent::generate();
      
      eval('$result .= "' . $this->application->getTemplate('/admin/index').  '";');
      return $result;
      
    }
    
    protected function getHeader() 
    {
      eval('$this->header .= "' . $this->application->getTemplate('/admin/header').  '";');
    }
    
  }

  
?>