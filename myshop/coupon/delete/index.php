
クーポン削除ページ
（リリースしてしまったクーポンは削除できなくすべき）

<?php
/* @var $this CouponApp */

//  URL Arguments
$args = $this->GetArgs();
$coupon_id = $args[0];
$action    = isset($args[1]) ? $args[1]: 'index';

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$config = $this->config()->form_coupon($shop_id,$coupon_id);
$this->form()->AddForm( $config );

//  Get form_name
$form_name = $config->name;
$data['coupon_id'] = $coupon_id;
$data['form_name'] = $form_name;

//  Do action
switch( $action ){
	case 'index':
	case 'confirm':
		$this->template('confirm.phtml',$data);
		break;

	case 'commit':
		if( $this->form()->Secure($form_name) ){

			//  Do Update
			$config = $this->config()->delete_coupon( $coupon_id, $form_name );
			$result = $this->pdo()->update($config);
			
			//  View result
			if( $result === false ){
				$args['message'] = 'Couponレコードの削除に失敗しました。';
			}else{
				$args['message'] = '削除しました。';
				$this->Location('app:/myshop/');
			}
		}
		
		//  
		$this->template('confirm.phtml',$data);
		break;

	default:
		$this->mark("undefined action=$action");
}

//  debug
//$this->form()->debug($form_name);
