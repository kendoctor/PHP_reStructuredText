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
 * Table StateMachine.
 *
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Table extends Base
{
  const INIT = 0;
  const SOURCE = 1;
  const ROW = 2;
  const THINK = 3;

  protected $cols = array();

  /**
   * SimpleTableの一番右のセルだけはヘッダラインを超えてかける。
   * 各セルはまたTokenで読み直されてよしなに処理される。
   * @see also: http://docutils.sourceforge.net/docs/ref/rst/restructuredtext.html#tables
   */
  public function execute(\Text\Restructured\TokenStream &$input,$level = 0)
  {
    $previous = $input->getLastToken();
    $col_cnt = 0;

    while($current = $input->getToken()){
      $next = $input->getVToken();

      switch($this->state){
        case self::INIT:
          if($current->alias == "table"){
            
            $string = rtrim($current->line);
            $length = strlen($string);

            $x = 0;
            $type = 0;
            $cols = array();

            for($i=0;$i<$length;$i++){

              if(($string[$i] == "=" || $string[$i] == "-") && $type == 1){
                $x++;
                $col_cnt++;
              }else if($string[$i] == " " || $string[$i] == "\t" && $type == 0){
                $x++;
              }

              if($string[$i] == "=" || $string[$i] == "-"){
                if(!isset($cols[$x])){
                  $cols[$x] = (object)array("type"=>"cell","length"=>0);
                }

                $cols[$x]->length++;
                $type = 0;
              }else if($string[$i] == " " || $string[$i] == "\t"){
                if(!isset($cols[$x])){
                  $cols[$x] = (object)array("type"=>"space","length"=>0);
                }

                $cols[$x]->length++;
                $type = 1;
              }

            }

            $this->notify(Event::TABLE_START);

            $this->state = self::ROW;
          }else{
            $input->back();
            return;
          }
          break;

        case self::THINK:
          if($current->alias == "indent"){
            //インデントかましてセルの位置が合えば継続行として扱えます。
          }else if(preg_match("/^[=]+/",$current->line) && ($next->alias == "line" || $next->alias == "eos")){
            // 終了処理
            $this->notify(Event::TABLE_END);
            //$input->back();
            return;

          }else if($current->alias == "line"){
            //throw new \Exception("tableの定義がおかしいよ？");
            //なんと改行も許容できます。

          }else if($current->alias == "text"){
            $input->back();
            $this->state = self::ROW;
            continue;
          }
          break;

        case self::ROW:
          if($current->alias == "indent"){
            //skip
          }else{
            $tmp = rtrim($current->data);

            $c = array();
            foreach($cols as $col){
              if($col->type == "cell"){
                $a = substr($tmp,0,$col->length);
                $c[] = $a;
                $tmp =substr($tmp,$col->length);
              }else if($col->type == "space"){
                $tmp = substr($tmp,$col->length);
              }else{
                throw new Exception("なんか違う");
              }
            }
            
            //書き出すのは、次が確定してからだ・・・・
            if(count($c) && $col_cnt){
              $this->notify(Event::ROW_START);
              foreach($c as $column){
                $this->notify(Event::ENTRY_START);
                $this->notify(Event::TEXT, $column);
                $this->notify(Event::ENTRY_END);
              }
              $this->notify(Event::ROW_END);
            }else{
              throw new Exception("セルの数が違うよ");
            }
            
            $this->state = self::THINK;
          }
          break;
      }
    }
    
    $previous = $current;
  }
}
