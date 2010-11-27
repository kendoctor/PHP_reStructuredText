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
 * FieldList StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class FieldList extends Base
{
  const INIT = 0;
  const DESCRIPTION = 1;

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
          if($current->alias == "field_list"){
            $this->notify(Event::FIELD_LIST_START);
            $this->notify(Event::FIELD_LIST_ROW_START);

            $this->notify(Event::FIELD_LIST_DEFINITION_START);
            $this->notify(Event::TEXT,$current->data);
            $this->notify(Event::FIELD_LIST_DEFINITION_END);

            $this->notify(Event::FIELD_LIST_DESCRIPTION_START);

            if(!empty($current->option)){
              $this->notify(Event::TEXT,$current->option);
            }

            if($next->alias == "indent" || $next->alias == "field_list"){
              $this->state = self::DESCRIPTION;
            }else{
              $this->notify(Event::FIELD_LIST_DESCRIPTION_END);
              $this->notify(Event::FIELD_LIST_ROW_END);
              $this->notify(Event::FIELD_LIST_END);
              return;
            }
          }else{
            $input->back();
            return;
          }
          break;

        case self::DESCRIPTION:
          if($current->alias == "field_list"){
              $this->notify(Event::FIELD_LIST_DESCRIPTION_END);
              $this->notify(Event::FIELD_LIST_ROW_END);
              $this->notify(Event::FIELD_LIST_ROW_START);


              // INIT と一緒やでー
              $this->notify(Event::FIELD_LIST_DEFINITION_START);
              $this->notify(Event::TEXT,$current->data);
              $this->notify(Event::FIELD_LIST_DEFINITION_END);

              $this->notify(Event::FIELD_LIST_DESCRIPTION_START);

              if(!empty($current->option)){
                $this->notify(Event::TEXT,$current->option);
              }

              if($next->alias == "indent" || $next->alias == "field_list" || $next->alias == "line"){
                $this->state = self::DESCRIPTION;
              }else{
                $this->notify(Event::FIELD_LIST_DESCRIPTION_END);
                $this->notify(Event::FIELD_LIST_ROW_END);
                $this->notify(Event::FIELD_LIST_END);
                return;
              }
              //ここまで一緒やでー

          }else if($current->alias == "line" && $next->alias == "indent"){
            $lv = strlen($next->data);
            $input->getToken();
            $machine = new \Text\Restructured\Machine\Paragraph();
            $machine->register_handler($this->get_handler());
            $machine->execute($input,$lv);
            unset($machine);
            
          }else if($mylevel > 0 && $current->alias == "text"){
            $this->notify(Event::TEXT, $current->data);
          }else if($current->alias == "indent"){
            // nothing to do.
          }else{
            $this->notify(Event::FIELD_LIST_DESCRIPTION_END);
            $this->notify(Event::FIELD_LIST_ROW_END);
            $this->notify(Event::FIELD_LIST_END);
            $input->back();
            return;
          }
          break;
      }
    }
  }
}
