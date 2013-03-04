<?php

class Module_Transfer extends Model_Model
{
	function test()
	{
		$this->p("test");
	}
	
	function Get()
	{
		
	}
	
	function Set( $url, $config=null )
	{
		if( empty($url) ){
			$this->StackError("URL is emtpy.");
			return false;
		}
		
		if( empty($config) ){
			$config = new Config();
		}
		
		//  Set URL
		$config->url = $this->ConvertURL($url);
		
		//  Set count
		$config->count = isset($config->count) ? (int)$config->count: 5;
		
		//  Get Template path
		$template = isset($config->template) ? $config->template: dirname(__FILE__).'/'.'transfer.phtml';
		$this->mark($template);
		
		//  Execute
		$this->template($template,$config);
	}
}
