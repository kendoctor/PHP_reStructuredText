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
 * DefinitionList StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class DefinitionList extends Base
{
  const INIT = 0;
  const DESCRIPTION = 1;
  const DEFINITION = 2;

  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    $init_level = $level;
    $mylevel = $init_level;
    $dd = 0;

    while($current = $input->getToken()){
      $next = $input->getVToken();

      if($current->alias == "indent"){
        $mylevel = strlen($current->data);
      }else if($current->alias == "line"){
        $mylevel = 0;
      }

      switch($this->state){
        case self::INIT:
          if($current->alias == "text"){
            $this->notify(Event::DEFINITION_LIST_START);
            $this->notify(Event::DEFINITION_LIST_ROW_START);

            $this->notify(Event::DEFINITION_LIST_DEFINITION_START);
            $this->notify(Event::TEXT,$current->data);
            $this->notify(Event::DEFINITION_LIST_DEFINITION_END);

            $this->state = self::DESCRIPTION;
          }
          break;
        case self::DEFINITION:
          $this->notify(Event::DEFINITION_LIST_DEFINITION_START);
          $this->notify(Event::TEXT,$current->data);
          $this->notify(Event::DEFINITION_LIST_DEFINITION_END);

          $this->state = self::DESCRIPTION;
          break;
        case self::DESCRIPTION:
          if($current->alias == "indent"){
            $this->notify(Event::DEFINITION_LIST_DESCRIPTION_START);

            $dd++;

          }else if($current->alias == "text"){
              $this->notify(Event::TEXT,$current->data);
          }else if($current->alias == "line" && $next->alias == "indent"){
            $lv = strlen($next->data);
            $input->getToken();
            $machine = new \Text\Restructured\Machine\Paragraph();
            $machine->register_handler($this->get_handler());
            $machine->execute($input,$lv);
            unset($machine);
          }else if($current->alias == "line" && $next->alias == "text"){
            $b = $input->getToken();
            $c = $input->getVToken();
            if($c->alias == "indent"){
              if($dd > 0){
                $this->notify(Event::DEFINITION_LIST_DESCRIPTION_END);
                $dd--;
              }
              $input->back();
              $this->state = self::DEFINITION;
            }else{
              $this->notify(Event::DEFINITION_LIST_DESCRIPTION_END);
              $this->notify(Event::DEFINITION_LIST_ROW_END);
              $this->notify(Event::DEFINITION_LIST_END);
              $input->back();
              return;
            }
          }else if($current->alias == "line"){
            if($dd > 0){
              $this->notify(Event::DEFINITION_LIST_DESCRIPTION_END);
              $dd--;
            }
            
          }else{
            $this->notify(Event::DEFINITION_LIST_DESCRIPTION_END);
            $this->notify(Event::DEFINITION_LIST_ROW_END);
            $this->notify(Event::DEFINITION_LIST_END);
            $input->back();
            return;
          }
          break;
      }
    }
  }
}
