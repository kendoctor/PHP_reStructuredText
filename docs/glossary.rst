Glossary
========================================

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

その他
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
:literal_block: xml:space="preserve"でコメントブロック
:doctest_block: xml:space="preserve"でdoctestブロック