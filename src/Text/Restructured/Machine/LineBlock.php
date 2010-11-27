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
 * LineBlock StateMachine.
 *
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class LineBlock extends Base
{
  const INIT = 0;
  const SOURCE = 1;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    while($current = $input->getToken()){
      $next = $input->getVToken();

      switch($this->state){
        case self::INIT:
          if($current->alias == "line_block"){
            $this->notify(Event::LINEBLOCK_START);
            $this->notify(Event::LINEBLOCK_DATA, $current->data);
            $this->state = self::SOURCE;
          }else{
            $input->back();
            return;
          }
          break;
        case self::SOURCE:
          if($current->alias == "line_block"){
            $this->notify(Event::LINEBLOCK_DATA, $current->data);
          }else if($current->alias == "line"){
            // nothing to do.
          }else{
            $this->notify(Event::LINEBLOCK_END);
            $input->back();
            return;
          }
          break;
      }
    }
  }
}
