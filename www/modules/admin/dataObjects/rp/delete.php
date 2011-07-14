<?php
  class bmDeleteDataObject extends bmCustomRemoteProcedure
  {
    public $dataObjectMapId = 0;
    
    public function execute()
    {
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      if ($this->dataObjectMapId != 0)
      {
        $dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $this->dataObjectMapId));
        
        $dataObjectMap->delete();
      }
      unset($dataObjectMap);
      parent::execute();
    }
    
  }
?>
