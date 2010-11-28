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
 * Horizion state.
 *
 * Attention: This class will rename to Text\Restructured\Parser\Transition
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Horizon extends Parser
{
  public $alias   = "horizon";
  protected $regexp  = "^(?<data>[=:'\"~^_*+\#<>`-]{4,})(\r\n|\r|\n)$";

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->data = $match["data"];
    
    return $state;
  }
}