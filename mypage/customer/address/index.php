<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Args
$args = $this->GetArgs();
$seq_no = isset($args[0]) ? $args[0]: null;
$action = isset($args[1]) ? $args[1]: 'index';

//  Data use to template.
$data = new Config();

//  Check seq_no
if(!$seq_no){
	$data->message = '順番号が指定されていません。';
	$data->template = 'error.phtml';
	$this->template('index.phtml',$data);
	return;
}else if(!is_numeric($seq_no)){
	$data->message = '順番号ではありません。';
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
$data->message     = null;

//	Action
switch( $action ){
	case 'index':
		$data->template = 'form.phtml';
		break;

	case 'confirm':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$data->template = 'confirm.phtml';
			$data->form_action = $this->ConvertURL("ctrl:/$seq_no/commit");
		}else{
			//  NG
			//$this->form()->debug($form_name);
			$data->template = 'form.phtml';
		}
		break;

	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$update = $this->config()->update_address( $id, $seq_no );
			$data->message  = "更新しました。";
		}else{
			//  NG
			//$this->form()->debug($form_name);
		}
		$data->template = 'form.phtml';
		break;
		
	default:
		$data->message  = "不正なアクセスです。($action)";
		$data->template = 'error.phtml';
		$this->mark("undefined action. ($action)");
}

$this->template('index.phtml',$data);
