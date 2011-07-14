<?php
  
  final class bmRouterPage extends bmHTMLPage
  {
    private $routes = array();
    
    private $fieldTypes = array(BM_VT_INTEGER => 'Целое число', BM_VT_FLOAT => 'Число с плавающей точкой', BM_VT_DATETIME => 'Дата и/или время', BM_VT_STRING => 'Текст', BM_VT_TEXT => 'Длинный текст', BM_VT_PASSWORD => 'Пароль', BM_VT_IMAGE => 'Картинка', BM_VT_FILE => 'Файл');
    
    private function getFieldTypeSelector($fieldType, $key)
    {
      $result = '<select name="routeParameterType[' . $key . ']">';
      foreach($this->fieldTypes as $type => $name)
      {
        $selected = $fieldType == $type ? 'selected="selected"' : '';
        $result .= '<option ' . $selected . ' value="' . $type . '">' . $name . '</option>';
      }
      $result .= '</select>';
      return $result;
    } 
    
    function generate()
    {
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      $this->addScript('/admin/router/index');
      
      require(projectRoot . '/conf/generator.conf');
      $routeList = '';
      
      ksort($this->routes);
      $i = 0;
      foreach($this->routes as $route => $routeData)
      {
        $route = htmlentities($route);
        $parameters = '';
        if (array_key_exists('parameters', $routeData))
        {
          
          foreach($routeData['parameters'] as $name => $type)
          {
            $routeParameterType = $this->getFieldTypeSelector($type, $i . '][');
            eval('$parameters .= "' . $this->application->getTemplate('/admin/router/parameter') . '";');  
          }
          
        }
        eval('$parametersList = "' . $this->application->getTemplate('/admin/router/parameterList') . '";');
        eval('$routeList .= "' . $this->application->getTemplate('/admin/router/route') . '";');
        $i++;  
      }
      /*$output = '';
      $dataObjectMaps = $this->application->data->dataObjectMaps;
      $objectList = '';
      foreach ($dataObjectMaps as $dataObjectMap)
      {
        $readonly = $dataObjectMap->type == 1 ? 'readonly="readonly"' : '';
        $delete = $dataObjectMap->type == 1 ? '' : $this->application->getTemplate('/admin/dataObjects/delete');
	      eval('$delete  = "' . $delete . '";');
        eval('$objectList .= "' . $this->application->getTemplate('/admin/dataObjects/dataObject') . '";');  
        //$dataObjectMap->generateFields();
      }
      eval('$newDataObjectMap = "' . $this->application->getTemplate('/admin/dataObjects/newDataObject') . '";');
      */
      eval('$output = "' . $this->application->getTemplate('/admin/router/router') . '";'); 
      return $output;
      
    }
  }
  
?>