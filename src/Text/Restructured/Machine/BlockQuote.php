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
 * BLOCKQUOTE StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class BlockQuote extends Base
{
  const INIT = 0;
  const BLOCKQUOTE = 1;

  //Todo: 継続中はrstパーサで再帰的に処理される
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
          if($current->alias == "indent"){
            $this->notify(Event::BLOCKQUOTE_START);
            $this->state = self::BLOCKQUOTE;
          }else{
            $input->back();
            return;
          }
          break;

        case self::BLOCKQUOTE:
          
          if($current->alias == "text" && $mylevel == 0){
            $this->notify(Event::BLOCKQUOTE_END);
            $input->back();
            return;
          }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel > $init_level){
            $machine = new self();
            $machine->register_handler($this->get_handler());
            $input->back();
            $machine->execute($input,$mylevel);
            unset($machine);
          }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel < $init_level){
            $this->notify(Event::BLOCKQUOTE_END);
            $input->back();
            return;
          }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel == $init_level){
            $this->notify(Event::BLOCKQUOTE_END);
            $this->state = self::INIT;
            $input->back();
            continue;
          }else if($previous->alias == "indent" && $current->alias == "text"){
            $this->notify(Event::TEXT, $current->data);
          }else if($current->alias == "indent"){
            // nothing to do.
          }else if($current->alias == "line"){
            // nothing to do.
          }else if($current->alias == "text"){
            $this->notify(Event::TEXT, $current->data);
          }else{
              $this->notify(Event::BLOCKQUOTE_END);
              $input->back();
              return;
          }
          break;
      }
      
      $previous = $current;
    }
    
  }
}
