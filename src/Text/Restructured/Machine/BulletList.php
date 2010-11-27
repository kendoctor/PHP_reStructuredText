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
 * BulletList StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class BulletList extends Base
{
  const INIT = 0;
  const BULLET_LIST = 1;
  const PARAGRAPH = 2;

  const LIST_START = Event::UNORDERED_LIST_START;
  const LIST_END = Event::UNORDERED_LIST_END;

  public $state = 0;
  public $count = 0;

  protected function start_list_item()
  {
    $this->notify(Event::LIST_ITEM_START);
    $this->count++;
  }

  protected function if_close_list_item()
  {
    if($this->count > 0){
      $this->notify(Event::LIST_ITEM_END);
      $this->count--;
    }
  }

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
          if($mylevel == $init_level && $current->alias == "list"){
            $this->state = self::BULLET_LIST;
            $this->notify(self::LIST_START);
            $input->back();
            continue;
          }else{
            throw new \Exception();
          }
          break;
          
        case self::PARAGRAPH:
          if($current->alias == "text" && $mylevel > $level){
            $this->notify(Event::TEXT,$current->data);
          }else if($current->alias == "indent" && $next->alias == "text"){
            //nothing to do.
          }else if($current->alias == "line"){
            $this->state = self::BULLET_LIST;
          }else{
            $this->state = self::BULLET_LIST;
          }
          break;

        case self::BULLET_LIST;
          if($mylevel == $level && $current->alias == "list"){
            $this->if_close_list_item();

            $this->start_list_item();
            $this->notify(Event::TEXT,$current->data);

          }else if($previous->alias == "list" && $current->alias == "indent" && $next->alias == "text"){
            $this->state = self::PARAGRAPH;

          }else if($previous->alias == "line" && $current->alias == "indent" && $next->alias == "text"){
            if($mylevel > $level){
              $machine = new Paragraph();
              $machine->register_handler($this->get_handler());
              $machine->execute($input,$mylevel);
              unset($machine);
            }else if($lv == $level){
              $this->start_list_item();
              $this->notify(Event::TEXT,$current->data);
            }
          }else if($previous->alias == "line" && $current->alias == "indent" && $next->alias == "list"){
            if($mylevel > $level){
              $machine = new self();
              $machine->register_handler($this->get_handler());
              $machine->execute($input,$mylevel);
              unset($machine);
              $input->back();
            }else if($mylevel == $level){
              $this->if_close_list_item();
              $this->start_list_item();
              $this->notify(Event::TEXT,$next->data);
              $input->getToken();
            }else if($mylevel < $level){
              $this->if_close_list_item();
              $this->notify(self::LIST_END);
              $input->back();
              return;
            }else{
              throw new \Exception("予想外のエラー。多分実装漏れだと思います。");
            }
          }else if($current->alias == "indent" && $next->alias == "list"){
            if($mylevel != $level){
              throw new \Exception("多分インデントがおかしいと思うよ");
            }
          }else if($current->alias == "line"){
            // nothing to do.
          }else if($mylevel < $level){
            $this->if_close_list_item();
            $this->notify(self::LIST_END);
            $input->back();
            return;
          }else{
            $this->if_close_list_item();
            $this->notify(self::LIST_END);
            $input->back();
            return;
          }
          break;
      }

      $previous = $current;
    }
  }
}
