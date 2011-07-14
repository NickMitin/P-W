<?php

%license%

  final class bmSave%upperCaseObjectName% extends bmCustomRemoteProcedure
  {
    
    public $%objectName%Id = 0;
    
    public function execute()
    {
      
      %cgiProperties%
      
      $%objectName% = new bm%upperCaseObjectName%($this->application, array('identifier' => $this->%objectName%Id));
      
      %objectProperties%
      
      unset($%objectName%);
      
      parent::execute();
    }
  }
?>