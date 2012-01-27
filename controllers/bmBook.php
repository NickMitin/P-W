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

  final class bmBook extends bmDataObject
  {
    public function __construct($application, $parameters = array())
    {
      /*FF::AC::MAPPING::{*/

      $this->objectName = 'book';
      $this->map = array_merge($this->map, array(
        'name' => array(
          'fieldName' => 'name',
          'dataType' => BM_VT_STRING,
          'defaultValue' => ''
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
        
        /*FF::AC::GETTER_CASE::author::{*/
        case 'authorIds':
          if (!array_key_exists('authorIds', $this->properties))
          {
            $this->properties['authorIds'] = $this->getAuthors(false);
          }
          return $this->properties['authorIds'];
        break;
        case 'authors':
          return $this->getAuthors();
        break;
        /*FF::AC::GETTER_CASE::author::}*/
 
        /*FF::AC::TOP::GETTER::}*/
        default:
          return parent::__get($propertyName);
        break;
      }
    }
    
    /*FF::AC::TOP::REFERENCE_FUNCTIONS::{*/
    
    /*FF::AC::REFERENCE_FUNCTIONS::author::{*/        
        
    public function getAuthors($load = true)
    {
      $cacheKey = 'book_authors_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_author_book`.`author2Id` AS `identifier`
        FROM 
          `link_author_book`
        WHERE 
          `link_author_book`.`book2Id` = " . $this->properties['identifier'] . ";
      ";
      
      if (!$load)
      {
        $this->properties['oldAuthorIds'] = $this->getSimpleLinks($sql, $cacheKey, 'author', E_OBJECTS_NOT_FOUND, $load);
        
        return $this->properties['oldAuthorIds'];
      }
      else
      {
        return $this->getSimpleLinks($sql, $cacheKey, 'author', E_OBJECTS_NOT_FOUND, $load);
      }
    }

    
    
    /*FF::AC::REFERENCE_FUNCTIONS::author::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
    
    /*FF::AC::DELETE_FUNCTION::{*/        
        
    public function delete()
    {
      
      
      $authors = $this->authors;

      foreach ($authors as $author)
      {
        $author->removeBook($this->properties['identifier']);
      }

      
      
      $this->application->cacheLink->delete($this->objectName . $this->properties['identifier']); 
      
      $sql = "DELETE FROM 
                `book` 
              WHERE 
                `id` = " . $this->properties['identifier'] . ";
              ";
      
      $this->application->dataLink->query($sql);
    }
    
    /*FF::AC::DELETE_FUNCTION::}*/
  }
  
?>