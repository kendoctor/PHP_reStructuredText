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
 * Basic state class.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
abstract class State implements IState
{
  //deplicated
  const UNDEFINED   = 0;
  const INITIALIZED = 1;
  const CONTINUOUS  = 2;
  const FINISHED    = 3;
  const REJECT      = 4;
  
  public $alias;
  protected $regexp;
  public $data;
  
  public function getState()
  {
    return $this->state;
  }

  public function register(RestructuredText &$rest)
  {
    $rest->register($this);
  }

  public function match($token)
  {
    if(preg_match("/" . $this->regexp . "/sm",$token,$match)){
      $state = $this->process($token,$match);
      $state->line = $token;
      return $state;
    }

    return false;
  }
  
  public function getAlias()
  {
    return $this->alias;
  }

  abstract protected function process($token, Array $match);
}
