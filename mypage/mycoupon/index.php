<?php 
/* @var $this CouponApp */
$config = $this->config()->select_my_buy();
$t_buys = $this->pdo()->select($config);
//$this->d($t_buys);

$coupons = array();
foreach ($t_buys as $t_buy){
	$coupon_id = $t_buy['coupon_id'];
			
	$config = $this->config()->select_one_coupon($coupon_id);
	$t_coupon = $this->pdo()->select($config);
			
	$t_coupon['coupon_expire'] = date('Y年m月d日', strtotime($t_coupon['coupon_expire']));
	$t_coupon['num'] = $t_buy['num'];
	array_push($coupons, $t_coupon);
}

//$this->d($coupons);
include('mycoupon.phtml');

?>