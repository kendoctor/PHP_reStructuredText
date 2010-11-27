<?php
namespace Text\Restructured;
/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * TokenStream.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class TokenStream implements \Countable, \ArrayAccess{
  public $array;
  public $offset = 0;

  public function getLastToken()
  {
    $offset = $this->offset-1;
    if(isset($this->array[$offset])){
      return $this->array[$offset];
    }else{
      return false;
    }
  }

  public function getNextToken()
  {
    $offset = $this->offset+1;
    if(isset($this->array[$offset])){
      return $this->array[$offset];
    }else{
      return false;
    }
  }
  public function back(){
    $this->offset--;
  }

  public function getVToken()
  {
    if(isset($this->array[$this->offset])){
      $tmp = $this->array[$this->offset];
      return $tmp;
    }
  }

  public function getToken()
  {
    if(isset($this->array[$this->offset])){
      $tmp = $this->array[$this->offset];
      $this->offset++;
      return $tmp;
    }
  }

  public function count(){
    return count($this->array);
  }
  public function append($obj){
    $this->array[] = $obj;
  }
  public function offsetExists($offset){
    if(isset($this->array[$offset])){
      return true;
    }else{
      return false;
    }
  }
  public function offsetGet($offset){
    if(isset($this->array[$offset])){
      return $this->array[$offset];
    }else{
      return false;
    }
  }
  public function offsetSet($offset,$value){
    $this->array[$offset] = $value;
  }

  public function offsetUnset($offset){
    unset($this->array[$offset]);
  }

  public function __construct()
  {
    $this->array = array();
  }
}