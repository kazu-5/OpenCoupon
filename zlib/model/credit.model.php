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
	const TEST_CARD_NO = '1234567890123456';
	
	private $result = null;
	
	function Init()
	{
		$this->result = new Config();
	}
	
	function Auth( $config )
	{
		$result = new Config();
		
		try{
			//  check
			$this->CheckCardNo($config);
			$this->CheckCardExp($config);
			$this->CheckAmount($config);
			
			//  create sid
			$sid = $this->CreateSid($config);
			
			//  save session
			$this->SetSession('sid',$sid);
			
		}catch( Exception $e ){
			//return $this->result;
		}
		
		return $this->result;
	}
	
	function Commit($config)
	{
		$result = new Config();
		
		try{
			$this->CheckSid();
		}catch( Exception $e ){
			//return $this->result;
		}

		return $this->result;
	}
	
	function Cancel()
	{
		$result = new Config();
		
		try{
			$this->CheckSid();
		}catch( Exception $e ){
			//return $this->result;
		}
		
		return $this->result;
	}
	
	private function CheckCardNo()
	{
		
	}
	
	private function CheckCardExp()
	{
		
	}
	
	private function CheckAmount()
	{
		
	}
	
	private function CreateSid()
	{
		
	}
	
	private function CheckSid()
	{
		
	}
}
