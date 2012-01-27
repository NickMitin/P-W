<?php

%license%

  final class bmSaveFile extends bmCustomRemoteProcedure
  {
    
    public $fileId = 0;
    
    public function execute()
    {
      
      /*FF::SAVE::CGIPROPERTIES::{*/
      $fileName = $this->application->cgi->getGPC('name', '', BM_VT_STRING);
      $fileMd5 = $this->application->cgi->getGPC('md5', '', BM_VT_STRING);
      $fileFileName = $this->application->cgi->getGPC('fileName', '', BM_VT_STRING);
      $fileSize = $this->application->cgi->getGPC('size', 0, BM_VT_ANY);
      $fileType = $this->application->cgi->getGPC('type', '', BM_VT_STRING);
      /*FF::SAVE::CGIPROPERTIES::}*/
      
      $file = new bmFile($this->application, array('identifier' => $this->fileId));
      
      /*FF::SAVE::OBJECTPROPERTIES::{*/
      $file->name = $fileName;
      $file->md5 = $fileMd5;
      $file->fileName = $fileFileName;
      $file->size = $fileSize;
      $file->type = $fileType;
      /*FF::SAVE::OBJECTPROPERTIES::}*/
      
      unset($file);
      
      parent::execute();
    }
  }
?>