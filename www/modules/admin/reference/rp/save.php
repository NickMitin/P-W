<?php

  class bmSaveReference extends bmCustomRemoteProcedure
  {
    public $referenceId = 0;
    private $dataFields = array();
    
    public function __construct($application, $parameters = array())
    {
      parent::__construct($application, $parameters); 
      
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      // Собираем все данные из хтмл-формы в единый массив объектов
      
      $allFieldsArray = array('identifier', 'propertyName', 'fieldName', 'referencedObjectId', 'referencedObjectType', 'oldReferencedObjectType', 'dataType', 'defaultValue', 'localName', 'oldFieldName', 'oldDataType', 'oldDefaultValue');
      
      foreach ($allFieldsArray as $field)
      {
        $name = $field . 'Array';
        $$name = $this->application->cgi->getGPC($field, array());
      }
      
      $droppedFieldIds = $this->application->cgi->getGPC('droppedFieldIds', array());
      
      $countArrayName = $allFieldsArray[0] . 'Array';
      $count = count($$countArrayName);
      
      for ($i = 0; $i < $count; ++$i)
      {
        $item = new stdClass();
        
        foreach ($allFieldsArray as $field)
        {
          $fieldArrayName = $field . 'Array';
          $fieldArray = $$fieldArrayName;
          
          $item->$field = $fieldArray[$i];
        }
        
        $this->dataFields[$i] = $item;
      }
      
      // Собрали данные в массив, теперь идем по каждому элементу массиву
      
      $count = count($this->dataFields);
      
      for ($i = 0; $i < $count; ++$i)
      {
        $currentItem = &$this->dataFields[$i]; 
        
        // проверяем правильность введенных данных
        
        if ($currentItem->propertyName == '' || $currentItem->fieldName == '' || $currentItem->dataType == 0) 
        {
          unset($this->dataFields[$i]);
        }
        else
        {
          $stringPattern =  '/^[a-zA-Z][a-zA-Z0-9]+$/';
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
          
          if ($currentItem->dataType == BM_VT_OBJECT && $currentItem->referencedObjectId == 0)
          {
            echo 'Ошибка: не указан связанный объект';
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
        }
        
        // Проверили, теперь смотрим, что и с каким объектом делать  
        
        $currentItem->actions = array();
        
        
        
        if (in_array($currentItem->identifier, $droppedFieldIds))
        {
          $currentItem->actions[] = 'delete';
        }
        elseif ($currentItem->identifier == 0)
        {
          $currentItem->actions[] = 'add';
        }
        elseif ($currentItem->oldFieldName != $currentItem->fieldName || $currentItem->oldDefaultValue != $currentItem->defaultValue)
        {
          $currentItem->actions[] = 'change';
        }
        elseif ($currentItem->oldDataType != $currentItem->dataType)
        {
          $currentItem->actions[] = 'changeType';
        }
      }     
      
      $this->dataFields = array_values($this->dataFields); // Сбрасываем индекс массива
      
      // Проверяем на дубли
      //   и на два главных или два зависимых объекта
      
      $count = count($this->dataFields);
      
      $referencedObjectTypesCount = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
      
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
        
        ++$referencedObjectTypesCount[$this->dataFields[$i]->referencedObjectType];
      }
      
      if ($referencedObjectTypesCount[BM_RT_MAIN] > 1 || $referencedObjectTypesCount[BM_RT_REFERRED] > 1)
      {
        echo 'Ошибка: в связи не может быть двух главных или двух зависимых объектов';
        exit;
      }
    }
    
    public function execute()
    {
      $dataLink = $this->application->dataLink; 
      
      if ($this->referenceId != 0)
      {
        $referenceMap = new bmReferenceMap($this->application, array('identifier' => $this->referenceId));
        
        foreach ($this->dataFields as &$item)
        {
          if (!in_array('delete', $item->actions))
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
            
            $referenceField = new bmReferenceField($this->application, array('identifier' => $item->identifier));
            $referenceField->propertyName = $item->propertyName;
            $referenceField->fieldName = $item->fieldName;
            $referenceField->dataType = $item->dataType;
            $referenceField->defaultValue = $item->defaultValue;
            $referenceField->localName = $item->localName;
            
            if ($item->referencedObjectId != 0)
            {
              $referenceField->setReferencedObject($item->referencedObjectId);
            }
            
            $referenceField->store();
            
            $item->identifier = $referenceField->identifier;
          } 
          
          $referenceMap->beginUpdate();
                 
          foreach ($item->actions as $action)
          {
            switch ($action)
            {
              case 'add':
                $referenceMap->addField($item->identifier, $item->referencedObjectType);
              break;
              case 'delete':
                $referenceMap->removeField($item->identifier);
              break;
              case 'change':
                $referenceMap->renameField($item->identifier, $item->oldFieldName);
              break;
              case 'changeType':
                $referenceMap->changeFieldType($item->identifier, $item->referencedObjectType);
              break;
            }  
          }
          
          
          
          $referenceMap->endUpdate();
        }
      }
      
      $referenceMap->save();      
      // $referenceMap->generateFiles(C_ADMIN_ANCESTOR_PAGE);
      
      // unset($referenceMap);
      parent::execute();      
    }    
  }
?>
