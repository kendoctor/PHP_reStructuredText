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
 * reStructuredText Parser.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class NumberedList extends Parser
{
  public $alias   = "numbered_list";
  protected $regexp  = "^(?<prefix>[1-9][0-9]*\.\s)(?<data>.+)$";

  public $allow  = array();

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->prefix = $match["prefix"];
    $state->data = $match["data"];
    return $state;
  }
}