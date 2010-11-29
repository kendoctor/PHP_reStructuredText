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
  const CONTINUOUS = 2;

  public function close_blockquote()
  {
    if($this->block_quote > 0){
      $this->notify(Event::BLOCKQUOTE_END);
      $this->block_quote--;
    }
  }

  //Todo: なんとかParseできてるけどきちんとリファクタリングしたい
  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    $init_level = $level;
    $mylevel = $init_level;

    $this->block_quote = 0;

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
            $this->block_quote++;
            $this->state = self::BLOCKQUOTE;
          }else{
            $input->back();
            return;
          }
          break;

        case self::CONTINUOUS:
          if($mylevel < $init_level || $current->alias == "eos"){
            $root = $this->get_root_machine();
            $rest = $root->root_parser;
            $rst = clone $rest;
            $rst->registerStream(new \Text\Restructured\Loader\StringLoader($stack));
            $rst->parse($this->handler);
            $input->back();

            unset($rst);
            $this->close_blockquote();
            $this->state = self::BLOCKQUOTE;
          }else{
            $stack .= $current->line;
          }
          
          break;

        case self::BLOCKQUOTE:
          if($current->alias == "text" && $mylevel < $init_level){
            $this->close_blockquote();
            $input->back();
            return;
          }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel > $init_level){
            //再帰
            $machine = new self();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this->get_root_machine());
            $input->back();
            $machine->execute($input,$mylevel);
            unset($machine);
          }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel < $init_level){
            $this->close_blockquote();
            $input->back();
            return;
          }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel == $init_level){
            // continuous
            $stack = "";
            $pp_level = $mylevel;
            $this->state = self::CONTINUOUS;
            //$current = $input->getToken();

          }else if($previous->alias == "indent" && $current->alias == "text"){
            $this->notify(Event::TEXT, $current->data);
          }else if($current->alias == "indent"){
            // nothing to do.
          }else if($current->alias == "line"){
            // nothing to do.
          }else if($current->alias == "text"){
            $this->notify(Event::TEXT, $current->data);
          }else{
            $this->close_blockquote();
            $input->back();
            return;
          }
          break;
      }
      
      $previous = $current;
    }
    
  }
}
