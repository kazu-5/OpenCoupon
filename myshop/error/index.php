
エラー制御

<?php
/* @var $this CouponApp */
$this->mark();

$action = $this->GetAction();
switch( $action ){
	case '':
		break;
	default:
		$this->mark("undefined action=$action");
}

