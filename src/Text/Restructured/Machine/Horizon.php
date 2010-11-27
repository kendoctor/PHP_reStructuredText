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
 * Horizon&Subject StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Horizon extends Base
{
  const INIT = 0;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();

    while($current = $input->getToken()){
      $next = $input->getVToken();

      switch($this->state){
        case self::INIT:
          if($current->alias == "horizon" && $next->alias == "text"){
            $this->notify(Event::SUBJECT_START);
          }else if($current->alias == "text" && $previous->alias = "horizon" && $next->alias == "horizon"){
            $this->notify(Event::TEXT,$current->data);
          }else if(($previous && $previous->alias == "text") &&  $current->alias == "horizon"){
            $this->notify(Event::SUBJECT_END);
          }else if($current->alias == "horizon"){
            $this->notify(Event::HORIZON,$current->data);
          }else{
            return;
          }
          break;
      }
    }
  }
}