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
        
        /*FF::AC::GETTER_CASE::bbb::{*/
        case 'bbbIds':
          if (!array_key_exists('bbbIds', $this->properties))
          {
            $this->properties['bbbIds'] = $this->getBbbs(false);
          }
          return $this->properties['bbbIds'];
        break;
        case 'bbbs':
          return $this->getBbbs();
        break;
        /*FF::AC::GETTER_CASE::bbb::}*/
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
    
    /*FF::AC::REFERENCE_FUNCTIONS::bbb::{*/        
        
    public function getBbbs($load = true)
    {
      $cacheKey = 'image_bbbs_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_a_b`.`bbbId` AS `identifier`
        FROM 
          `link_a_b`
        WHERE 
          `link_a_b`.`aaaId` = " . $this->properties['identifier'] . ";
      ";
      
      if (!$load)
      {
        $this->properties['oldBbbIds'] = $this->getSimpleLinks($sql, $cacheKey, 'author', E_OBJECTS_NOT_FOUND, $load);
        
        return $this->properties['oldBbbIds'];
      }
      else
      {
        return $this->getSimpleLinks($sql, $cacheKey, 'author', E_OBJECTS_NOT_FOUND, $load);
      }
    }

    public function addBbb($bbbId)
    {
      $bbbIds = $this->bbbIds;
      
      if (!in_array($bbbId, $bbbIds))
      {
        $this->properties['bbbIds'][] = $bbbId;
      }
      
      $this->dirty['saveBbbs'] = true;
    }

    public function removeBbb($bbbId)
    {
      $bbbIds = $this->bbbIds;
      
      foreach ($bbbIds as $key => $identifier)
      {
        if ($identifier == $bbbId)
        {
          unset($this->properties['bbbIds'][$key]);
        }
      }
      
      $this->dirty['saveBbbs'] = true;
    }

    public function removeBbbs()
    {
      $this->properties['bbbIds'] = array();
      
      $this->dirty['saveBbbs'] = true;
    }

    protected function saveBbbs()
    {
      $dataLink = $this->application->dataLink;
      $cacheLink = $this->application->cacheLink;
      
      $cacheKeysToDelete = array();
      $cacheKeysToDelete[] = 'image_bbbs_' . $this->properties['identifier']; 
      
      $oldBbbIds = $this->properties['oldBbbIds'];
      $bbbIds = $this->properties['bbbIds'];
      
      $idsToDelete = array_diff($oldBbbIds, $bbbIds);
      $idsToAdd = array_diff($bbbIds, $oldBbbIds);
      
      foreach ($idsToDelete as $idToDelete)
      {
        $cacheKeysToDelete[] = 'bbb_images_' . $idToDelete;
      }
      
      foreach ($cacheKeysToDelete as $cacheKey)
      {
        $cacheLink->delete($cacheKey);
      }
      
      if (count($idsToDelete) > 0)
      {
        $sql = "
          DELETE FROM 
            `link_a_b`
          WHERE 
            `aaaId` = " . $this->properties['identifier'] . "
            AND `bbbId` IN (" . implode(', ', $idsToDelete) . ");";
        
        $dataLink->query($sql);
      }
      
      $insertStrings = array();
      
      foreach ($idsToAdd as $identifier)
      { 
        $insertStrings[] = '(' . $dataLink->formatInput($this->properties['identifier'], BM_VT_INTEGER) . ", " . $dataLink->formatInput($identifier, BM_VT_INTEGER) . ')';
      }
      
      if (count($insertStrings) > 0)
      {
        $sql = "INSERT IGNORE INTO
                  `link_a_b`
                  (aaaId, bbbId)
                VALUES
                  " . implode(', ', $insertStrings) . ";";
                  
        $dataLink->query($sql);
      }
      
      $this->enqueueCache('saveBbbs');
      $this->dirty['saveBbbs'] = false;
      
      $this->properties['oldBbbIds'] = $this->properties['bbbIds'];
    }
    
    /*FF::AC::REFERENCE_FUNCTIONS::bbb::}*/

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
      
      if (!$load)
      {
        $this->properties['oldUserIds'] = $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
        
        return $this->properties['oldUserIds'];
      }
      else
      {
        return $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
      }
    }

    
    
    /*FF::AC::REFERENCE_FUNCTIONS::user::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
    
    /*FF::AC::DELETE_FUNCTION::{*/        
        
    public function delete()
    {
      $this->removeBbbs();

      
      
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