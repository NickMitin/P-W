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
          'dataType' => BM_VT_STRING,
          'defaultValue' => '0'
        ),
        'type' => array(
          'fieldName' => 'type',
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
        
        /*FF::AC::GETTER_CASE::club::{*/
        case 'clubIds':
          if (!array_key_exists('clubIds', $this->properties))
          {
            $this->properties['clubIds'] = $this->getClubs(false);
          }
          return $this->properties['clubIds'];
        break;
        case 'clubs':
          return $this->getClubs();
        break;
        /*FF::AC::GETTER_CASE::club::}*/
 
        /*FF::AC::TOP::GETTER::}*/
        default:
          return parent::__get($propertyName);
        break;
      }
    }
    
    /*FF::AC::TOP::REFERENCE_FUNCTIONS::{*/
    
    /*FF::AC::REFERENCE_FUNCTIONS::club::{*/        
        
    public function getClubs($load = true)
    {
      $cacheKey = 'user_clubs_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_user_club`.`clubId` AS `identifier`
        FROM 
          `link_user_club`
        WHERE 
          `link_user_club`.`userId` = " . $this->properties['identifier'] . ";
      ";
      
      return $this->getSimpleLinks($sql, $cacheKey, 'club', E_OBJECTS_NOT_FOUND, $load);
    }

    public function addClub($clubId)
    {
      $clubIds = $this->clubIds;
      
      if (!in_array($clubId, $clubIds))
      {
        $this->properties['clubIds'][] = $clubId;
      }
      
      $this->dirty['saveClubs'] = true;
    }

    public function removeClub($clubId)
    {
      $clubIds = $this->clubIds;
      
      foreach ($clubIds as $key => $identifier)
      {
        if ($identifier == $clubId)
        {
          unset($this->properties['clubIds'][$key]);
        }
      }
      
      $this->dirty['saveClubs'] = true;
    }

    public function removeClubs()
    {
      $this->properties['clubIds'] = array();
      
      $this->dirty['saveClubs'] = true;
    }

    protected function saveClubs()
    {
      $dataLink = $this->application->dataLink;
      $cacheLink = $this->application->cacheLink;
      
      $cacheKeysToDelete = array();
      $cacheKeysToDelete[] = 'user_clubs_' . $this->properties['identifier']; 
      
      $clubIds = $this->properties['clubIds'];
      
      foreach ($clubIds as $clubId)
      {
        $cacheKeysToDelete[] = 'club_users_' . $clubId; 
      }
      
      foreach ($cacheKeysToDelete as $cacheKey)
      {
        $cacheLink->delete($cacheKey);
      }
      
      $sql = "
        DELETE FROM 
          `link_user_club`
        WHERE 
          `userId` = " . $this->properties['identifier'] . ";";
      
      $dataLink->query($sql);
      
      $insertStrings = array();
      
      foreach ($this->properties['clubIds'] as $identifier)
      { 
        $insertStrings[] = '(' . $dataLink->formatInput($this->properties['identifier'], BM_VT_INTEGER) . ", " . $dataLink->formatInput($identifier, BM_VT_INTEGER) . ')';
      }
      
      if (count($insertStrings) > 0)
      {
        $sql = "INSERT IGNORE INTO
                  `link_user_club`
                  (userId, clubId)
                VALUES
                  " . implode(', ', $insertStrings) . ";";
                  
        $dataLink->query($sql);
      }
      
      $this->enqueueCache('saveClubs');
      $this->dirty['saveClubs'] = false;    
    }
    
    /*FF::AC::REFERENCE_FUNCTIONS::club::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
  }
  
?>