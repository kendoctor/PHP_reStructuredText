======================================================================
ネスト可能なMachineの書き方
======================================================================

reStructuredTextの幾つかの記法ではネストが可能ということになっています。
再帰的に処理を行うようなMachineの書き方としては次のような点に注意
する必要があります。

Machine起動時のインデントレベルと現在のインデントレベルに合った処理
######################################################################

再帰的に処理を行うには、いつ親のMachineに制御を返すかを記述しなければなりません。

:レベルが同じ: Machineの先頭にもどして処理を再度行う
:レベルが高い: 新しいMachineを生成して処理を委ねる。
:レベルが低い: Tokenを一つback()させて親に処理を返す。

それでは実際のコードを見てみましょう::

    }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel > $init_level){
      //再帰的に処理をさせる

      $machine = new self();
      $machine->register_handler($this->get_handler());
      $input->back();
      $machine->execute($input,$mylevel);
      unset($machine);
    }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel < $init_level){
      //レベルが起動時より下がっているので処理を親に返す。　

      $this->notify(Event::BLOCKQUOTE_END);
      $input->back();
      return;
    }else if($previous->alias == "line" && $current->alias == "indent" && $mylevel == $init_level){
      //レベルが同じなので再度Machineの先頭から処理をさせる。
    }
    //通常の処理

Todo: ここらへんの処理は共通化出来そうなので何かしらのインターフェースを考えたいところですね。