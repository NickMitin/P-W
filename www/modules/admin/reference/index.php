<?php
  
  final class bmReferencePage extends bmHTMLPage
  {
    public $referenceId = 0;
    
    private $fieldTypes = array(BM_VT_INTEGER => 'Целое число', BM_VT_FLOAT => 'Число с плавающей точкой', BM_VT_DATETIME => 'Дата и/или время', BM_VT_STRING => 'Текст', BM_VT_TEXT => 'Длинный текст', BM_VT_PASSWORD => 'Пароль', BM_VT_IMAGE => 'Картинка');
    private $referencedObjectTypes = array(BM_RT_MAIN => 'Главный', BM_RT_REFERRED => 'Зависимый', BM_RT_ADDITIONAL => 'Дополнительный');
    private $referencedObjectTypesCount = array(BM_RT_MAIN => 0, BM_RT_REFERRED => 0, BM_RT_ADDITIONAL => 0);
    
    private function getFieldTypeSelector($fieldType)
    {
      $result = '<select name="dataType[]">';
      foreach($this->fieldTypes as $type => $name)
      {
        $selected = $fieldType == $type ? 'selected="selected"' : '';
        $result .= '<option ' . $selected . ' value="' . $type . '">' . $name . '</option>';
      }
      $result .= '</select>';
      return $result;
    } 
    
    private function getReferencedObjectTypeSelector($referencedObjectType)
    {
      $result = '<select name="referencedObjectType[]">';
      foreach($this->referencedObjectTypes as $type => $name)
      {
        $selected = $referencedObjectType == $type ? 'selected="selected"' : '';
        $result .= '<option ' . $selected . ' value="' . $type . '">' . $name . '</option>';
      }
      $result .= '</select>';
      
      ++$this->referencedObjectTypesCount[$referencedObjectType];  
      
      return $result;
    } 
    
    private function getObjectSelector($dataObjectMapId)
    {
      $dataObjectMaps = $this->application->data->dataObjectMaps;
      
      $result = '<select name="referencedObjectId[]">';
      $result .= '<option value="0"> Не выбран </option>';            
      
      foreach($dataObjectMaps as $dataObjectMap)
      {
        $selected = $dataObjectMap->identifier == $dataObjectMapId ? 'selected="selected"' : '';
        $result .= '<option ' . $selected . ' value="' . $dataObjectMap->identifier . '">' . $dataObjectMap->name . '</option>';  
      }
      $result .= '</select>';
      return $result;
    }
    
    private function getObjectString($dataObjectMapId)
    {
      $dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $dataObjectMapId));
      
      $dataObjectMapName = $dataObjectMap->name;
      
      $result = '<input type="hidden" name="referencedObjectId[]" value="' . $dataObjectMapId . '" />';
      $result .= $dataObjectMapName;
      
      return $result;
    } 
    
    public function generate()
    {
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      $reference = new bmReferenceMap($this->application, array('identifier' => $this->referenceId));
      
      //if ($this->application->errorHandler->getLast() == E_SUCCESS)
      //{
        $objectFieldList = '';
        $propertyFieldList = '';
        
        $fields = $reference->fields;
        
        foreach ($fields as $field)
        {
          $identifier = $field->referenceField->identifier;
          $fieldName = $field->referenceField->fieldName;
          $type = $field->referenceField->dataType;
          $defaultValue = $field->referenceField->defaultValue;
          $propertyName = $field->referenceField->propertyName;
          $referencedObjectId = $field->referenceField->referencedObjectId;
          $localName = $field->referenceField->localNames['nominative'];
          
          if ($field->referenceField->dataType == BM_VT_OBJECT)
          {
            $dataObjectMap = new bmDataObjectMap($this->application, array('identifier' => $referencedObjectId, 'load' => true));
            $referencedObjectName = $dataObjectMap->name;
            $referencedObjectType = $field->type;
            
            $referencedObjectTypeSelector = $this->getReferencedObjectTypeSelector($referencedObjectType);
            eval('$objectFieldList .= "' . $this->application->getTemplate('/admin/reference/referenceObjectField') . '";');
          }
          else
          {
            $fieldType = $this->getFieldTypeSelector($type);
            eval('$propertyFieldList .= "' . $this->application->getTemplate('/admin/reference/referencePropertyField') . '";');
          }
        }
        
        $newObjectFieldList = '';
        $newPropertyFieldList = '';
        
        for ($i = 0; $i < 5; ++$i)
        {
          $newObjectSelector = $this->getObjectSelector(0);
          $newFieldType = $this->getFieldTypeSelector(BM_VT_INTEGER);
          
          if ($this->referencedObjectTypesCount[BM_RT_MAIN] == 0)
          {
            $referencedObjectTypeSelector = $this->getReferencedObjectTypeSelector(BM_RT_MAIN);  
          }
          elseif ($this->referencedObjectTypesCount[BM_RT_REFERRED] == 0)
          {
            $referencedObjectTypeSelector = $this->getReferencedObjectTypeSelector(BM_RT_REFERRED);  
          }
          else
          {
            $referencedObjectTypeSelector = $this->getReferencedObjectTypeSelector(BM_RT_ADDITIONAL);  
          }
          
          
          eval('$newObjectFieldList .= "' . $this->application->getTemplate('/admin/reference/newReferenceObjectField') . '";');
          eval('$newPropertyFieldList .= "' . $this->application->getTemplate('/admin/reference/newReferencePropertyField') . '";');
        }
        
        eval('$output = "' . $this->application->getTemplate('/admin/reference/referenceMap') . '";');
        return $output;
      //}
      
    }
  }
  
?>