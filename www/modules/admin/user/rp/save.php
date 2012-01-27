<?php

%license%

  final class bmSaveUser extends bmCustomRemoteProcedure
  {
    
    public $userId = 0;
    
    public function execute()
    {
      
      /*FF::SAVE::CGIPROPERTIES::{*/
      $userEmail = $this->application->cgi->getGPC('email', '0', BM_VT_STRING);
      $userPassword = $this->application->cgi->getGPC('password', '', BM_VT_STRING);
      $userType = $this->application->cgi->getGPC('type', 0, BM_VT_ANY);
      /*FF::SAVE::CGIPROPERTIES::}*/
      
      $user = new bmUser($this->application, array('identifier' => $this->userId));
      
      /*FF::SAVE::OBJECTPROPERTIES::{*/
      $user->email = $userEmail;
      $user->password = $userPassword;
      $user->type = $userType;
      /*FF::SAVE::OBJECTPROPERTIES::}*/
      
      unset($user);
      
      parent::execute();
    }
  }
?>