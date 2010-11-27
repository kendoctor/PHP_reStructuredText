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
 * SourceCode StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Code extends Base
{
  const INIT = 0;
  const SOURCE = 1;
  const LINE = 2;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    while($current = $input->getToken()){
      $next = $input->getVToken();

      switch($this->state){
        case self::INIT:
          if($current->alias == "line"){
            $this->notify(Event::CODE_START);

            $this->state = self::SOURCE;
            $level = strlen($next->data);
          }else if($current->alias == "code"){
            $this->notify(Event::CODE_START);
            $this->notify(Event::TEXT, $current->data);
            $this->state = self::LINE;
          }else{
            $input->back();
            return;
          }
          break;
        case self::LINE:
          if($current->alias == "code"){
            $this->notify(Event::TEXT, $indent);
          }else{
            $this->notify(Event::CODE_END);
            $input->back();
            return;
          }
          break;
        case self::SOURCE:
          if($current->alias == "indent"){
            $indent = substr($current->data,$level);

            $this->notify(Event::TEXT, $indent);
            $token = $input->getToken();
            $this->notify(Event::TEXT, $token->line);

          }else if($current->alias == "line" && $next->alias == "indent"){
            //send line;
            $this->notify(Event::TEXT, $current->line);

          }else{
            $this->notify(Event::CODE_END);
            $input->back();
            return;
          }
          break;
      }
    }
  }
}
