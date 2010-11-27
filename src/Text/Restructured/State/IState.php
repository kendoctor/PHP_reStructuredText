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
 * State interface.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
interface IState{
  public function getState();
  public function match($token);
}
