<?php
$vendor_dir = __DIR__ . "/../src";
require_once $vendor_dir . "/Text/Restructured.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Base.php";
require_once $vendor_dir . "/Text/Restructured/Loader/FileLoader.php";
require_once $vendor_dir . "/Text/Restructured/TokenStream.php";
require_once $vendor_dir . "/Text/Restructured/StateMachine.php";
require_once $vendor_dir . "/Text/Restructured/Event.php";

require_once $vendor_dir . "/Text/Restructured/Machine/Bloquote.php";
require_once $vendor_dir . "/Text/Restructured/Machine/BulletList.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Code.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Comment.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Horizon.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Paragraph.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Doctest.php";
require_once $vendor_dir . "/Text/Restructured/Machine/LineBlock.php";
require_once $vendor_dir . "/Text/Restructured/Machine/FieldList.php";
require_once $vendor_dir . "/Text/Restructured/Machine/OptionList.php";
require_once $vendor_dir . "/Text/Restructured/Machine/DefinitionList.php";
require_once $vendor_dir . "/Text/Restructured/Machine/Table.php";

require_once $vendor_dir . "/Text/Restructured/Parser/IState.php";
require_once $vendor_dir . "/Text/Restructured/Parser/State.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Comment.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Horizon.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Indent.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Line.php";
require_once $vendor_dir . "/Text/Restructured/Parser/BulletList.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Text.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Doctest.php";
require_once $vendor_dir . "/Text/Restructured/Parser/LineBlock.php";
require_once $vendor_dir . "/Text/Restructured/Parser/FieldList.php";
require_once $vendor_dir . "/Text/Restructured/Parser/OptionList.php";
require_once $vendor_dir . "/Text/Restructured/Parser/SimpleTable.php";
require_once $vendor_dir . "/Text/Restructured/Parser/Code.php";


$states = array(
  new Text\Restructured\Parser\Line(),
  new Text\Restructured\Parser\Horizon(),
  new Text\Restructured\Parser\SimpleTable(),
  new Text\Restructured\Parser\Indent(),
  new Text\Restructured\Parser\LineBlock(),
  new Text\Restructured\Parser\Doctest(),
  new Text\Restructured\Parser\Comment(),
  new Text\Restructured\Parser\FieldList(),
  new Text\Restructured\Parser\BulletList(),
  new Text\Restructured\Parser\OptionList(),
  new Text\Restructured\Parser\Code(),
  new Text\Restructured\Parser\Text()
);