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
 * BulletList State.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class BulletList extends Parser
{
  public $alias   = "list";
  protected $regexp  = "^(?<prefix>[*-]\s)(?<data>.+)$";

  public $allow  = array();

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->data = $match["data"];
    return $state;
  }
}