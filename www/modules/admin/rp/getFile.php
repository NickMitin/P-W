<?php

  final class bmGetFile extends bmCustomRemoteProcedure
  {
    
    private function prepareCommand($command)
    {
      return ' -geometry ' . $command . 'x' . $command; 
      $arguments = explode($command);
      $result = '';
      if (count($arguments) == 1)
      {
        if ($dimension[0] == 'w')
        {
          $dimension  = mb_substr($dimension, 1);  
        }
        $dimension = intval($arguments[0]);
        if ($dimension > 0)
        {
          $result .= ' -resize ' . $dimension . 'x' . $dimension;
        }
      }
      else
      {
        
        $resize = '';
        $widht = 0;
        $height = 0;
        $gavity = '';
        foreach($arguments as $argumentString)
        {
          if (preg_match('/^(\w)(!?)(.+)$/', $argumentString, $argument))
          {
            
            switch ($argument[1])
            {
              case 'w':
                $argument[3] = intval($argument[3]);
                if ($argument[3] != 0)
                {
                  if ($argument[2] == '!')
                  {
                    $resize = ' -resize ' . $argument[3];
                  }
                }
              break;
            }
          }
        }
      }
    }
    
    //convert test.jpg -resize 60 -gravity Center -crop 60x40+0+0 +repage result.jpg

    
    public function execute()
    {
      $this->type = BM_RP_TYPE_JSON; 
      
      if ($this->application->user->identifer != C_DEFAULT_USER_ID)
      {
        if (array_key_exists('Filedata', $_FILES))
        {
          $type = $this->application->cgi->getGPC('type', 'files');
          $objectName = trim($this->application->cgi->getGPC('objectName', ''));
          $propertyName = trim($this->application->cgi->getGPC('propertyName', ''));

          $fileData = $_FILES['Filedata'];
          
          if ($fileData['error'] == 0)
          {
            
            
            $md5 = md5($objectName . $propertyName . md5_file($fileData['tmp_name']));
            $fileId = $this->application->getObjectIdByFieldName('photo', 'md5', $md5);
            $file = new bmPhoto($this->application, array('identifier' => $fileId));
            if ($fileId > 0)
            {
              $fileName = $file->fileName;
            }
            else
            {
              $fileName = md5(uniqid('', true));
              $originalsDirectoryName = documentRoot . '/' . $type . '/' . $objectName . '/' . $propertyName . '/originals/' . mb_substr($fileName, 0, 2) . '/';
              
              if (!file_exists($originalsDirectoryName))
              {
                mkdir($originalsDirectoryName, 0777, true);
              }
              move_uploaded_file($fileData['tmp_name'], $originalsDirectoryName . $fileName);
              
              if ($type == 'images')
              {
                $dimensions = trim($this->application->cgi->getGPC('dimensions', ''));
                $dimensions = preg_split('/\s*,\s*/', $dimensions, -1, PREG_SPLIT_NO_EMPTY);
                array_push($dimensions, '100');
                $dimensions = array_unique($dimensions);
                foreach ($dimensions as $dimension)
                {
                  $directoryName = documentRoot . '/' . $type . '/' . $objectName . '/' . $propertyName . '/' . $dimension . '/' . mb_substr($fileName, 0, 2) . '/';
                  
                  if (!file_exists($directoryName))
                  {
                    mkdir($directoryName, 0777, true);
                  }
                  $command = $this->prepareCommand($dimension);
                  shell_exec('convert ' . $command . ' "' . $originalsDirectoryName . '/' . $fileName . '" "' . $directoryName . '/' . $fileName . '"'); 
                  
                }
                $metaData = new stdClass();
                $metaData->dimensions = $dimensions;
                $file->metadata = serialize($metaData);
              }
              $file->name = $fileData['name'];
              $file->fileName = $fileName;
              $file->md5 = $md5;
            }
            
            $this->output = new stdClass();
            $this->output->fileName = $fileName;
            $this->output->data = $this->application->cgi->getGPC('data', '');
          }
        }
      }
      return parent::execute();
    }
  }

?>