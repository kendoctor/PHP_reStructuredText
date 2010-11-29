========================================
インデントによる継続と継続行の処理について
========================================

reStructuredText parserでは直前の行が何かしらの意味を持っている場合に
インデントを行うことで継続したテキストであると判断されます::

  | [a]lineblock text
    [b]continuous lineblock1
    [c]continuous lineblock2

継続した行が改行を含まなければ一つのテキストの塊であると判断されます。
上記の場合は::

  [line_block]
    [a]lineblock text\n[b]continuous lineblock1\n[c]continuous lineblock2

というように解釈されます。

継続した行の後で改行が含まれる場合はその後のテキストはreStructuredText Parser
で再帰的に解析されるように実装する必要があります::

  | [a]lineblock text
    [b]continuous lineblock1

    [c]continuous lineblock2
    [d]continuous lineblock2


この場合、c,dはreStructuredText Parserで再帰的に処理されるために一度テキストデータを
ライブロックが終了するまで貯めて、新しくTokenに分けてreStructuredText Parserで処理を行ないます。
因みにa,bはlineblockの最初のパラグラフである、というだけです。