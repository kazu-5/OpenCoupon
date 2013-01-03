<?php
/**
 * This is test use mock.
 * 
 * 
 * @author Tomoaki Nagahara
 *
 */
class Credit_model extends Model_model
{
	private $result = null;
	
	function __call($name, $args)
	{
		if( strtolower($name) == 'const' ){
			//var_dump($args);
			return $this->_const($args[0]);
		}else{
			return parent::__call($name, $args); 
		}
	}
	
	function Init()
	{
		$this->result = new Config();
	}
	
	function _const( $key )
	{
		/*
		if( $con = constant('self::'.$v) ){
			return $con;
		}else{
			$this->StackError("Does not define constant. ($v)");
		}
		*/
		
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
	
	function Auth( $config )
	{
		$this->result->io      = null;
		$this->result->status  = null;
		$this->result->message = null;
		$this->result->sid     = null;
		
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
			$this->result->io      = true;
			$this->result->status  = 'OK';
						
		}catch( Exception $e ){
			$this->result->io      = false;
			$this->result->status  = 'InputError';
			$this->result->message = $e->getMessage();
		}

		return $this->result;
	}
	
	function Commit($config)
	{
		$this->result->io      = null;
		$this->result->status  = null;
		$this->result->message = null;
		$this->result->sid     = null;
		
		try{
			//  check
			$this->CheckSid($config);

			//  result
			$this->result->io      = true;
			$this->result->status  = 'OK';
			
		}catch( Exception $e ){
			$this->result->io      = false;
			$this->result->status  = 'InputError';
			$this->result->message = $e->getMessage();
		}

		return $this->result;
	}
	
	function Cancel($config)
	{
		$this->result->io      = null;
		$this->result->status  = null;
		$this->result->message = null;
		$this->result->sid     = null;
		
		try{
			//  check
			$this->CheckSid($config);
			
			//  result
			$this->result->io      = true;
			$this->result->status  = 'OK';
			
		}catch( Exception $e ){
			$this->result->io      = false;
			$this->result->status  = 'InputError';
			$this->result->message = $e->getMessage();
		}
		
		return $this->result;
	}

	//==================================================================//
	
	private function CheckEmail( $config )
	{
		if(!isset($config->email)){
			throw new Exception('Does not set email.');
		}
	}
	
	private function CheckCardNo( $config )
	{
		$cardno = $config->cardno;
		$io = $cardno == $this->const('TEST_CARD_NO') ? true: false;
		$this->result->io = $io;
	}
	
	private function CheckCardExp( $config )
	{	
		//  get card exp
		$exp = $config->cardexp;
		preg_match('|([0-9]{2,4})[-/]?([0-9]{2,4})|',$exp,$match);
		//$this->d($match);
		
		//  check year
		if( strlen($match[1]) == 4 or strlen($match[1]) == 4 ){
			// OK
		}else{
			// NG
			throw new Exception('Does not match card expire. Examle:2014/01'); 
		}

		//  check double year
		if( strlen($match[1]) == 4 and strlen($match[1]) == 4 ){
			// OK
		}else{
			// NG
			throw new Exception('Does not match card expire. Examle:2014/01'); 
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
		return $this->result->sid = md5(date('Y-m-d'));
	}
	
	private function CheckSid( $config )
	{
		$sid = $this->GetSession('sid');
		
		//$this->mark( $sid );
		//$this->mark( $config->sid );
		
		//  check
		if( $sid !== $config->sid ){
			throw new Exception('Does not match SID.');
		}
	}
}
