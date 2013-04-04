<?php

class Module_Transfer extends Model_Model
{
	/**
	 * Return to the forward URL.
	 * 
	 */
	function Forward( Config $config=null )
	{
		//  Get
		$url = $this->GetSession('transfer_url');
		
		//  Reset
		$this->SetSession('transfer_url',null);
		
		//  Check
		if( empty($url) ){
			$url = $this->ConvertURL('app:/');
			$this->StackError("Empty forward URL.");
		}
		
		//  Do
		$this->_Behavior( $url, $config );
	}
	
	/**
	 * Transfer
	 * 
	 * @param  string $url    Transfer URL
	 * @param  Config $config Template-file-name, count, class, message
	 * @return boolean
	 */
	function Set( $url, Config $config=null )
	{
		$transfer_url = $this->GetURL('url');
		$this->SetSession( 'transfer_url', $transfer_url );
		
		if( empty($url) ){
			$this->StackError("URL is emtpy.");
			return false;
		}

		$this->_Behavior( $url, $config );
	}
	
	private function _Behavior( $url, Config $config=null )
	{
		//  Init
		if( empty($config) ){
			$config = new Config();
		}
		
		//  Set URL
		$config->url = $this->ConvertURL($url);
		
		//  Set count
		$config->count = isset($config->count) ? (int)$config->count: 5;
		
		//  Get Template path
		$template = isset($config->template) ? $config->template: dirname(__FILE__).'/'.'transfer.phtml';
		
		//  Execute
		$this->template( $template, $config );
	}
}
