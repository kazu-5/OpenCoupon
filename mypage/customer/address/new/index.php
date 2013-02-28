<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Args
$action = $this->GetAction();

//  Data use to template.
$data = new Config();
$data->message = null;
$data->error   = null;

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form address
$form_config = $this->config()->form_address( $id );
$form_name   = $form_config->name;
$this->form()->AddForm($form_config);

//  Set data
$data->form_name   = $form_name;
$data->form_action = $this->ConvertURL('ctrl:/confirm');

//	Action			
switch( $action ){
	case 'index':
		$data->template = 'form/address.phtml';
		break;

	case 'confirm':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$data->template = 'form/confirm/address.phtml';
			$data->form_action = $this->ConvertURL("ctrl:/commit");
		}else{
			//  NG
			//$this->form()->debug($form_name);
			$data->template = 'form/address.phtml';
		}
		break;

	case 'commit':
		//  default template
		$data->template = 'form/address.phtml';
		if( $this->form()->Secure($form_name) ){
			//  OK
			$insert = $this->config()->insert_address( $id );
			$num = $this->pdo()->insert($insert);
			if( $num !== false ){
				//  Insert new address is successful!!
				$data->message  = "修正しました。";
				$data->template = 'commit.phtml';
				$data->seq_no   = $insert->set->seq_no;
				$this->form()->Clear($form_name);
			}else if( $num === 0 ){
				$data->message  = "既に修正してあります。";
			}else{
				$data->message  = "修正に失敗しました。";
			}
		}else{
			//  NG
			//$this->form()->debug($form_name);
		}
		break;
		
	default:
		$data->message  = "不正なアクセスです。($action)";
		$data->template = 'error.phtml';
		$this->mark("undefined action. ($action)");
}

$this->template('index.phtml',$data);
