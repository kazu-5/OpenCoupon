
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
		if(!$this->form()->Secure('form_shop') ){
			$this->template('form.phtml');
		}else{
			$this->template('confirm.phtml');
		}
		break;
		
	case 'commit':
		if(!$this->form()->Secure('form_shop') ){
			$this->template('form.phtml');
		}else{
			
			//  Do update
			$update = $this->config()->update_shop($shop_id);
			$result = $this->pdo()->update($update);
			
			//  View result
			if( $result !== false ){
				$this->template('form.phtml',array('message'=>'更新しました'));
			}else{
				$this->template('error-update.phtml');
			}
		}
		break;
}
