<?php
namespace Text\Restructured\Parser;

/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * FieldList Parser.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class FieldList extends Parser
{
  public $alias   = "field_list";
  protected $regexp  = "^:(?P<data>.+?):(\s(?P<description>.+))?$";

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->data = $match["data"];

    if(isset($match["description"])){
      $state->option = trim($match["description"]);
    }else{
      $state->option = null;
    }

    return $state;
  }
}