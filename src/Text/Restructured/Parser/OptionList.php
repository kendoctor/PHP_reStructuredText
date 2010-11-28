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
 * OptionList State.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class OptionList extends Parser
{
  public $alias   = "option_list";

  //一発で分解するのは難しいのでとりあえず当てるだけにする
  protected $regexp  = "^(?P<prefix>(-[a-zA-Z0-9])|\/[a-zA-Z0-9]|--[a-zA-Z0-9][a-zA-Z0-9_=-]*)\s+(?P<data>.+)$";

  protected function process($token, Array $match)
  {
    $state = clone $this;
    $state->option = $match["prefix"];
    $state->data = $match["data"];
    
    return $state;
  }
}