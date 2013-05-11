<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Init data
$data = new Config();

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$config = $this->config()->form_customer($id);
$this->form()->AddForm($config);

//	Action
switch( $action ){
	case 'index':
		if( $this->form()->Secure('form_customer') ){
			//登録情報変更処理
			
			
			$data->message = '変更しました';
		}
		//登録情報表示
		//  Get t_address by account_id
		$select = $this->config()->select_address($id);
		$data->t_address = $this->pdo()->Select($select);
		$this->template('index.phtml',$data);
		break;
			
	default:
		$this->mark("undefined action. ($action)");
}
