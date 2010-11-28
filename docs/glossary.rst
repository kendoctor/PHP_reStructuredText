Glossary
========================================


Subject -> Sectionに変更
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
:title: hnタグの事。一番最初にでてきたものはタイトルとして扱われる。
:section: ids,namesパラメーターを取る。h2以降。titleを子供に持つ
          なぜかsectionはsectionの子供を持つ(´・ω・｀)

DocumentTree関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
:document: sourceをパラメーターに取る。ドキュメントツリー

List関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

:bullet_list: <ul>タグの事。オプションbulletでリストスタイルを指定
:paragraph: パラグラフ
:list_item: <li>タグの事
:enumerated_list: <ol>タグの事。enumtypeで数字の順番,prefix,suffixを受け取る


FieldList関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

:docinfo:    FieldListの開始/終了
:field:      定義部分の開始/終了
:field_name: 定義部分のデータ
:field_body: 説明部分。子供にparagraphをもつ。

OptionList関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

:option_list:       オプションリストの開始/終了
:option_list_item:  オプションリストの行の開始/終了
:option_group:      -b valueの部分
:option:            option_string,option_argumentを子にもつ
:option_string:     -bの部分
:option_argument:   delimiterを受け取る。value の部分
:description:       オプションの説明部分。paragraphをもつ。

DefinitionList関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

:definition_list: definition_listの開始/終了
:definition_list_item: definition_list_itemの開始、終了
:term: 定義。dtタグのこと。
:definition: 説明部分。ddタグの事。中はparagraphで囲まれる。

Line関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

:line_block: ラインの開始/終了
:line:       ラインブロック中の行

Table関連
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
:table:   tableの開始/終了
:tgroup:  cols パラメーターをとる。全体の列数
:colspec: colwidthで列の幅を覚え特
:thead:   そのなの通り
:tbody:   そのなの通り
:row:     行の開始/終了
:entry:   オプションでmorecolsをとる。morecols=1 = colspan=2
          morerowsも同様

こんな感じ::

  table
    tgroup cos=3
      colspec colwidth=n
      colspec colwidth=n
      colspec colwidth=n
      thead
        row
          entry (morecols)
          entry
          entry
            paragraph
      tbody
        ...


GridTableもSimpleTableも構造は一緒


Horizon -> Transition に変更
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
:transition: 何も情報もってないのね(´・ω・｀)

transitionは連続して続けられない。ドキュメントの最初、最後にtransitionを置くことはできない


その他
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
:literal_block: xml:space="preserve"でコメントブロック
:doctest_block: xml:space="preserve"でdoctestブロック
:bloquote: paragraphを子供に持つ