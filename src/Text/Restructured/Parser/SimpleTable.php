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
 * Simple Table Parser.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class SimpleTable extends Parser
{
  public $alias   = "table";
  protected $regexp  = "^(?P<data>[=-\s]+)$";

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->data = $match["data"];
    return $state;
  }
}