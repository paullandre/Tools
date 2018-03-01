<?php

class variables
{
	public $publicVar = "public";
	private $privateVar = "private";	
	
	public function getPublic()
	{
		return $this->privateVar;
	}
}

class checker
{
	public function echoPublic()
	{
		$var = new variables();
		$vars = $var->getPublic();
		
		echo $var->publicVar . "<br />"; 
		
		return $vars;
	}	
}

$checker = new checker();
echo $checker->echoPublic();
?>