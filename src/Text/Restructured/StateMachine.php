<?php
namespace Text\Restructured;
/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * StateMachine for reStructuredText.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class StateMachine extends Machine\Base
{
  const INIT = 0;
  
  public function execute(TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();

    while($current = $input->getToken()){
      $next = $input->getVToken();

      // Todo: そのうち登録用のInterface考える。流石にベタ書きは汎用性が。
      switch($this->state){
        case self::INIT:
          if($current->alias == "list"){
            $machine = new \Text\Restructured\Machine\BulletList();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "grid_table"){
            $machine = new \Text\Restructured\Machine\GridTable();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "table"){
            $machine = new \Text\Restructured\Machine\Table();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "comment"){
            $machine = new \Text\Restructured\Machine\Comment();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "transition"){
            $machine = new \Text\Restructured\Machine\Transition();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "code"){
            $machine = new \Text\Restructured\Machine\Code();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "doctest"){
            $machine = new \Text\Restructured\Machine\Doctest();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "line_block"){
            $machine = new \Text\Restructured\Machine\LineBlock();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "field_list"){
            $machine = new \Text\Restructured\Machine\FieldList();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "option_list"){
            $machine = new \Text\Restructured\Machine\OptionList();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "text" && $next->alias == "transition"){
            $this->notify(Event::SUBJECT_START,$next->data);
            $this->notify(Event::TEXT,$current->data);
            $this->notify(Event::SUBJECT_END,$next->data);
            $input->getToken();
          }else if($current->alias == "text" && $next->alias != "indent"){
            $machine = new \Text\Restructured\Machine\Paragraph();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "text" && $next->alias == "indent"){
            $machine = new \Text\Restructured\Machine\DefinitionList();
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $input->back();
            $machine->execute($input);
            unset($machine);
          }else if($current->alias == "indent" && $next->alias == "text"){
            $lv = strlen($current->data);
            $input->back();

            $machine = new \Text\Restructured\Machine\BlockQuote($input,$lv);
            $machine->register_handler($this->get_handler());
            $machine->register_root_machine($this);
            $machine->execute($input,$lv);
            unset($machine);

          }else if($current->alias == "line"){
            // nothing to do.
          }else if($current->alias == "eos"){
            return;
          }else{
            var_dump($current);
            throw new \Exception("StateMachineの実装がどっかでおかしいよ＞＜");
          }
          break;
      }
    }
  }
}
