<?php
  
  final class bmDataObjectPage extends bmHTMLPage
  {
    
    public $dataObjectId = 0;
    
    private $fieldTypes = array(BM_VT_INTEGER => 'Целое число', BM_VT_FLOAT => 'Число с плавающей точкой', BM_VT_DATETIME => 'Дата и/или время', BM_VT_STRING => 'Текст', BM_VT_TEXT => 'Длинный текст', BM_VT_PASSWORD => 'Пароль', BM_VT_IMAGE => 'Картинка', BM_VT_FILE => 'Файл');
    
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
    
    public function generate()
    {
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      $dataObject = new bmDataObjectMap($this->application, array('identifier' => $this->dataObjectId));
      
      //$dataObject->fillTable();
      
      if (true)
      {
        $fieldList = '';
        
        $fields = $dataObject->fields;
        
        foreach ($fields as $field)
        {
          $fieldType = $this->getFieldTypeSelector($field->dataType);
          eval('$fieldList .= "' . $this->application->getTemplate('/admin/dataObject/dataField') . '";');
        }
        $newFieldList = '';
        for ($i = 0; $i < 20; ++$i)
        {
          $newFieldType = $this->getFieldTypeSelector(BM_VT_INTEGER);
          eval('$newFieldList .= "' . $this->application->getTemplate('/admin/dataObject/newDataField') . '";');
        }
        
        eval('$output = "' . $this->application->getTemplate('/admin/dataObject/dataObject') . '";');
        return $output;
      }
      
    }
  }
  
?>