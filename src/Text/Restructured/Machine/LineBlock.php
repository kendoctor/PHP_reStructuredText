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
  const CONTINUOUS = 2;

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
          if($current->alias == "line_block" && $next->alias == "line_block"){
            $this->notify(Event::LINEBLOCK_START);
            $this->notify(Event::LINEBLOCK_DATA, $current->data);
            $this->notify(Event::LINEBLOCK_END);
            $this->state = self::SOURCE;
          }else if($current->alias == "line_block"){
            //line blockが継続する場合?
            $this->notify(Event::LINEBLOCK_START);
            $this->notify(Event::LINEBLOCK_DATA, $current->data);
            $this->state = self::SOURCE;
          }else{
            $input->back();
            return;
          }
          break;
          
        case self::CONTINUOUS:
          if($previous->alias == "line" && $current->alias != "indent"){

            $root = $this->get_root_machine();
            $rest = $root->root_parser;
            $rst = clone $rest;
            $rst->registerStream(new \Text\Restructured\Loader\StringLoader($stack));
            $rst->parse($this->handler);
            $input->back();
            unset($rst);
            $this->notify(Event::LINEBLOCK_END);
            $this->state = self::INIT;
          }else{
            if($current->alias == "indent"){
              //再帰的にパースさせるので継続の意味のインデントは消す
              // => 余計なお世話だったみたい(´；ω；｀))
              //$stack .= substr($mylevel,$pp_level);

              $stack .= $current->line;
            }else{
              $stack .= $current->line;
            }
          }
          
          break;
        case self::SOURCE:
          if($current->alias == "line_block"){
            $this->notify(Event::LINEBLOCK_DATA, $current->data);
          }else if($previous->alias == "indent" && $mylevel >= $level){
            $this->notify(Event::LINEBLOCK_DATA, $current->data);
          }else if($current->alias == "indent" && $mylevel >= $level){
            // continuous

            $stack = "";
            $pp_level = $mylevel;
            $this->state = self::CONTINUOUS;
            $current = $input->getToken();
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
      
      $previous = $current;
    }
  }
}
