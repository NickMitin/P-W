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

  final class bmImage extends bmDataObject
  {
    public function __construct($application, $parameters = array())
    {
      /*FF::AC::MAPPING::{*/

      $this->objectName = 'image';
      $this->map = array_merge($this->map, array(
        'abc' => array(
          'fieldName' => 'abc',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'md5' => array(
          'fieldName' => 'md5',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'fileName' => array(
          'fieldName' => 'fileName',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'status' => array(
          'fieldName' => 'status',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        ),
        'bbb1' => array(
          'fieldName' => 'bbb1',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        )
      ));

      /*FF::AC::MAPPING::}*/

      parent::__construct($application, $parameters);
    }

    public function __get($propertyName)
    {
      $this->checkDirty();
      
      switch ($propertyName)
      {
        /*FF::AC::TOP::GETTER::{*/
        
        /*FF::AC::GETTER_CASE::user::{*/
        case 'userIds':
          if (!array_key_exists('userIds', $this->properties))
          {
            $this->properties['userIds'] = $this->getUsers(false);
          }
          return $this->properties['userIds'];
        break;
        case 'users':
          return $this->getUsers();
        break;
        /*FF::AC::GETTER_CASE::user::}*/
 
        /*FF::AC::TOP::GETTER::}*/
        default:
          return parent::__get($propertyName);
        break;
      }
    }
    
    /*FF::AC::TOP::REFERENCE_FUNCTIONS::{*/
    
    /*FF::AC::REFERENCE_FUNCTIONS::user::{*/        
        
    public function getUsers($load = true)
    {
      $cacheKey = 'image_users_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_user_image`.`userId` AS `userId`,
          `link_user_image`.`imageId` AS `imageId`,
          `link_user_image`.`ab` AS `ab`
        FROM 
          `link_user_image`
        WHERE 
          `link_user_image`.`imageId` = " . $this->properties['identifier'] . ";
      ";
      
      $map = array('user IS user' => 5, 'image IS image' => 5, 'ab' => 2);
      
      // Problem here: what if $load = true? :-)
      
      $this->properties['oldUserIds'] = $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
      
      return $this->properties['oldUserIds'];
    }

    
    
    /*FF::AC::REFERENCE_FUNCTIONS::user::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
    
    /*FF::AC::DELETE_FUNCTION::{*/        
        
    public function delete()
    {
      
      
      $users = $this->users;

      foreach ($users as $item)
      {
        $item->user->removeImage($this->properties['identifier']);
      }

      
      
      $this->application->cacheLink->delete($this->objectName . $this->properties['identifier']); 
      
      $sql = "DELETE FROM 
                `image` 
              WHERE 
                `id` = " . $this->properties['identifier'] . ";
              ";
      
      $this->application->dataLink->query($sql);
    }
    
    /*FF::AC::DELETE_FUNCTION::}*/
  }
  
?>