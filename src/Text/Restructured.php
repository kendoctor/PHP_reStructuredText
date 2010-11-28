<?php
namespace Text\Restructured;

/*
 * This file is part of the chobi_e's reStructuredText package.
 *
 * (c) chobi_e <http://twitter.com/chobi_e>
 *
 * For the full copyright and licence information, please view the LICENCE
 * file that was distributed with this source code.
 */

/**
 * reStructuredText Parser.
 *
 * @author chobi_e <http://twitter.com/chobi_e>
 */
class Restructured
{
  protected $machine;
  protected $stream;
  protected $states = array();

  public function __construct(Loader\FileLoader $reader,$machine)
  {
    $this->stream = $reader;
    $this->machine = $machine;
  }
  
  public function registerStream($reader)
  {
    $this->stream = $reader;
  }
  
  public function registerState(Array $array)
  {
    foreach($array as $state)
    {
      $this->states[] = $state;
    }
  }

  public function process($token)
  {
    foreach($this->states as $sm)
    {
      if($state = $sm->match($token)){
        return $state;
      }
    }
  }
  
  public function parse(\Closure $closure)
  {
    $stack = array();
    $result = array();
    $machine = $this->machine;

    $machine->root_parser = $this;
    $machine->register_handler($closure);
    
    $ar  = new TokenStream();
    
    //Todo: LoaderがToken返すっておかしいのであとで治す
    while($token = $this->stream->getToken())
    {
      $state = $this->process($token);
      $ar->append($state);
    }

    if($token === false){
      $ar->append((object)array("alias"=>"eos","data"=>"","line"=>""));
    }
    
    $machine->execute($ar);
  }
}