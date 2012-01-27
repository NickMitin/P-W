<?php

%license%

  final class bmSaveImage extends bmCustomRemoteProcedure
  {
    
    public $imageId = 0;
    
    public function execute()
    {
      
      /*FF::SAVE::CGIPROPERTIES::{*/
      $imageName = $this->application->cgi->getGPC('name', '', BM_VT_STRING);
      $imageMd5 = $this->application->cgi->getGPC('md5', '', BM_VT_STRING);
      $imageFileName = $this->application->cgi->getGPC('fileName', '', BM_VT_STRING);
      $imageStatus = $this->application->cgi->getGPC('status', 0, BM_VT_ANY);
      /*FF::SAVE::CGIPROPERTIES::}*/
      
      $image = new bmImage($this->application, array('identifier' => $this->imageId));
      
      /*FF::SAVE::OBJECTPROPERTIES::{*/
      $image->name = $imageName;
      $image->md5 = $imageMd5;
      $image->fileName = $imageFileName;
      $image->status = $imageStatus;
      /*FF::SAVE::OBJECTPROPERTIES::}*/
      
      unset($image);
      
      parent::execute();
    }
  }
?>