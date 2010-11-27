Text\RestructuredText
=====================

Text\Restructured はpure PHPで書かれたreStructuredTextのパーサです。

:author: chobi_e <http://twitter.com/chobi_e>
:created_at: 2010/11/27
:current status: alpha development
:licence: MIT Licence
:required: PHP5.3 higher

このライブラリはレンダラがないので、実際に使うときは自分でレンダラを
作成してください。

EXPERIMENTAL
+++++++++++++++++++++++++

このライブラリは実験的な実装なので内容がゴッソリ変わる可能性があります


Purpose
+++++++++++++++++++++++++

reStructuredTextは非常に書きやすいドキュメントですが、現状PHPでのreStructuredTextを解析する
ライブラリがなく、色々とカスタマイズが面倒だったので作成しました。

主にgithubで開発をしていくので、開発に参加したい方はガンガンpullリクエストを送ってください。


※作者はテキストの意味付けとか得意ではないので構造自体が間違っている可能性があります。

このライブラリの使い方
---------------------

./testディレクトリをみるべし

* Attention:  鋭意開発版なので内部仕様やAPIが変わる可能性があります。

simple parser::

  $s = new Text\Restructured\Loader\FileLoader(__DIR__ . "/data.txt");

  $r = new Text\Restructured\Restructured($s, new Text\Restructured\StateMachine());
  $r->registerState(array(
    new Text\Restructured\State\Line(),
    new Text\Restructured\State\Horizon(),
    new Text\Restructured\State\Indent(),
    new Text\Restructured\State\Doctest(),
    new Text\Restructured\State\Comment(),
    new Text\Restructured\State\BulletList(),
    new Text\Restructured\State\Text()
  ));

  use Text\Restructured\Event;

  $r->parse(function($event){
    // handle parser event.
    // イベントはText/Restructured/Event.phpで定義されています
    echo $event->type . PHP_EOL;
  });

Supported restructured text format
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

今のところの対応状況はこんな感じ::


  [*]章立ての構造
    =======
    タイトル
    =======
    
    サブタイトル
    -----------
    
  [*]段落
    テキスト

  [*]記号付きリスト
    - something
      lorem ipsum dolar sit amet

    * anything

  
  [-]番号付きリスト(記号付きリストと本質が同じなのでそちらの最適化をしたら。)
    1. something
        lorem ipsum dolar sit amet

  [-]autoincrementリスト(記号付きリストと本質が同じなのでそちらの最適化をしたら。)
    #. moemoe
    
  [*]定義リスト
    左揃えテキスト
      インデントテキスト（空行なし）

  [*]整形済みブロック(一部)
    ::
    
      インデントおわりまで
   |
    something ::
    
      インデント終わりまで（上記の場合はコロンがひとつになる）
   |
    > text

  [*]ラインブロック
    | 装飾の不要なリスト
      あ、インデントの対応やってねーや

  [*]引用
    インデントするだけ
    ネスト対応まだできてないと思う

  [*]Doctestブロック
    >>> something
  
  [*]区切り線
    ------------

  [*]フィールドリスト
    :definition: (description)?
      description

  [-]オプションリスト(手抜き)
    -a            command-line option "a"
    -b file       options can have arguments
                  and long descriptions
    --long        options can be long also
    --input=file  long options can also have arguments
    /V            DOS/VMS-style options too

  [-] 拡張系の実装
    [*]comment
      但し空コメントの後のブロックはコメントとして許容されます
    []image

  Unsupported
  ===========

  []Table（気が向いたら）
    ・Grid Table
    ・Simple Table


To do
----------------------

- Token化するクラスとStateを判断するクラスの改善

  現状Tokenが行頭か行末なのかが判断できないのでそこらへんでなんか問題があった気がする

- 各種Machineを綺麗にする

  適当実装なので大きくなる前に片付けたい

- 有限オートマトンの状態表の作成

  フィーリングで作っているのできちんと状態表を作って実装する

- InlineParserの実装

  InlineParserは別なような気がするんだけど、そのうち実装したい。
  多分同じようにイベントをハンドリングする形になると思う。

- Testの実装

  もうちょい仕様確定したらTestつくる

- 仕様の作成

  仕様ないと他の人が拡張しづらいので