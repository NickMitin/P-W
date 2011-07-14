<?php

  class bmSyncronizeDataObjects extends bmCustomRemoteProcedure
  {

    private $exceptions = array('dataObjectMap', 'dataObjectField', 'link_.+');

    private function testException($tableName)
    {
      foreach ($this->exceptions as $execption)
      {
        if (preg_match('/^' . $execption . '$/', $tableName))
        {
          return true;
        }
      }
      return false;
    }

    private function getObjectIdByField($objectName, $fieldName, $value)
    {
      $sql = "SELECT `id` AS `identifier` FROM `" . $objectName . "` WHERE `" . $fieldName . "` = '" . $value . "';";
      $result = $this->application->dataLink->getValue($sql);
      return ($result != null) ? $result : 0;
    }

    public function execute()
    {
      $sql = "SHOW TABLES";
      $qTables = $this->application->dataLink->select($sql);

      while ($table = $qTables->nextRow())
      {
        $dataObjectName = $table[0]; 
        if (!$this->testException($dataObjectName)) 
        {
          $identifier = $this->getObjectIdByField('dataObjectMap', 'name', $dataObjectName);
          $dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $identifier));
          if ($identifier == 0)
          {
            $dataObjectMap->name = $dataObjectName;
            $dataObjectMap->generateFields();
          }
          $dataObjectMap->generateFiles(C_ADMIN_ANCESTOR_PAGE);
          unset($dataObjectField);
        }
        parent::execute();
      }
      
    }
  }

?>
