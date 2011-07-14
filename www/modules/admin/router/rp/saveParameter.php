<?php

  class bmSaveRouteParameter extends bmCustomRemoteProcedure
  {
    public $route = '';
    public $parameterName = '';
    public $parameterType = 0;
    
    private $routes = array();
    
    public function __construct($application, $parameters = array())
    {
      parent::__construct($application, $parameters);
      $this->type = BM_RP_TYPE_JSON;
    }
    
    public function execute()
    {
      if ($this->route != '' && $this->parameterName != '' && $this->parameterType != 0)
      {
        $application->generator->setRouteParameter($this->route, $this->parameterName, $this->parameterType);
      }
    }
  }