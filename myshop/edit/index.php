
お店情報の編集ページ

<?php
/* @var $this CouponApp */

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$config = $this->config()->form_shop($shop_id);
$this->form()->AddForm( $config );

//  Action
$action = $this->GetAction();
$this->mark($action,'controller');

switch( $action ){
	case 'index':
		$this->template('form.phtml');
		break;
		
	case 'confirm':
		if(!$this->form()->Secure('form_coupon') ){
			$args['message'] = '入力内容を確かめて下さい。';
			$this->template('form.phtml',$args);
		}else{
			$this->template('confirm.phtml');
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure('form_coupon') ){
			
			//  Do Insert
			$config = $this->config()->insert_shop($shop_id);
			$result = $this->pdo()->insert($config);
			
			//  View result
			if( $result === false ){
				$args['message'] = '新規レコードの作成に失敗しました。';
			}else{
				$args['message'] = '新規クーポンを作成しました。';
			}
		}else{
			$args = null;
		}
		
		$this->template('form.phtml',$args);
		break;
}
