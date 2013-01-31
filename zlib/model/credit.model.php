<?php
/**
 * This is test use mock.
 * 
 * 
 * @author Tomoaki Nagahara
 *
 */
class Model_credit extends Model_Model
{
	function __call($name, $args)
	{
		if( strtolower($name) == 'const' ){
			//var_dump($args);
			return $this->_const($args[0]);
		}else{
			return parent::__call($name, $args); 
		}
	}
	
	function _const( $key )
	{		
		switch( $key ){
			case 'TEST_CARD_NO':
				$var = '1234567890123456';
				break;
			
			case 'AMOUNT_MIN':
				$var = 100;
				break;

			case 'AMOUNT_MAX':
				$var = 10000;
				break;
				
			default:
				$this->mark("![ .red [Does not define constant. ($key)]]");
				$var = null;
		}
		
		return $var;
	}
	
	function Test()
	{
		$this->p('![ .bold [OK]]');
	}
	
	function Auth( &$config )
	{
		$result = new Config();
		$result->io      = null;
		$result->status  = null;
		$result->message = null;
		$result->sid     = null;
		
		try{
			//  check
			$this->CheckEmail($config);
			$this->CheckCardNo($config);
			$this->CheckCardExp($config);
			$this->CheckAmount($config);
			
			//  create sid
			$sid = $this->CreateSid($config);
			
			//  save session
			$this->SetSession('sid',$sid);

			//  result
			$result->io      = true;
			$result->status  = 'OK';
			$result->sid	 = $sid;
			
		}catch( Exception $e ){
			$result->io      = false;
			$result->status  = 'InputError';
			$result->message = $e->getMessage();
		}
		
		$config->merge($result);

		return true;
	}
	
	function Commit( &$config )
	{
		$result = new Config();
		$result->io      = null;
		$result->status  = null;
		$result->message = null;
		
		try{
			//  check
			$this->CheckSid($config);

			//  result
			$result->io      = true;
			$result->status  = 'OK';
			
		}catch( Exception $e ){
			$result->io      = false;
			$result->status  = 'InputError';
			$result->message = $e->getMessage();
		}

		$config->merge($result);
		
		return $result;
	}
	
	function Cancel($config)
	{
		$result = new Config();
		$result->io      = null;
		$result->status  = null;
		$result->message = null;
		$result->sid     = null;
		
		try{
			//  check
			$this->CheckSid($config);
			
			//  result
			$result->io      = true;
			$result->status  = 'OK';
			
		}catch( Exception $e ){
			$result->io      = false;
			$result->status  = 'InputError';
			$result->message = $e->getMessage();
		}
		
		return $result;
	}

	//==================================================================//
	
	private function CheckEmail( $config )
	{
		if( empty($config->email) ){
			throw new Exception('Does not set email.');
		}
	}
	
	private function CheckCardNo( $config )
	{
		$cardno = $config->cardno;
		$io = $cardno == $this->const('TEST_CARD_NO') ? true: false;
		return $io;
	}
	
	private function CheckCardExp( $config )
	{	
		//  Get card exp
		$exp = $config->cardexp;
		preg_match('|([0-9]{2,4})[-/]?([0-9]{2,4})|',$exp,$match);
		//$this->d($match);
		
		$error = "Does not match card expire($exp). Examle:2018/01";
		
		//  Check year
		if( strlen($match[1]) == 4 or strlen($match[2]) == 4 ){
			// OK
		}else{
			// NG
			throw new Exception($error); 
		}

		//  Check both year
		if( strlen($match[1]) == 4 and strlen($match[2]) == 4 ){
			// NG
			throw new Exception($error); 
		}
		
		//  get
		if( strlen($match[1]) == 4 ){
			$yy = $match[1];
			$mm = $match[2];
		}else{
			$yy = $match[2];
			$mm = $match[1];
		}
		
		// check
		if( $yy >= date('Y') and $mm >= date('m') ){
			//  OK
		}else{
			//  NG
			throw new Exception('Card is expire.');
		}
	}
	
	private function CheckAmount( $config )
	{
		if( $config->amount < $this->_const('AMOUNT_MIN') ){
			throw new Exception('Amount is small.');
		}
		
		if( $config->amount > $this->_const('AMOUNT_MAX') ){
			throw new Exception('Amount is large.');
		}
	}
	
	private function CreateSid( $config )
	{
		return md5(date('Y-m-d'));
	}
	
	private function CheckSid( $config )
	{
		$sid = $this->GetSession('sid');
		
		//  check
		if( $sid !== $config->sid ){
			throw new Exception('Does not match SID.');
		}
	}
}
