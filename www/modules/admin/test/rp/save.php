<?php

%license%

  final class bmSaveTest extends bmCustomRemoteProcedure
  {
    
    public $testId = 0;
    
    public function execute()
    {
      
      /*FF::SAVE::CGIPROPERTIES::{*/
      $testVal = $this->application->cgi->getGPC('val', -100, BM_VT_ANY);
      /*FF::SAVE::CGIPROPERTIES::}*/
      
      $test = new bmTest($this->application, array('identifier' => $this->testId));
      
      /*FF::SAVE::OBJECTPROPERTIES::{*/
      $test->val = $testVal;
      /*FF::SAVE::OBJECTPROPERTIES::}*/
      
      unset($test);
      
      parent::execute();
    }
  }
?>