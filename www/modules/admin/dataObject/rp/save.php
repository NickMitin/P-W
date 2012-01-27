<?php

  class bmSaveDataObject extends bmCustomRemoteProcedure
  {
    public $dataObjectId = 0;
    
    private $dataFields = array();
    
    public function __construct($application, $parameters = array())
    {
      parent::__construct($application, $parameters); 
      
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      $propertyNameArray = $this->application->cgi->getGPC('propertyName', array());
      
      $droppedFieldIds = $this->application->cgi->getGPC('droppedFieldIds', array());
      
      if (count($propertyNameArray) > 0) {
        foreach($propertyNameArray as $key => $value)
        {
          $this->dataFields[$key] = new stdClass();
          $value = $this->application->validateValue($value, BM_VT_STRING);
          $this->dataFields[$key]->propertyName = $value;
        }
        
        $this->setValues('dataFields', 'identifier', BM_VT_INTEGER, 0);
        
        $this->setValues('dataFields', 'oldFieldName', BM_VT_STRING, '');
        $this->setValues('dataFields', 'oldDataType', BM_VT_INTEGER, 0);
        $this->setValues('dataFields', 'oldDefaultValue', BM_VT_STRING, '');
        
        $this->setValues('dataFields', 'fieldName', BM_VT_STRING, '');
        $this->setValues('dataFields', 'dataType', BM_VT_INTEGER, 0);
        $this->setValues('dataFields', 'defaultValue', BM_VT_STRING, '');
        $this->setValues('dataFields', 'localName', BM_VT_STRING, '');
      }
      
      $i = 0;
      $counter = 0;
      
      $count = count($this->dataFields);
      
      for ($i = 0; $i < $count; ++$i)
      {
        $currentItem = &$this->dataFields[$i];
        
        if ($currentItem->propertyName == '' || $currentItem->fieldName == '' || $currentItem->dataType == 0) 
        {
          unset($this->dataFields[$i]);
        }
        else
        {
          $stringPattern =  '/^[a-zA-Z]+[a-zA-Z0-9]*$/';
          $localNamePattern =  '/[\'\"<>]/';
        
          if (!preg_match($stringPattern, $currentItem->propertyName))
          {
            echo 'Ошибка: имя свойства может состоять из строчных и прописных латинских букв и цифр и должно начинаться с буквы';
            exit;
          }
          
          if (!preg_match($stringPattern, $currentItem->fieldName))
          {
            echo 'Ошибка: имя поля может состоять из строчных и прописных латинских букв и цифр и должно начинаться с буквы';
            exit;
          }
          
          if (preg_match($localNamePattern, $currentItem->localName))
          {
            echo 'Ошибка: в названии для пользователя содержатся недопустимые символы';
            exit;
          }
          
          if ($currentItem->dataType == BM_VT_INTEGER || $currentItem->dataType == BM_VT_DATETIME)
          {
            $currentItem->defaultValue = intval($currentItem->defaultValue);
          }
          
          if ($currentItem->dataType == BM_VT_FLOAT)
          {
            $currentItem->defaultValue = floatval($currentItem->defaultValue);
          }
          
          $currentItem->action = 'none';
          
          if (in_array($currentItem->identifier, $droppedFieldIds))
          {
            $currentItem->action = 'delete';
          }
          elseif ($currentItem->identifier == 0)
          {
            $currentItem->action = 'add';
          }
          elseif ($currentItem->oldFieldName != $currentItem->fieldName || $currentItem->oldDataType != $currentItem->dataType || $currentItem->oldDefaultValue != $currentItem->defaultValue)
          {
            $currentItem->action = 'change';
          }
        }
      }
      
      $count = count($this->dataFields);
      
      for ($i = 0; $i < $count; ++$i)
      {
        for ($j = 0; $j < $count; ++$j)
        {
          if ($i != $j)
          {
            if ($this->dataFields[$i]->propertyName == $this->dataFields[$j]->propertyName || $this->dataFields[$i]->fieldName == $this->dataFields[$j]->fieldName)
            {
              echo 'Ошибка: не должно быть дублей имен свойств и полей.';
              exit;
            }
          }
        }
      }
    }
    
    private function setValues($collection, $propertyName, $dataType, $defaultValue)
    {
      $values = $this->application->cgi->getGPC($propertyName, array());
      
      if (count($values) > 0) {
        foreach($values as $key => $value)
        {
          $value = $this->application->validateValue($value, $dataType);
          $this->{$collection}[$key]->$propertyName = $value;
        }
      }
    }
    
    public function execute()
    {
      $dataLink = $this->application->dataLink; 
      
      if ($this->dataObjectId != 0)
      {
        $dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $this->dataObjectId));
        
        foreach ($this->dataFields as &$item)
        {
          if ($item->action != 'delete')
          {
            $inflectionNames = array('nominative', 'genitive', 'dative', 'accusive', 'creative', 'prepositional');
            if ($item->localName != '')
            {
              $inflections = file_get_contents('http://export.yandex.ru/inflect.xml?name=' . urlencode($item->localName));
              $xml = simplexml_load_string($inflections);
              $inflections = array();
              if (count($xml->inflection) == 6)
              {
                $i = 0;
                foreach ($xml->inflection as $inflection)
                {
                  $inflections[$inflectionNames[$i]] = (string)$inflection;
                  $i++;
                }
              }
              else
              {
                foreach ($inflectionNames as $i => $inflection)
                {
                  $inflections[$inflectionNames[$i]] = (string)$xml->inflection;
                }
              }
            }
            else
            {
              $inflections = array();
              foreach ($inflectionNames as $i => $inflection)
              {
                $inflections[$inflectionNames[$i]] = $item->propertyName;
              }
            }
            $item->localName = serialize($inflections);
              
            if ($item->dataType == BM_VT_DATETIME)
            {
              if (!preg_match('/^\d{4}-\d{2}-\d{2}\s+\d{2}:\d{2}:\d{2}$/', $item->defaultValue))
              {
                $item->defaultValue = '0000-01-01 00:00:00';
              }
            }
            
            $dataField = new bmDataObjectField($this->application, array('identifier' => $item->identifier));
            $dataField->propertyName = $item->propertyName;
            $dataField->fieldName = $item->fieldName;
            $dataField->dataType = $item->dataType;
            $dataField->defaultValue = $item->defaultValue;
            $dataField->localName = $item->localName;
            $dataField->store();
            
            $item->identifier = $dataField->identifier;
          } 
          
          $dataObjectMap->beginUpdate();
                 
          switch ($item->action)
          {
            case 'add':
              $dataObjectMap->addField($item->identifier, $item->dataType);
            break;
            case 'delete':
              $dataObjectMap->removeField($item->identifier);
            break;
            case 'change':
              $dataObjectMap->renameField($item->identifier, $item->oldFieldName);
            break;
          }
          
          $dataObjectMap->endUpdate();
        }
      }
      
      $dataObjectMap->save();      
      $dataObjectMap->generateFiles();
      
      unset($dataObjectMap);
      
      parent::execute();
    }
    
  }
?>
