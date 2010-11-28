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
 * Indent State.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Indent extends Parser
{
  public $alias = "indent";
  protected $regexp = "^(?<indent>[ \t]+)";
  
  protected function process($token, Array $match)
  {
    $state = clone $this;
    if(isset($match["indent"])){
      $state->data = $match["indent"];
    }

    return $state;
  }
}