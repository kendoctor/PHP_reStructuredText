<?php
namespace Text\Restructured;
/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * Parser Events.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Event{
  const SUBJECT_START = 1;
  const SUBJECT_END = 2;
  const TEXT = 3;

  const BLOQUOTE_START = 4;
  const BLOQUOTE_END = 5;

  const PARAGRAPH_START = 6;
  const PARAGRAPH_END = 7;

  const UNORDERED_LIST_START = 8;
  const UNORDERED_LIST_END = 9;

  const LIST_ITEM_START = 10;
  const LIST_ITEM_END = 11;
  // deplicated
  const BULLET_LIST_START = 10;
  const BULLET_LIST_END = 11;

  const CODE_START = 12;
  const CODE_END = 13;
  const COMMENT_START = 14;
  const COMMENT_END = 15;
  const COMMENT_DATA = 16;

  const HORIZON = 17;

  const DOCTEST_START = 18;
  const DOCTEST_DATA = 19;
  const DOCTEST_END = 20;

  const LINEBLOCK_START = 21;
  const LINEBLOCK_DATA = 22;
  const LINEBLOCK_END = 23;

  const FIELD_LIST_START = 24;
  const FIELD_LIST_DEFINITION_START = 25;
  const FIELD_LIST_DEFINITION_END = 26;
  const FIELD_LIST_DESCRIPTION_START = 27;
  const FIELD_LIST_DESCRIPTION_END = 28;
  const FIELD_LIST_END = 29;
  const FIELD_LIST_ROW_START = 30;
  const FIELD_LIST_ROW_END = 31;
  
  const OPTION_LIST_START = 32;
  const OPTION_LIST_ROW_START = 33;
  const OPTION_LIST_ROW_END = 34;
  const OPTION_LIST_DEFINITION_START = 35;
  const OPTION_LIST_DEFINITION_END = 36;
  const OPTION_LIST_DESCRIPTION_START = 37;
  const OPTION_LIST_DESCRIPTION_END = 38;
  const OPTION_LIST_ARGUMENT_START = 39;
  const OPTION_LIST_ARGUMENT_END = 40;
  const OPTION_LIST_END = 41;
  
  const DEFINITION_LIST_START = 42;
  const DEFINITION_LIST_DEFINITION_START = 43;
  const DEFINITION_LIST_DEFINITION_END = 44;
  const DEFINITION_LIST_DESCRIPTION_START = 45;
  const DEFINITION_LIST_DESCRIPTION_END = 46;
  const DEFINITION_LIST_ROW_START = 47;
  const DEFINITION_LIST_ROW_END = 48;
  const DEFINITION_LIST_END = 49;
  
  const ORDERED_LIST_START = 50;
  const ORDERED_LIST_END = 51;

  public $type = 0;
  public $data = "";
  public $prefix = "";
}