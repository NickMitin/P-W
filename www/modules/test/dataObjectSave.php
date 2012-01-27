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


  final class bmDataObjectSaveTest extends bmHTMLPage
  {

    function generate()
    {
      if ($this->application->user->type < 100)
      {
        echo 'Недостаточно прав доступа';
        exit;
      }
      
      $output = '';
      
      $mode = 'simple'; // 'simple' or 'complex'
      
      if ($mode == 'simple')
      {
      
        
      //$author = new bmAuthor($this->application, array('identifier' => 0, 'name' => 'Shakespeare'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'All\'s Well That Ends Well'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'As You Like It'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Comedy of Errors'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'Love\'s Labour\'s Lost'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'Measure for Measure'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Merchant of Venice'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Merry Wives of Windsor'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'A Midsummer Night\'s Dream'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Tempest'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'Twelfth Night'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Two Gentlemen of Verona'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Two Noble Kinsmen'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'The Winter\'s Tale'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'Romeo and Juliet'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'King Lear'));
//      $book = new bmBook($this->application, array('identifier' => 0, 'name' => 'Hamlet'));

      $author = new bmAuthor($this->application, array('identifier' => 1));
      
      //$author->addBook(1);
//      $author->addBook(2);
//      $author->addBook(3);
//      $author->addBook(4);
//      $author->addBook(5);
//      $author->addBook(6);
//      $author->addBook(7);
//      $author->addBook(8);
//      $author->addBook(9);
//      $author->addBook(10);
//      $author->addBook(11);
//      $author->addBook(12);
//      $author->addBook(13);
//      $author->addBook(14);
//      $author->addBook(15);
//      $author->addBook(16);
      
      echo '<h1>-- Simple Link Test begins --</h1>';

      
      $author->beginUpdate();
      
      $book1 = new bmBook($this->application, array('identifier' => 0, 'name' => 'New book 1'));      
      $book2 = new bmBook($this->application, array('identifier' => 0, 'name' => 'New book 2'));      
      $book3 = new bmBook($this->application, array('identifier' => 0, 'name' => 'New book 3'));      
      
      $author->removeBook(3);
      $author->removeBook(5);
      $author->removeBook(8);
      
      $author->addBook($book1->identifier);
      $author->addBook($book2->identifier);
      $author->addBook($book3->identifier);
      
      echo 'These books should be deleted: 3, 5, 8';
      echo '<br>';
      echo 'These books should be added: ' . $book1->identifier . ', ' . $book2->identifier . ', ' . $book3->identifier;
      $author->endUpdate();
      $author->save();
      
      //// restore to defaults                            
      echo '<h1>-- Do not look beyond this line -- </h1>';
      
      
      $author->addBook(3);
      $author->addBook(5);
      $author->addBook(8);
      
      $author->removeBook($book1->identifier);
      $author->removeBook($book2->identifier);
      $author->removeBook($book3->identifier);
      
      }
      
      if ($mode == 'complex')
      {
      //$sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'All\'s Well That Ends Well'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'As You Like It'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Comedy of Errors'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'Love\'s Labour\'s Lost'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'Measure for Measure'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Merchant of Venice'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Merry Wives of Windsor'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'A Midsummer Night\'s Dream'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Tempest'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'Twelfth Night'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Two Gentlemen of Verona'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Two Noble Kinsmen'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'The Winter\'s Tale'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'Romeo and Juliet'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'King Lear'));
//      $sketch = new bmSketch($this->application, array('identifier' => 0, 'name' => 'Hamlet'));

      $author = new bmAuthor($this->application, array('identifier' => 1));
      
      //$author->addSketch(1, 1);
//      $author->addSketch(2, 1);
//      $author->addSketch(3, 1);
//      $author->addSketch(4, 1);
//      $author->addSketch(5, 1);
//      $author->addSketch(6, 1);
//      $author->addSketch(7, 1);
//      $author->addSketch(8, 1);
//      $author->addSketch(9, 1);
//      $author->addSketch(10, 1);
//      $author->addSketch(11, 1);
//      $author->addSketch(12, 1);
//      $author->addSketch(13, 1);
//      $author->addSketch(14, 1);
//      $author->addSketch(15, 1);
//      $author->addSketch(16, 1);
        
          
      echo '<h1>-- Complex Link Test begins --</h1>';
      
      $author->beginUpdate();
      
      $sketch1 = new bmSketch($this->application, array('identifier' => 0, 'name' => 'New sketch 1'));      
      $sketch2 = new bmSketch($this->application, array('identifier' => 0, 'name' => 'New sketch 2'));      
      $sketch3 = new bmSketch($this->application, array('identifier' => 0, 'name' => 'New sketch 3'));      
      
      $author->removeSketch(2);
      $author->removeSketch(4);
      $author->removeSketch(6);
      
      $author->addSketch($sketch1->identifier, 2);
      $author->addSketch($sketch2->identifier, 2);
      $author->addSketch($sketch3->identifier, 2);
      
      echo 'These sketches should be deleted: 2, 4, 6';
      echo '<br>';
      echo 'These books should be added: ' . $sketch1->identifier . ', ' . $sketch2->identifier . ', ' . $sketch3->identifier;
      $author->endUpdate();
      $author->save();
      
      //// restore to defaults                            
      echo '<h1>-- Do not look beyond this line -- </h1>';
      
      
      $author->addSketch(2, 1);
      $author->addSketch(4, 1);
      $author->addSketch(6, 1);
      
      $author->removeSketch($sketch1->identifier);
      $author->removeSketch($sketch2->identifier);
      $author->removeSketch($sketch3->identifier);
        
      }
      
      
      eval('$output = "' . $this->application->getTemplate('/test/dataObjectSave') . '";'); 
      
      
      
      return $output;
      
    }
  }
?>