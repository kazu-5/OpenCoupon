
お店情報の編集ページ

<?php
/* @var $this CouponApp */

//  Action
$action = $this->GetAction();
$this->mark("action: $action",'controller');

//  My shop ID.
$shop_id = $this->GetShopID();
$this->mark("shop_id: $shop_id",'controller');

//  data to form
$data = null;

//  Form
$config = $this->config()->form_shop($shop_id);
$this->form()->AddForm( $config );

//  form name
$form_name = $config->name;
$data['form_name'] = $form_name;
$this->mark("form_name: $form_name",'controller');

switch( $action ){
	case 'index':
		$this->template('form.phtml',$data);
		break;
		
	case 'confirm':
		if(!$this->form()->Secure($form_name) ){
			$args['message'] = '入力内容を確かめて下さい。';
			$this->template('form.phtml',$data);
		}else{
			$this->template('confirm.phtml',$data);
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure($form_name) ){
			
			//  Do Update
			$config = $this->config()->update_shop( $shop_id, $form_name );
			$result = $this->pdo()->update($config);
			
			//  View result
			if( $result === false ){
				$data['message'] = 'お店情報の更新に失敗しました。';
			}else{
				$data['message'] = 'お店情報を更新しました。';
			}
		}
		
		$this->template('form.phtml',$data);
		break;
}
