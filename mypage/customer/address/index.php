<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Args
$args = $this->GetArgs();
$seq_no = isset($args[0]) ? $args[0]: null;
$action = isset($args[1]) ? $args[1]: 'index';

//  Data use to template.
$data = new Config();
$data->message  = null;
$data->template = 'form/address.phtml';

//  Check seq_no
if(!$seq_no){
	$data->error    = '順番号が指定されていません。';
	$data->template = 'error.phtml';
	$this->template('index.phtml',$data);
	return;
}else if(!is_numeric($seq_no)){
	$data->error    = '順番号ではありません。';
	$data->template = 'error.phtml';
	$this->template('index.phtml',$data);
	return;
}

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form address
$form_config = $this->config()->form_address( $id, $seq_no );
$form_name   = $form_config->name;
$this->form()->AddForm($form_config);

//  Set data
$data->form_name   = $form_name;
$data->form_action = $this->ConvertURL("ctrl:/$seq_no/confirm");

//	Action
switch( $action ){
	case 'index':
		break;

	case 'confirm':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$data->template = 'form/confirm/address.phtml';
			$data->form_action = $this->ConvertURL("ctrl:/$seq_no/commit");
		}else{
			//  NG
			//$this->form()->debug($form_name);
			$data->template = 'form/address.phtml';
		}
		break;

	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$update = $this->config()->update_address( $id, $seq_no );
			if( $num = $this->pdo()->update($update) ){
				$data->message  = "修正しました。";
			}else if( $num === 0 ){
				$data->message  = "既に修正してあります。";
			}else{
				$data->message  = "修正に失敗しました。";
			}
		}else{
			//  NG
			//$this->form()->debug($form_name);
		}
		$data->template = 'form/address.phtml';
		break;
		
	default:
		$data->message  = "不正なアクセスです。($action)";
		$data->template = 'error.phtml';
		$this->mark("undefined action. ($action)");
}

$this->template('index.phtml',$data);
