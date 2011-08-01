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


  final class bmSession extends bmCustomWebSession
  {

    public function __construct($aplication, $parameters = array())
    {
      /*FF::AC::MAPPING::{*/

      $this->objectName = 'session';
      $this->map = array_merge($this->map, array(
        'ipAddress' => array(
          'fieldName' => 'ipAddress',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'userId' => array(
          'fieldName' => 'userId',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'userAgent' => array(
          'fieldName' => 'userAgent',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'location' => array(
          'fieldName' => 'location',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
        ),
        'createTime' => array(
          'fieldName' => 'createTime',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        ),
        'lastActivity' => array(
          'fieldName' => 'lastActivity',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        ),
        'lastVisit' => array(
          'fieldName' => 'lastVisit',
          'dataType' => BM_VT_INTEGER,
          'defaultValue' => 0
        )
      ));

      /*FF::AC::MAPPING::}*/

      parent::__construct($aplication, $parameters);
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
      $cacheKey = 'session_users_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_user_session`.`userId` AS `userId`,
          `link_user_session`.`sessionId` AS `sessionId`,
          `link_user_session`.`time` AS `time`
        FROM 
          `link_user_session`
        WHERE 
          `link_user_session`.`sessionId` = " . $this->properties['identifier'] . ";
      ";
      
      $map = array('user IS user' => 5, 'session IS session' => 5, 'time' => 4);
      
      return $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
    }
    
    /*FF::AC::REFERENCE_FUNCTIONS::user::}*/

    /*FF::AC::REFERENCE_FUNCTIONS::::{*/        
        
    

    
    
    /*FF::AC::REFERENCE_FUNCTIONS::::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
  
  }
?>