<?php
namespace Text\Restructured\State;

/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * Doctest State.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Doctest extends State
{
  public $alias   = "doctest";
  protected $regexp  = "^(?<prefix>>>>\s)(?<data>.+)$";

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->data = $match["data"];
    $state->prefix = trim($match["prefix"]);

    return $state;
  }
}