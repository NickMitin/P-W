<?php
  /*
  * Copyright (c) 2009, "The Blind Mice Studio"
  * All rights reserved.
  * 
  * Redistribution and use in source and binary forms, with or without
  * modification, are permitted provided that the following conditions are met:
  * - Redistributions of source code must retain the above copyright
  *   notice, this list of conditions and the following disclaimer.
  * - Redistributions in binary form must reproduce the above copyright
  *   notice, this list of conditions and the following disclaimer in the
  *   documentation and/or other materials provided with the distribution.
  * - Neither the name of the "The Blind Mice Studio" nor the
  *   names of its contributors may be used to endorse or promote products
  *   derived from this software without specific prior written permission.

  * THIS SOFTWARE IS PROVIDED BY "The Blind Mice Studio" ''AS IS'' AND ANY
  * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  * DISCLAIMED. IN NO EVENT SHALL "The Blind Mice Studio" BE LIABLE FOR ANY
  * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  * 
  */


  final class bmImageEditPage extends 
  {

    public $imageId = 0;


    public function generate()
    {

      if ($this->imageId == 0)
      {

        /*FF::EDITOR::NEWFIELDS::{*/
        $imageName = '';
        $imageMd5 = '';
        $imageFileName = '';
        $imageStatus = 0;
        /*FF::EDITOR::NEWFIELDS::}*/

      }
      else
      {
        $image = new bmImage($this->application, array('identifier' => $this->imageId));
        if ($this->application->errorHandler->getLast() != E_SUCCESS)
        {
          //TODO Error;
          exit;
        }

        /*FF::EDITOR::EXISTINGFIELDS::{*/
        $imageName = htmlspecialchars($image->name);
        $imageMd5 = htmlspecialchars($image->md5);
        $imageFileName = htmlspecialchars($image->fileName);
        $imageStatus = $image->status;
        /*FF::EDITOR::EXISTINGFIELDS::}*/

      }
      eval('$this->content = "' . $this->application->getTemplate('/admin/image/image') . '";');
      $page = parent::generate();
      return $page;
    }
  }
?>