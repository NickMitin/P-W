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

  final class bmUser extends bmDataObject
  {
    public function __construct($application, $parameters = array())
    {
      /*FF::AC::MAPPING::{*/

      $this->objectName = 'user';
      $this->map = array_merge($this->map, array(
        'email' => array(
          'fieldName' => 'email',
          'dataType' => BM_VT_STRING,
          'defaultValue' => '0'
        ),
        'password' => array(
          'fieldName' => 'password',
          'dataType' => BM_VT_PASSWORD,
          'defaultValue' => '0'
        ),
        'type' => array(
          'fieldName' => 'type',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        ),
        'status' => array(
          'fieldName' => 'status',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        ),
        'a' => array(
          'fieldName' => 'a',
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
        
        /*FF::AC::GETTER_CASE::image::{*/
        case 'imageIds':
          if (!array_key_exists('imageIds', $this->properties))
          {
            $this->properties['imageIds'] = $this->getImages(false);
          }
          return $this->properties['imageIds'];
        break;
        case 'images':
          return $this->getImages();
        break;
        /*FF::AC::GETTER_CASE::image::}*/
 
        /*FF::AC::TOP::GETTER::}*/
        default:
          return parent::__get($propertyName);
        break;
      }
    }
    
    /*FF::AC::TOP::REFERENCE_FUNCTIONS::{*/
    
    /*FF::AC::REFERENCE_FUNCTIONS::image::{*/        
        
    public function getImages($load = true)
    {
      $cacheKey = 'user_images_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_user_image`.`userId` AS `userId`,
          `link_user_image`.`imageId` AS `imageId`,
          `link_user_image`.`ab` AS `ab`
        FROM 
          `link_user_image`
        WHERE 
          `link_user_image`.`userId` = " . $this->properties['identifier'] . ";
      ";
      
      $map = array('user IS user' => 5, 'image IS image' => 5, 'ab' => 2);
      
      // Problem here: what if $load = true? :-)
      
      $this->properties['oldImageIds'] = $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
      
      return $this->properties['oldImageIds'];
    }

    public function addImage($imageId, $ab)
    {
      $imageIds = $this->imageIds;
      
      if (!$this->itemExists($imageId, 'imageId', $imageIds))
      {
        $item = new stdClass();
        $item->imageId = $imageId;
        $item->ab = $ab;
        $this->properties['imageIds'][] = $item;
        
        $this->dirty['saveImages'] = true;
      }
    }

    public function removeImage($imageId)
    {
      $imageIds = $this->imageIds;
      
      $key = $this->searchItem($imageId, 'imageId', $imageIds);
      
      if ($key !== false)
      {
        unset($this->properties['imageIds'][$key]);
        $this->dirty['saveImages'] = true;
      }
    }

    public function removeImages()
    {
      $this->properties['imageIds'] = array();
      
      $this->dirty['saveImages'] = true;
    }

    protected function saveImages()
    {
      $dataLink = $this->application->dataLink;
      $cacheLink = $this->application->cacheLink;
      
      $cacheKeysToDelete = array();
      $cacheKeysToDelete[] = 'user_images_' . $this->properties['identifier']; 
      
      $oldImageIds = $this->properties['oldImageIds'];
      $imageIds = $this->properties['imageIds'];
      
      $itemsToDelete = $this->itemDiff($oldImageIds, $imageIds, 'imageId');
      $itemsToAdd = $this->itemDiff($imageIds, $oldImageIds, 'imageId');
      
      foreach ($itemsToDelete as $itemToDelete)
      {
        $imageId = $itemToDelete->imageId;
        $cacheKeysToDelete[] = 'image_users_' . $imageId; 
      }
      
      foreach ($cacheKeysToDelete as $cacheKey)
      {
        $cacheLink->delete($cacheKey);
      }
      
      if (count($idsToDelete) > 0)
      {
        $sql = "
          DELETE FROM 
            `link_user_image`
          WHERE 
            `userId` = " . $this->properties['identifier'] . "
            AND `imageId` IN (" . $this->itemImplode($itemsToDelete, 'imageId') . ");";
        
        $dataLink->query($sql);
      }
      
      $insertStrings = array();
      
      foreach ($itemsToAdd as $item)
      { 
        $insertStrings[] = '(' . $dataLink->formatInput($this->properties['identifier'], BM_VT_INTEGER) . ', ' . $dataLink->formatInput($item->imageId, 5) . ', ' . $dataLink->formatInput($item->ab, 2) . ')';
      }
      
      if (count($insertStrings) > 0)
      {
        $sql = "INSERT IGNORE INTO
                  `link_user_image`
                  (`userId`, `imageId`, `ab`)
                VALUES
                  " . implode(', ', $insertStrings) . ";";
                  
        $dataLink->query($sql);
      }
      
      $this->enqueueCache('saveImages');
      $this->dirty['saveImages'] = false;    
    }
    
    /*FF::AC::REFERENCE_FUNCTIONS::image::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
  }
  
?>