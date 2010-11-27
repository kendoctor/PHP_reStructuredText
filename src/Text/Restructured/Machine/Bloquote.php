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
 * Bloquote StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Bloquote extends Base
{
  const INIT = 0;
  const BLOQUOTE = 1;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();

    while($current = $input->getToken()){
      $next = $input->getVToken();

      switch($this->state){
        case self::INIT:
          if($current->alias == "indent"){
            $this->notify(Event::BLOQUOTE_START);
            $this->state = self::BLOQUOTE;
          }else{
            $input->back();
            return;
          }
          break;
        case self::BLOQUOTE:
          if($current->alias == "line" && $next->alias != "indent"){
            $this->notify(Event::BLOQUOTE_END);
            $input->back();
            return;
          }else{
            if($current->alias != "indent"){
              $this->notify(Event::TEXT, $current->data);
            }else{
              $tmp = substr($current->data,$level);
              $this->notify(Event::TEXT, $tmp);
            }
          }
          break;
      }
    }
  }
}
