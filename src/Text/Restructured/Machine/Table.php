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
  const RENDERING = 4;

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
    $row_cnt = 0;
    $rows = array();

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
              $char = $string[$i];

              if(($char == "=" || $char == "-") && $type == 1){
                $x++;
                $col_cnt++;
              }else if($char == " " || $char == "\t" && $type == 0){
                $x++;
              }

              if($char == "=" || $char == "-"){
                if(!isset($cols[$x])){
                  $cols[$x] = (object)array("type"=>"cell","length"=>0);
                }

                $cols[$x]->length++;
                $type = 0;
              }else if($char == " " || $char == "\t"){
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

          if($current->alias == "indent" && $next->alias != "eos"){
            //インデントかましてセルの位置が合えば継続行として扱えます。
            $tmp = $current->data;
            $current = $input->getToken();
            $tmp .= $current->line;

            $c = array();
            $n = 0;

            foreach($cols as $col){
              if($col->type == "cell"){
                if($n == $col_cnt){
                  $a = $tmp;
                }else{
                  $a = substr($tmp,0,$col->length);
                }

                $c[] = $a;
                $tmp = substr($tmp,$col->length);
                $n++;
              }else if($col->type == "space"){
                $tmp = substr($tmp,$col->length);
              }else{
                throw new Exception("なんか違う");
              }
            }

            //書き出すのは全部確定してから
            if(count($c) && $col_cnt){
              $nu = 0;
              foreach($c as $cc){
                if(trim($cc)){
                  
                  $rows[$row_cnt-1][$nu] .= PHP_EOL . $cc;
                }
                $nu++;
              }
            }else{
              throw new \Exception("セルの数が違うよ2");
            }

            $this->state = self::THINK;

          }else if(preg_match("/^[=]+/",$current->line) && ($next->alias == "line" || $next->alias == "eos")){
            // 終了処理
            $input->back();
            $this->state = self::RENDERING;
            continue;
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
            $n = 0;
            foreach($cols as $offset => $col){
              if($col->type == "cell"){

                if($n == $col_cnt){
                  $a = $tmp;
                }else{
                  
                  $s_len = mb_strlen($tmp);
                  $s_buffer = "";
                  for($i=0,$l=0;$l < $col->length && $i<$s_len;$i++,$l++){
                    $s_char = mb_substr($tmp,$i,1,"utf-8");
                    $s_width = mb_strwidth($s_char,"utf-8");
                    if($s_width == 2){
                      $l++;
                    }
                    $s_buffer .= $s_char;
                  }
                  $a = $s_buffer;
                }
                $c[] = $a;
                $tmp = mb_substr($tmp,$i,8192,"utf-8");
                $n++;
              }else if($col->type == "space"){

                $tmp = substr($tmp,$col->length);
              }else{
                throw new Exception("なんか違う");
              }
            }
            

            //書き出すのは全部確定してから
            if(count($c) && $col_cnt){
              $rows[$row_cnt] = $c;
              $row_cnt++;
            }else{
              throw new \Exception("セルの数が違うよ");
            }
            
            $this->state = self::THINK;
          }
          break;
          
        case self::RENDERING:
          //貯めたテーブルを通知しまくる

          //Todo: 暫定的にこれで
          $root = $this->get_root_machine();
          $rest = $root->root_parser;
          foreach($rows as $c){
              $this->notify(Event::ROW_START);

              foreach($c as $column){
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
      }
    }
    
    $previous = $current;
  }
}
