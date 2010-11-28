<?php
require __DIR__ . "/common.php";

$s = new Text\Restructured\Loader\FileLoader($_SERVER['argv'][1]);

$r = new Text\Restructured\Restructured($s, new Text\Restructured\StateMachine());
$r->registerState($states);

use Text\Restructured\Event;

echo "<html><head>";
echo "<style type='text/css'>
  blockquote{margin-left:4em;background:#F9F966;padding:0.5em;border:orange 1px solid;}
  pre{margin-left:4em;background:#CFCFCF;padding:0.5em;}
  dl{margin-left:4em;}
  dt{font-size:1.2em;}
  dd{padding:0.5em}
  h1{text-align:center;font-size:45pt}
  h2,h3,h4,h5,h6{font-size:20pt}
  table{margin-left:4em;}
  p{margin-left:2em;}
</style>";
echo "</head><body>" . PHP_EOL;


$subject = array();
$s = 1;
$comment = 0;
/**
 * parseをコールすると読み込んだファイルを解析しつつイベントが通知されます。
 * イベント毎の処理を記述することでrenderingすることができます。
 * 何が何回コールされたとか、そういった情報はもっていないので
 * イベントハンドラ側で状態を記憶してあげる必要があります
 * (例えばtransitionの種類とか、subjectの種類とか)
 */
$r->parse(function($event){
  global $subject;
  global $s;
  global $comment;

  switch($event->type){
    case Event::PARAGRAPH_START:
      echo "<p>";
      break;

    case Event::PARAGRAPH_END:
      echo "</p>" . PHP_EOL;
      break;

    case Event::SUBJECT_START:
      if(!isset($subject[$event->data])){
        $subject[$event->data] = $s;
        if($s<2){
          $s++;
        }
      }
      echo "<h{$subject[$event->data]}>";
      break;

    case Event::SUBJECT_END:
      echo "</h{$subject[$event->data]}>" . PHP_EOL;
      break;

    case Event::BLOCKQUOTE_START:
      echo "<blockquote>" . PHP_EOL;
      break;

    case Event::BLOCKQUOTE_END:
      echo "</blockquote>" . PHP_EOL;
      break;

    case Event::UNORDERED_LIST_START:
      echo "<ul>" . PHP_EOL;
      break;

    case Event::UNORDERED_LIST_END:
      echo "</ul>" . PHP_EOL;
      break;
      
    case Event::LIST_ITEM_START:
      echo "<li>";
      break;

    case Event::LIST_ITEM_END:
      echo "</li>" . PHP_EOL;
      break;

    case Event::TRANSITION:
      echo "<hr />";
      break;

    case Event::COMMENT_START:
      if($comment == 0){
       echo "<!-- ";
      }else{
        echo "&gt;!-- ";
      }
      $comment++;
      break;
    case Event::COMMENT_END:
      $comment--;
      if($comment == 0){
       echo " -->" . PHP_EOL;
      }else{
        echo " --&lt;" . PHP_EOL;
      }
      break;

    case Event::CODE_START:
      echo "<pre>";
      break;
    case Event::CODE_END:
      echo "</pre>";
      break;

    case Event::COMMENT_DATA:
      echo htmlentities($event->data,ENT_QUOTES,"UTF-8");
      break;

    case Event::TEXT:
      echo $event->data;
      break;

    case Event::DOCTEST_START:
      echo "<pre>[doctest]";
      break;
    case Event::DOCTEST_DATA:
      echo htmlentities($event->data,ENT_QUOTES,"UTF-8");
      break;
    case Event::DOCTEST_END;
      echo "</pre>" . PHP_EOL;
      break;

    case Event::LINEBLOCK_START:
      echo "<pre>[lineblock]";
      break;
    case Event::LINEBLOCK_DATA:
      echo htmlentities($event->data,ENT_QUOTES,"UTF-8");
      break;
    case Event::LINEBLOCK_END;
      echo "</pre>" . PHP_EOL;
      break;
      
    case Event::FIELD_LIST_START:
      echo "<table border=\"1\">" . PHP_EOL;
      break;

    case Event::FIELD_LIST_END:
      echo "</table>" . PHP_EOL;
      break;

    case Event::FIELD_LIST_ROW_START:
      echo "<tr>" . PHP_EOL;
      break;

    case Event::FIELD_LIST_ROW_END:
      echo "</tr>" . PHP_EOL;
      break;

    case Event::FIELD_LIST_DEFINITION_START:
      echo "<th>";
      break;

    case Event::FIELD_LIST_DEFINITION_END:
      echo "</th>" . PHP_EOL;
      break;

    case Event::FIELD_LIST_DESCRIPTION_START:
      echo "<td>";
      break;

    case Event::FIELD_LIST_DESCRIPTION_END:
      echo "</td>" . PHP_EOL;
      break;



    case Event::OPTION_LIST_START:
      echo "<table border=\"2\">" . PHP_EOL;
      break;

    case Event::OPTION_LIST_END:
      echo "</table>" . PHP_EOL;
      break;

    case Event::OPTION_LIST_ROW_START:
      echo "<tr>" . PHP_EOL;
      break;

    case Event::OPTION_LIST_ROW_END:
      echo "</tr>" . PHP_EOL;
      break;

    case Event::OPTION_LIST_DEFINITION_START:
      echo "<th>";
      break;

    case Event::OPTION_LIST_DEFINITION_END:
      echo "</th>" . PHP_EOL;
      break;

    case Event::OPTION_LIST_DESCRIPTION_START:
      echo "<td>";
      break;

    case Event::OPTION_LIST_DESCRIPTION_END:
      echo "</td>" . PHP_EOL;
      break;




    case Event::DEFINITION_LIST_START:
      echo "<dl>" . PHP_EOL;
      break;

    case Event::DEFINITION_LIST_END:
      echo "</dl>" . PHP_EOL;
      break;

    case Event::DEFINITION_LIST_ROW_START:
      echo "";
      break;

    case Event::DEFINITION_LIST_ROW_END:
      echo "";
      break;

    case Event::DEFINITION_LIST_DEFINITION_START:
      echo "<dt>";
      break;

    case Event::DEFINITION_LIST_DEFINITION_END:
      echo "</dt>" . PHP_EOL;
      break;

    case Event::DEFINITION_LIST_DESCRIPTION_START:
      echo "<dd>";
      break;

    case Event::DEFINITION_LIST_DESCRIPTION_END:
      echo "</dd>" . PHP_EOL;
      break;

    case Event::ROW_START:
      echo "<tr>";
      break;
    case Event::ROW_END:
      echo "</tr>" . PHP_EOL;
      break;
      
    case Event::ENTRY_START:
      echo "<td>";
      break;
    case Event::ENTRY_END:
      echo "</td>";
      break;
      
    case Event::TABLE_START:
      echo "<table border=\"1\">" . PHP_EOL;
      break;
    case Event::TABLE_END:
      echo "</table>" . PHP_EOL;
      break;

    default:
      throw new Exception("unsupported event.type `{$event->type}` found.ちゃんとハンドルすれ");
  }
});

echo "</body></html>";