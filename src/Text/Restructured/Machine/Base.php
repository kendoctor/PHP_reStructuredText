<?php
namespace Text\Restructured\Machine;
/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * Basic StateMachine.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
abstract class Base
{
  public $state = 0;
  protected $handler;

  public function get_handler()
  {
    return $this->handler;
  }

  public function register_handler(\Closure $closure)
  {
    $this->handler = $closure;
  }

  public function notify($event,$option=null)
  {
    $cls = $this->handler;
    $ev = new \Text\Restructured\Event();
    $ev->type = $event;
    $ev->data = $option;

    $cls($ev);
  }
  
  abstract public function execute(\Text\Restructured\TokenStream &$input,$level = 0);
}