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
 * catchup state.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Text extends State
{
  public $alias   = "text";
  protected $regexp  = "^(?<data>.+)$";

  protected function process($token, Array $match)
  {
    $state = clone $this;
    if(isset($match["data"])){
      $state->data = $match["data"];
    }

    return $state;
  }
}
