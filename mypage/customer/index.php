<?php
if( $this->form()->Secure('customer')  ){

	//customer_idを取得
	$config = $this->config()->select_my_customer();
	$t_coupon = $this->pdo()->select($config);
	
	$customer_id = $t_customer['customer_id'];
	
	//	t_customer編集
	$update = array();
	$update['table'] = 't_customer';
	// PKEY : customer_id
	//$update['set']['customer_id']	 = $customer_id;
	$update['where']['account_id'] = $account_id;
	//$update['set']['nickname']		 = $nickname;
	$update['set']['last_name']		 = $this->form->GetInputValue('last_name', 'customer');
	$update['set']['first_name']	 = $this->form->GetInputValue('first_name', 'customer');
	$update['set']['gender']		 = $this->form->GetInputValue('gender', 'customer');
	$update['set']['myarea']		 = $this->form->GetInputValue('myarea', 'customer');
	$year				= $this->form->GetInputValue('year', 'customer');
	$month				= $this->form->GetInputValue('month', 'customer');
	$day				= $this->form->GetInputValue('day', 'customer');
	$update['set']['birthday']		 = $year.'-'.$month.'-'.$day;
	$update['set']['address_seq_no'] = 1; //βリリースでは、住所が一つ
	//$update['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
	$this->mysql->update($update);


	//	t_address編集
	$update = array();
	$update['table'] = 't_address';
	//$update['set']['customer_id'] = $customer_id; //PKEY
	$update['where']['customer_id'] = $customer_id; //PKEY
	$update['set']['seq_no'] = 1; //PKEY
	$update['set']['postal_code'] = $this->form->GetInputValue('postal_code', 'customer');
	$update['set']['pref'] = $this->form->GetInputValue('pref', 'customer');
	$update['set']['city'] = $this->form->GetInputValue('city', 'customer');
	$update['set']['address'] = $this->form->GetInputValue('address', 'customer');
	$update['set']['building'] = $this->form->GetInputValue('building', 'customer');

	//$update['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
	$this->mysql->update($update);
}

$config = $this->config()->select_my_account();
$t_account = $this->pdo()->select($config);
/*
$select = array();
$select['table'] = 't_account';
$select['where']['id'] = $account_id;
$select['limit'] = 1;
$t_account = $this->mysql->select($select);
*/


$config = $this->config()->select_my_customer();
$t_customer = $this->pdo()->select($config);
/*
$select = array();
$select['table'] = 't_customer';
$select['where']['customer_id'] = $account_id;
$select['limit'] = 1;
$t_customer = $this->mysql->select($select);
*/
$customer_id = $t_customer['customer_id'];


$config = $this->config()->select_my_address();
$this->d( Toolbox::toArray($config) );
$t_address = $this->pdo()->select($config);
/*
$select = array();
$select['table'] = 't_address';
$select['where']['customer_id'] = $customer_id;
$select['where']['seq_no'] = 1; //β版は、住所が一つのため
$select['limit'] = 1;
$t_address = $this->mysql->select($select);
*/
//$address_id = $t_address['address_id'];
//$this->d($t_address);

//		$chip = $this->Enc('暗号化と暗号の復号化');
//		$this->mark('暗号化='.$chip);
//		$chip = $this->Dec($chip);
//		$this->mark('復号化='.$chip);

$mailaddr = $this->Dec($t_account['mailaddr']);
//$this->d($mailaddr);

$birthday = explode("-", $t_customer['birthday']);
$this->d($birthday);

/*
$birthday = $t_customer['birthday'][0].'年'.$t_customer['birthday'][1].'月'.$t_customer['birthday'][2].'日';
$this->d($birthday);
*/

//$this->d( Toolbox::toArray($t_account) );
$config = $this->config()->form_customer($t_customer);
//$this->d( Toolbox::toArray($config) );
$this->form()->AddForm($config);

/*
$this->form->InitInputValue('last_name',   $t_customer['last_name']);
$this->form->InitInputValue('first_name',  $t_customer['first_name']);
$this->form->InitInputValue('postal_code', $t_address['postal_code']);
$this->form->InitInputValue('pref',		$t_address['pref']);
$this->form->InitInputValue('city',        $t_address['city']);
$this->form->InitInputValue('address',     $t_address['address']);
$this->form->InitInputValue('building',    $t_address['building']);
$this->form->InitInputValue('myarea',      $t_customer['myarea']);
$this->form->InitInputValue('year',        $birthday[0]);
$this->form->InitInputValue('month',       $birthday[1]);
$this->form->InitInputValue('day',         $birthday[2]);
$this->form->InitInputValue('gender',      $t_customer['gender']);
*/
//		$this->d($_SESSION);

include('customer.phtml');

//メールアドレス変更処理
//mailaddr_change ➡ mailaddr_confirm ➡ mailaddr_commit
?>