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
 * Parser interface.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
interface IParser{
  public function getState();
  public function match($token);
}
