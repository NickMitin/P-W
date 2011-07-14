<?php
  class bmAdminProcedure extends bmCustomRemoteProcedure
  {
   
    public function __construct($application, $parameters = array())
    {
      parent::__construct($application, $parameters); 
    }
    
    public function execute()
    {
      $this->type = BM_RP_TYPE_JSON;    
      
      $queryType = $this->application->cgi->getGPC('queryType', '', BM_VT_STRING);
      switch ($queryType)
      {
        case 'initialize':
          $user = $this->application->user; 
          $this->output = $this->initialize($user);
        break;
        case 'login':
          $email = $this->application->cgi->getGPC('email', '', BM_VT_STRING);
          $password = $this->application->cgi->getGPC('password', '', BM_VT_STRING);
          $this->output = $this->login($email, $password);
        break;
        case 'register':
          $email = $this->application->cgi->getGPC('email', '', BM_VT_STRING);
          $password = $this->application->cgi->getGPC('password', '', BM_VT_STRING);
          $this->output = $this->register($email, $password);
        break;
        default:
          $this->output = new stdClass(); 
          $this->output->answer = 'error';
          $this->outpit->errorMessage = 'wrongQueryType';
        break;
      }
      
      return parent::execute();
    }
  
    public function initialize(bmUser $user)
    {
      if ($user->identifier == C_DEFAULT_USER_ID)
      {
        return $this->handleDefaultUser();  
      }
      else
      {
        return $this->handleAuthenticatedUser($user);
      }       
    }
    
    public function handleDefaultUser()
    {
      $result = new stdClass();
      $result->answer = 'defaultUser';
      return $result;
    }
    
    public function handleAuthenticatedUser(bmUser $user)
    {
      $result = new stdClass();
      
      if ($user->type >= 100)
      {
        $result->answer = 'accessGranted';
      }
      else
      {
        $result->answer = 'accessDenied';
        $result->userId = $user->identifier;
      }
      
      return $result;
    } 

    public function login($email, $password)
    {
      $checkUserDataValidityResult = $this->checkUserDataValidity($email, $password);
      
      if ($checkUserDataValidityResult !== true)
      {
        return $checkUserDataValidityResult;
      }
      
      if ($this->application->login($email, $password))
      {
        $user = $this->application->user;
        return $this->handleAuthenticatedUser($user);
      }
      else
      {
        $result = new stdClass();
        $result->answer = 'error';
        $result->errorMessage = 'wrongAuthenticationData';
        return $result;
      }                
    }

    public function register($email, $password)
    {
      $checkUserDataResult = $this->checkUserData($email, $password);
      
      if ($checkUserDataResult !== true)
      {
        return $checkUserDataResult;
      }
      
      $user = new bmUser($this->application, array('email' => $email, 'password' => $password));  
      unset($user);
      return $this->login($email, $password);
    }
    
    public function checkUserData($email, $password)
    {
      $checkUserDataValidityResult = $this->checkUserDataValidity($email, $password);  
      
      if ($checkUserDataValidityResult !== true) 
      {
        return $checkUserDataValidityResult;
      }
      
      return $this->checkUserDuplicate($email, $password);
    }
    
    public function checkUserDataValidity($email, $password)
    {
      $checkEmailResult = $this->checkEmail($email);  
      
      if ($checkEmailResult !== true) 
      {
        return $checkEmailResult;
      }

      return $this->checkPassword($password);
    }
    
    public function checkUserDuplicate($email, $password)
    {
      $existingUserId = $this->application->getObjectIdByFieldName('user', 'email', $email);
      if ($existingUserId != 0)
      {
        $result = new stdClass;
        $result->answer = 'error';
        $result->errorMessage = 'userAlreadyExists';
        return $result; 
      }
      else
      {
        return true;   
      } 
    }
    
    public function checkEmail($email)
    {
      $emailParts = explode('@', $email);
      
      if (count($emailParts) != 2 || !getmxrr($emailParts[1], $mxhosts) || !filter_var($email, FILTER_VALIDATE_EMAIL))
      {
        $result = new stdClass;
        $result->answer = 'error';
        $result->errorMessage = 'invalidEmail';
        return $result;
      }
    
      return true;
    }
    
    public function checkPassword($password)
    {
      if ($password == '')
      {
        $result = new stdClass;
        $result->answer = 'error';
        $result->errorMessage = 'invalidPassword';
        return $result;                         
      }  
      
      return true;
    }
  }
  
?>