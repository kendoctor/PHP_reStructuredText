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

require_once $vendor_dir . "/Text/Restructured/State/IState.php";
require_once $vendor_dir . "/Text/Restructured/State/State.php";
require_once $vendor_dir . "/Text/Restructured/State/Comment.php";
require_once $vendor_dir . "/Text/Restructured/State/Horizon.php";
require_once $vendor_dir . "/Text/Restructured/State/Indent.php";
require_once $vendor_dir . "/Text/Restructured/State/Line.php";
require_once $vendor_dir . "/Text/Restructured/State/BulletList.php";
require_once $vendor_dir . "/Text/Restructured/State/Text.php";
require_once $vendor_dir . "/Text/Restructured/State/Doctest.php";
require_once $vendor_dir . "/Text/Restructured/State/LineBlock.php";
require_once $vendor_dir . "/Text/Restructured/State/FieldList.php";
require_once $vendor_dir . "/Text/Restructured/State/OptionList.php";
require_once $vendor_dir . "/Text/Restructured/State/Code.php";


$states = array(
  new Text\Restructured\State\Line(),
  new Text\Restructured\State\Horizon(),
  new Text\Restructured\State\Indent(),
  new Text\Restructured\State\LineBlock(),
  new Text\Restructured\State\Doctest(),
  new Text\Restructured\State\Comment(),
  new Text\Restructured\State\FieldList(),
  new Text\Restructured\State\BulletList(),
  new Text\Restructured\State\OptionList(),
  new Text\Restructured\State\Code(),
  new Text\Restructured\State\Text()
);