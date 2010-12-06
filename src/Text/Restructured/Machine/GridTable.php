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
 * GridTable StateMachine.
 *
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class GridTable extends Base
{
  const INIT = 0;
  const SOURCE = 1;
  const ROW = 2;
  const ENTRY = 3;
  const RENDERING = 4;

  protected $cols = array();


  /**
   * 行中のセル数とセルの長さを数えて返す
   *
   * @params Parser $input
   * @return array
   */
  protected function detect_cells($input)
  {
    $length = strlen($input->data);
    $cells = array();
    
    $tmp_len = 0;
    for($i=0,$j=-1;$i<$length;$i++){
      $char = $input->data[$i];
      
      if($char == "+"){
        if($j > -1){
          $cells[$j] = $tmp_len;
          $tmp_len = 0;
        }
        $j++;
      }else{
        $tmp_len++;
      }
    }
    return $cells;
  }
  
  protected function parse_cells($input, $cell_structure)
  {
    $count = count($cell_structure);
    $data = $input->line;
    $offset = 0;
    $cells = array();

    for($i=0;$i<$count;$i++){
      if(isset($cell_structure[$i])){
        $len = $cell_structure[$i];
      }else{
        $len = null;
      }
      $tmp = substr($data,$offset+1,$len);
      $cells[] = $tmp;
      $offset += $cell_structure[$i]+1;
    }
    if(count($cell_structure) && count($cells)){
      return $cells;
    }else{
      throw new \Exception();
    }
  }


  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    $col_cnt = 0;
    $row_cnt = 0;
    $rows = array();
    $first_rows = array();

    while($current = $input->getToken()){
      $next = $input->getVToken();

      switch($this->state){
        case self::INIT:
          if($current->alias == "grid_table" && $next->alias == "line_block"){
            //Todo: セルのマージがチェックできるように
            $first_rows = $this->detect_cells($current);
            $this->state = self::ROW;
          }else{
            $input->back();
            return;
          }
          break;

        case self::ENTRY:
          if($current->alias == "grid_table" && $next->alias == "line_block"){
            $before_rows = $this->detect_cells($current);
            $this->state = self::ROW;
          }else if($current->alias == "grid_table" && ($next->alias == "line" || $next->alias == "eos")){
            $input->back();
            $this->state = self::RENDERING;
            break;
          }else{
            var_dump($next->alias);
            throw new Exception();
          }
          break;

        case self::ROW:
          if($current->alias == "line_block"){
            //Todo: セルのマージができるように
            $rows[] = $this->parse_cells($current,$first_rows);
            $this->state = self::ENTRY;
          }else{
            throw new Exception();
          }
          break;
          
        case self::RENDERING:

          $root = $this->get_root_machine();
          $rest = $root->root_parser;

          $this->notify(Event::TABLE_START);

          foreach($rows as $c){
              $this->notify(Event::ROW_START);

              foreach($c as $column){
                //Todo: morecolumn,morerowsのオプションをつけよう
                $this->notify(Event::ENTRY_START);

                $rst = clone $rest;
                $rst->registerStream(new \Text\Restructured\Loader\StringLoader(trim($column)));
                $rst->parse($this->handler);
                unset($rst);
                $this->notify(Event::ENTRY_END);
              }

              $this->notify(Event::ROW_END);
          }

          $this->notify(Event::TABLE_END);

          return;
          break;
      }

      $previous = $current;
    }
    
  }
}
