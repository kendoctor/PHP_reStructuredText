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
 * Pragraph StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Paragraph extends Base
{
  const INIT = 0;
  const IN_PARAGRAPH = 1;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    $init_level = $level;
    $mylevel = $init_level;
    $paragraph = 0;

    while($current = $input->getToken()){
      $next = $input->getVToken();

      if($current->alias == "indent"){
        $mylevel = strlen($current->data);
      }else if($current->alias == "line"){
        $mylevel = 0;
      }

      switch($this->state){
        case self::INIT:
          if($mylevel == $init_level && $current->alias == "text"){
            $this->notify(Event::PARAGRAPH_START);
            $paragraph++;

            $this->notify(Event::TEXT,$current->data);
            $this->state = self::IN_PARAGRAPH;

            if($next->alias == "line"){
              if(preg_match("/::$/m",trim($current->data))){
                $machine = new Code();
                $machine->register_handler($this->get_handler());
                $machine->execute($input);
                unset($machine);

                if($paragraph > 0){
                  $this->notify(Event::PARAGRAPH_END);
                  $paragraph--;
                }
                return;
              }
            }

          }else if($level > 0 && $current->alias == "indent"){
            if($level == strlen($current->data)){
              $this->notify(Event::PARAGRAPH_START);
              $paragraph++;
              $current = $input->getToken();

              $this->notify(Event::TEXT,$current->data);
              $this->state = self::IN_PARAGRAPH;
            }
          }else if($current->alias == "line"){
            if($paragraph > 0){
              $this->notify(Event::PARAGRAPH_END);
              $paragraph--;
            }
          }else{
            $input->back();
            return;
          }
          break;

        case self::IN_PARAGRAPH:
          if($current->alias == "text"){
            $this->notify(Event::TEXT,$current->data);

            if($next->alias == "line"){
              if(preg_match("/::$/m",trim($current->data))){
                $machine = new Code();
                $machine->register_handler($this->get_handler());
                $machine->execute($input);
                unset($machine);
                return;
              }
            }
          }else if($current->alias == "line"){
            if($paragraph > 0){
              $this->notify(Event::PARAGRAPH_END);
              $paragraph--;
            }
            $input->back();
            return;
          }else if($level > 0 && $current->alias == "indent"){
            if(strlen($current->data) < $level){
              $input->back();
              return;
            }
          }else{
            if($paragraph > 0){
              $this->notify(Event::PARAGRAPH_END);
              $paragraph--;
            }

            $input->back();
            return;
          }
      }
    }
  }
}