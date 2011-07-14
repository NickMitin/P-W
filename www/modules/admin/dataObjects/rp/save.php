<?php

  class bmSaveDataObjects extends bmCustomRemoteProcedure
  {
    
    private $dataObjectNames = array();
    
    public function __construct($application, $parameters = array())
    {
      parent::__construct($application, $parameters); 
      
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      $this->dataObjectNames = $this->application->cgi->getGPC('dataObjectName', array());
    }
    
    public function execute()
    {
      foreach ($this->dataObjectNames as $dataObjectMapId => $dataObjectName)
      {
        if ($dataObjectMapId != 0 || $dataObjectName != '')
        {
          $dataObjectName = trim($dataObjectName);
          
          $pattern =  '/^[a-zA-Z][a-zA-Z0-9]+$/';
          if (preg_match($pattern, $dataObjectName))
          {
            $dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $dataObjectMapId));
            $dataObjectMap->beginUpdate();
            
            if ($dataObjectMap->type != 1)
            {
              $dataObjectMap->name = $dataObjectName;  
            } 
            
            $dataObjectMap->endUpdate();
            $dataObjectMap->save();      
            // $dataObjectMap->generateFiles(C_ADMIN_ANCESTOR_PAGE);
            unset($dataObjectMap);                           
          }
          else
          {
            echo 'Ошибка: имя объекта может состоять из строчных и прописных латинских букв и цифр и должно начинаться с буквы';
          } 
        }
      }
      
      parent::execute();
    }
    
  }
?>
