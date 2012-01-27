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

  final class bmAuthor extends bmDataObject
  {
    public function __construct($application, $parameters = array())
    {
      /*FF::AC::MAPPING::{*/

      $this->objectName = 'author';
      $this->map = array_merge($this->map, array(
        'name' => array(
          'fieldName' => 'name',
          'dataType' => BM_VT_STRING,
          'defaultValue' => '0'
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
        
        /*FF::AC::GETTER_CASE::book::{*/
        case 'bookIds':
          if (!array_key_exists('bookIds', $this->properties))
          {
            $this->properties['bookIds'] = $this->getBooks(false);
          }
          return $this->properties['bookIds'];
        break;
        case 'books':
          return $this->getBooks();
        break;
        /*FF::AC::GETTER_CASE::book::}*/
        /*FF::AC::GETTER_CASE::sketch::{*/
        case 'sketchIds':
          if (!array_key_exists('sketchIds', $this->properties))
          {
            $this->properties['sketchIds'] = $this->getSketchs(false);
          }
          return $this->properties['sketchIds'];
        break;
        case 'sketchs':
          return $this->getSketchs();
        break;
        /*FF::AC::GETTER_CASE::sketch::}*/
 
        /*FF::AC::TOP::GETTER::}*/
        default:
          return parent::__get($propertyName);
        break;
      }
    }
    
    /*FF::AC::TOP::REFERENCE_FUNCTIONS::{*/
    
    /*FF::AC::REFERENCE_FUNCTIONS::book::{*/        
        
    public function getBooks($load = true)
    {
      $cacheKey = 'author_books_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_author_book`.`book2Id` AS `identifier`
        FROM 
          `link_author_book`
        WHERE 
          `link_author_book`.`author2Id` = " . $this->properties['identifier'] . ";
      ";
      
      if (!$load)
      {
        $this->properties['oldBookIds'] = $this->getSimpleLinks($sql, $cacheKey, 'book', E_OBJECTS_NOT_FOUND, $load);
        
        return $this->properties['oldBookIds'];
      }
      else
      {
        return $this->getSimpleLinks($sql, $cacheKey, 'book', E_OBJECTS_NOT_FOUND, $load);
      }
    }

    public function addBook($bookId)
    {
      $bookIds = $this->bookIds;
      
      if (!in_array($bookId, $bookIds))
      {
        $this->properties['bookIds'][] = $bookId;
      }
      
      $this->dirty['saveBooks'] = true;
    }

    public function removeBook($bookId)
    {
      $bookIds = $this->bookIds;
      
      foreach ($bookIds as $key => $identifier)
      {
        if ($identifier == $bookId)
        {
          unset($this->properties['bookIds'][$key]);
        }
      }
      
      $this->dirty['saveBooks'] = true;
    }

    public function removeBooks()
    {
      $this->properties['bookIds'] = array();
      
      $this->dirty['saveBooks'] = true;
    }

    protected function saveBooks()
    {
      $dataLink = $this->application->dataLink;
      $cacheLink = $this->application->cacheLink;
      
      $cacheKeysToDelete = array();
      $cacheKeysToDelete[] = 'author_books_' . $this->properties['identifier']; 
      
      $oldBookIds = $this->properties['oldBookIds'];
      $bookIds = $this->properties['bookIds'];
      
      $idsToDelete = array_diff($oldBookIds, $bookIds);
      $idsToAdd = array_diff($bookIds, $oldBookIds);
      
      foreach ($idsToDelete as $idToDelete)
      {
        $cacheKeysToDelete[] = 'book_authors_' . $idToDelete;
      }
      
      foreach ($cacheKeysToDelete as $cacheKey)
      {
        $cacheLink->delete($cacheKey);
      }
      
      if (count($idsToDelete) > 0)
      {
        $sql = "
          DELETE FROM 
            `link_author_book`
          WHERE 
            `author2Id` = " . $this->properties['identifier'] . "
            AND `book2Id` IN (" . implode(', ', $idsToDelete) . ");";
        
        $dataLink->query($sql);
        
        echo '<br />' . $sql . '<br />';
      }
      
      $insertStrings = array();
      
      foreach ($idsToAdd as $identifier)
      { 
        $insertStrings[] = '(' . $dataLink->formatInput($this->properties['identifier'], BM_VT_INTEGER) . ", " . $dataLink->formatInput($identifier, BM_VT_INTEGER) . ')';
      }
      
      if (count($insertStrings) > 0)
      {
        $sql = "INSERT IGNORE INTO
                  `link_author_book`
                  (author2Id, book2Id)
                VALUES
                  " . implode(', ', $insertStrings) . ";";
                  
        $dataLink->query($sql);
        
        echo '<br />' . $sql . '<br />';
      }
      
      $this->enqueueCache('saveBooks');
      $this->dirty['saveBooks'] = false;
      
      $this->properties['oldBookIds'] = $this->properties['bookIds'];
    }
    
    /*FF::AC::REFERENCE_FUNCTIONS::book::}*/

    /*FF::AC::REFERENCE_FUNCTIONS::sketch::{*/        
        
    public function getSketchs($load = true)
    {
      $cacheKey = 'author_sketchs_' . $this->properties['identifier'];
      
      $sql = "
        SELECT 
          `link_author_sketch`.`author2Id` AS `authorId`,
          `link_author_sketch`.`sketch2Id` AS `sketchId`,
          `link_author_sketch`.`rating2` AS `rating`
        FROM 
          `link_author_sketch`
        WHERE 
          `link_author_sketch`.`author2Id` = " . $this->properties['identifier'] . ";
      ";
      
      $map = array('author IS author' => 5, 'sketch IS sketch' => 5, 'rating' => 2);
      
      if (!$load)
      {
        $this->properties['oldSketchIds'] = $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
        
        return $this->properties['oldSketchIds'];
      }
      else
      {
        return $this->getComplexLinks($sql, $cacheKey, $map, E_OBJECTS_NOT_FOUND, $load);
      }
    }

    public function addSketch($sketchId, $rating)
    {
      $sketchIds = $this->sketchIds;
      
      if (!$this->itemExists($sketchId, 'sketchId', $sketchIds))
      {
        $item = new stdClass();
        $item->sketchId = $sketchId;
        $item->rating = $rating;
        $this->properties['sketchIds'][] = $item;
        
        $this->dirty['saveSketchs'] = true;
      }
    }

    public function removeSketch($sketchId)
    {
      $sketchIds = $this->sketchIds;
      
      $key = $this->searchItem($sketchId, 'sketchId', $sketchIds);
      
      if ($key !== false)
      {
        unset($this->properties['sketchIds'][$key]);
        $this->dirty['saveSketchs'] = true;
      }
    }

    public function removeSketchs()
    {
      $this->properties['sketchIds'] = array();
      
      $this->dirty['saveSketchs'] = true;
    }

    protected function saveSketchs()
    {
      $dataLink = $this->application->dataLink;
      $cacheLink = $this->application->cacheLink;
      
      $cacheKeysToDelete = array();
      $cacheKeysToDelete[] = 'author_sketchs_' . $this->properties['identifier']; 
      
      $oldSketchIds = $this->properties['oldSketchIds'];
      $sketchIds = $this->properties['sketchIds'];
      
      $itemsToDelete = $this->itemDiff($oldSketchIds, $sketchIds, 'sketchId');
      $itemsToAdd = $this->itemDiff($sketchIds, $oldSketchIds, 'sketchId');
      
      foreach ($itemsToDelete as $itemToDelete)
      {
        $sketchId = $itemToDelete->sketchId;
        $cacheKeysToDelete[] = 'sketch_authors_' . $sketchId; 
      }
      
      foreach ($cacheKeysToDelete as $cacheKey)
      {
        $cacheLink->delete($cacheKey);
      }
      
      if (count($itemsToDelete) > 0)
      {
        $sql = "
          DELETE FROM 
            `link_author_sketch`
          WHERE 
            `author2Id` = " . $this->properties['identifier'] . "
            AND `sketch2Id` IN (" . $this->itemImplode($itemsToDelete, 'sketchId') . ");";
        
        $dataLink->query($sql);
        
        echo '<br />' . $sql . '<br />';
      }
      
      $insertStrings = array();
      
      foreach ($itemsToAdd as $item)
      { 
        $insertStrings[] = '(' . $dataLink->formatInput($this->properties['identifier'], BM_VT_INTEGER) . ', ' . $dataLink->formatInput($item->sketchId, 5) . ', ' . $dataLink->formatInput($item->rating, 2) . ')';
      }
      
      if (count($insertStrings) > 0)
      {
        $sql = "INSERT IGNORE INTO
                  `link_author_sketch`
                  (`author2Id`, `sketch2Id`, `rating2`)
                VALUES
                  " . implode(', ', $insertStrings) . ";";
                  
        $dataLink->query($sql);
        
        echo '<br />' . $sql . '<br />';
      }
      
      $this->enqueueCache('saveSketchs');
      $this->dirty['saveSketchs'] = false;    
      
      $this->properties['oldSketchIds'] = $this->properties['sketchIds'];
    }
    
    /*FF::AC::REFERENCE_FUNCTIONS::sketch::}*/


    /*FF::AC::TOP::REFERENCE_FUNCTIONS::}*/
    
    /*FF::AC::DELETE_FUNCTION::{*/        
        
    public function delete()
    {
      $this->removeBooks();

      $this->removeSketchs();

      
      
      
      
      $this->application->cacheLink->delete($this->objectName . $this->properties['identifier']); 
      
      $sql = "DELETE FROM 
                `author` 
              WHERE 
                `id` = " . $this->properties['identifier'] . ";
              ";
      
      $this->application->dataLink->query($sql);
    }
    
    /*FF::AC::DELETE_FUNCTION::}*/
  }
  
?>