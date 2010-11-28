<?php
namespace Text\Restructured\Loader;
/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * StrignLoader.
 *
 * なんちゃって実装。そのうち共通部分とか分ける
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class StringLoader
{
  const INITIALIZED = 0;
  const INDENT = 1;
  const STRING = 2;

  protected $data;
  protected $offset = 0;
  protected $length;

  public function __construct($data)
  {
    $this->data = $data;
    $this->length = strlen($this->data);
  }
  
  public function getToken()
  {
    $result = "";
    $buffer = "";
    $state = 0;
    $next = null;

    while($this->offset < $this->length)
    {
      $char = $this->data[$this->offset];
      if(isset($this->data[$this->offset+1])){
        $next = $this->data[$this->offset+1];
      }else{
        $next = null;
      }

      switch($state)
      {
        case self::INITIALIZED:
          if($char == " " || $char == "\t"){
            $state = self::INDENT;
          }else if($char == "\r" && $next == "\n" || $char == "\r" && $next != "\n" || $char == "\n" && $next != "\r"){
            //空行
            if($char == "\r" && $next == "\n"){
              $buffer .= $char;
              $this->offset += 2;
              return $buffer;
            }else{
              $this->offset++;
              return $char;
            }
          }else{
            $state = self::STRING;
          }
          break;
        case self::INDENT:
          if($char != " " && $char != "\t")
          {
            //finished
            $result = $buffer;
            return $result;
          }
          break;
        case self::STRING:
          if($char == "\r" && $next == "\n"){
            $this->offset +=2;
            return $buffer . $char . $next;
          }else if($char == "\r" && $next != "\n"){
            $this->offset++;
            return $buffer . $char;
          }else if($char == "\n"){
            $this->offset++;
            return $buffer . $char;
          }
          break;
      }

      $buffer .= $char;
      $this->offset++;
    }
    if(!empty($buffer)){
      return $buffer;
    }
    return false;
  }
}