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
 * Comment state.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Comment extends State
{
  public $alias = "comment";
  protected $regexp = "^(?<prefix>\.\.)(?<data>\s.+)?$";
  
  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->data = $match["data"];
    return $state;
  }
}