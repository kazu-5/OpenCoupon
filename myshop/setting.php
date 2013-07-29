<?php
/* @var $this CouponApp */

//  Change layout
$this->SetLayoutName('myshop');

//  Check Login
if( !$id = $this->model('Login')->GetLoginID() ){
	$this->Location('app:/login');
}

//  Check Shop ID
if(!$shop_id = $this->GetSession('myshop_id') ){
	
	//  Get route info
	$route = $this->GetEnv('route');
	//$this->d( $route );
	
	//  Error transfer.
	if( $route['path'] === '/myshop/error' ){
		return;
	}
	
	//  Fetch record (SELECT)
	$config = $this->config()->select_customer($id);
	$record = $this->pdo()->select( $config );
	
	//  Get Shop ID
	$shop_id = $record['shop_id'];

	//  Get Shop flag
	$shop_flag = $record['shop_flag'];
	
	//  Check shop_flag
	if( $shop_flag ){
		//  OK
	}else{
		//  NG
		$this->Location('app:/myshop/error/shop_flag');
	}

	//  Check shop_id
	if( $shop_id ){
		//  OK (Save session)
		$this->SetSession('myshop_id',$shop_id);
	}else{
		//  NG
		$this->Location('app:/myshop/error/shop_id');
	}
}

//$this->template('breadcrumb.phtml');
