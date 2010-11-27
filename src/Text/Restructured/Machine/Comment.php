<?php
namespace Text\Restructured\Machine;
/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */


use Text\Restructured\Event;
/**
 * Comment StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Comment extends Base
{
  const INIT = 0;
  const COMMENT = 1;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    $init_level = $level;
    $mylevel = $init_level;

    while($current = $input->getToken()){
      $next = $input->getVToken();

      if($current->alias == "indent"){
        $mylevel = strlen($current->data);
      }else if($current->alias == "line"){
        $mylevel = 0;
      }

      switch($this->state){
        case self::INIT:
          if($current->alias == "comment"){
            $this->notify(Event::COMMENT_START);
            $this->notify(Event::COMMENT_DATA,$current->data);
            $this->state = self::COMMENT;
          }else{
            $input->back();
            return;
          }
          break;
        case self::COMMENT:
          if($current->alias == "line"){
            // nothing to do.
          }else if($mylevel > $level){
            $this->notify(Event::COMMENT_DATA,$current->data);
          }else{
            $this->notify(Event::COMMENT_END);
            $input->back();
            return;
          }
          break;
      }
      
      $previous = $current;
    }
  }
}