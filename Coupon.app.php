<?php
/**
 * Open Coupon App
 * 
 * @author Open Coupon Projects members <open-coupon@gmail.com>
 *
 */
class CouponApp extends App
{
	/**
	 * (non-PHPdoc)
	 * @see App::Config()
	 * @return CouponConfig
	 */
	function Config( $cmgr=null )
	{
		if(!$cmgr){
			$cmgr = new CouponConfig();
		}
		return parent::Config( $cmgr );
	}
	
	function Path2URL($path)
	{
		$app_root = $this->GetEnv('app_root');
		$app_root = rtrim( $app_root, DIRECTORY_SEPARATOR );
		return str_replace( $app_root, '', $path );
	}
	
	/***    ACTION    ***/
	
	function GetAction(){
		$args = $this->GetArgs();
		$action = isset($args[0]) ? $args[0]: 'index';
		return $action;
	}
	
	function GetBuyAction()
	{
		$args = $this->GetArgs();
		//$this->d($args);
		
		//  URLをパース
		$coupon_id = isset($args[0]) ? $args[0]: null;
		$action    = isset($args[1]) ? $args[1]: 'index';
		
		if(!$coupon_id ){
			$coupon_id = $this->GetDefaultCouponId();
			$this->mark("GetDefaultCouponId: $coupon_id ",'debug');
		}
		
		//  現在のCoupon IDを保存
		$this->SetSession('current_coupon_id',$coupon_id);
		
		return $action;
	}
	
	function GetMyShopAction()
	{
		//  Init
		$action = 'does_not_set';
		
		//  Get URL Argument
		$args = $this->GetArgs();
		
		//  Standard
		$action = $args[0];
		switch( $action ){
			case '':
				$action = 'index';
		}
		
		return $action;
	}

	/***    URL Arguments    ***/
	
	function GetArgs( $define=null )
	{
		$temp = parent::GetArgs();
		if( $define ){
			foreach( explode('/',$define) as $key ){
				$args[$key] = array_shift($temp);
			}
		}else{
			$args = $temp;
		}
		return $args;
	}
	
	/**
	 * 
	 */
	function GetShopID($id=null)
	{
		if(!$shop_id = $this->GetSession('myshop_id') ){
			$this->Location('app:/myshop/error/GetShopID');
		}
		return $shop_id;
	}
	
	/**
	 * URLで指定されたcoupon_idまたはsessionに保存していたcoupon_id
	 *
	 * @return number
	 */
	function GetCouponID()
	{
		return $this->GetSession('current_coupon_id');
	}
	
	/**
	 * TOPページなどにCouponID指定なしで訪問された場合に出力するおすすめのクーポンID
	 *
	 * @return number
	 */
	function GetDefaultCouponId(){
		
		//  SELECTの定義を作成
		$config = $this->config()->select_coupon_default();
		
		//  SELECTを実行
		$t_coupon = $this->pdo()->select($config);
		
		return $t_coupon['coupon_id'];
	}

	/**
	 * t_couponからcoupon_idのレコードを取得
	 *
	 * @param  integer  coupon_id
	 * @return mixed    成功=record
	 */
	function GetTCoupon( $coupon_id )
	{
		if(!$coupon_id){
			$this->StackError("Does not set coupon_id.");
			return false;
		}
		
		//  SELECTの定義を作成
		$config = new Config();
		$config->table = 't_coupon';
		$config->where->coupon_id = $coupon_id;
		$config->limit = 1;
		
		//  SELECTを実行
		$t_coupon = $this->pdo()->select($config);
		//$this->d($t_coupon);
		
		//	販売枚数
		$t_coupon['coupon_sales_num_sum'] = $this->GetCouponSalesNum($coupon_id);

		//	割引額
		$t_coupon['coupon_discount_price'] = $t_coupon['coupon_normal_price'] - $t_coupon['coupon_sales_price'];

		//	割引率(%)
		$t_coupon['coupon_discount_rate'] = 100 - (($t_coupon['coupon_sales_price'] / $t_coupon['coupon_normal_price']) * 100);

		//	残り時間を計算
		$rest_time = strtotime($t_coupon['coupon_sales_finish']) - time();

		//	レコードに残り時間を追加
		$t_coupon['rest_time_day']    = floor($rest_time / (86400));
		$t_coupon['rest_time_hour']   = floor(($rest_time - ($t_coupon['rest_time_day']*86400)) / 3600);
		$t_coupon['rest_time_minute'] = date("i",$rest_time);
		$t_coupon['rest_time_second'] = date("s",$rest_time);

		return $t_coupon;
	}
	
	function GetTShop($shop_id)
	{
		if(!$shop_id){
			$this->StackError("Does not set shop_id.");
			return false;
		}
		
		return $this->pdo()->quick(" t_shop.shop_id = $shop_id ");
	}
	
	/**
	 * myshopで使うクーポン一覧を取得する
	 * 
	 * myshopでしか使わないので、これは分離した方が良い。
	 * 
	 * @param  integer $shop_id
	 * @return array 
	 */
	function GetCouponListByShopId($shop_id)
	{
		if(!$shop_id){
			$this->StackError("Does not set shop_id.");
			return false;
		}
		
		//  Init
		$list['wait']   = null;
		$list['on']     = null;
		$list['off']    = null;
		$list['delete'] = null;
		
		//  Wait sale
		$config = $this->config()->select_coupon();
		$config->where->coupon_sales_start  = '> '.date('Y-m-d H:i:s');
		$config->where->coupon_sales_finish = '> '.date('Y-m-d H:i:s');
		$list['wait']  = $this->pdo()->select($config);
	//	$this->mark( $this->pdo()->qu() );
		
		//  On sale
		$config = $this->config()->select_coupon();
		$config->where->coupon_sales_start  = '<  '.date('Y-m-d H:i:s');
		$config->where->coupon_sales_finish = '>  '.date('Y-m-d H:i:s');
		$list['on']  = $this->pdo()->select($config);
	//	$this->mark( $this->pdo()->qu() );
		
		//  End of sale
		$config = $this->config()->select_coupon();
		$config->where->coupon_sales_start  = '< '.date('Y-m-d H:i:s');
		$config->where->coupon_sales_finish = '< '.date('Y-m-d H:i:s');
		$list['off']  = $this->pdo()->select($config);
	//	$this->mark( $this->pdo()->qu() );
		
		//  Delete
		$config = $this->config()->select_coupon();
		$config->where->deleted = '! null';
		$list['delete']  = $this->pdo()->select($config);
	//	$this->mark( $this->pdo()->qu() );
		
		return $list;
	}
	
	/**
	 * このクーポンIDの販売枚数
	 * 
	 * @param unknown $coupon_id
	 * @return boolean
	 */
	function GetCouponSalesNum( $coupon_id )
	{
		if(!$coupon_id){
			$this->StackError("Does not set coupon_id.");
			return false;
		}
		
		//  SELECTの定義を作成
		$config = new Config();
		$config->table    = 't_buy';
		$config->where->coupon_id = $coupon_id;
		$config->agg->sum = 'coupon_id';
		$config->group    = 'coupon_id';
		$config->limit    = 1;
		
		//  Selectの実行
		$t_buy = $this->pdo()->select($config);
		
		if(!count($t_buy)){
			return 0;
		}
		
		return $t_buy['SUM(coupon_id)'];
	}
	
	function GetTShopByShopId($shop_id)
	{
		//  SELECTの定義を作成
		$config = new Config();
		$config->table = 't_shop';
		$config->where->shop_id = $shop_id;
		$config->limit = 1;
		
		//  SELECTを実行
		$t_shop = $this->pdo()->select($config);
		$this->d($t_shop,'debug');
		
		return $t_shop;
	}
	
	/**
	 * 新規アカウントをt_accountに登録
	 *
	 * @param  string   mailaddress
	 * @param  string   password
	 * @return integer  account_id
	 */
	function AccountRegister( $mail, $password ){

		$mailaddr = $this->enc($mail);
		$mailaddr_md5 = md5($mail);
		$password_md5 = md5($password);

		//	既に登録済みかチェック
		if( $id = $this->GetIdFromMailaddr($mail) ){
			//	既にデータベースに登録済み
		}else{
			//	データベースに登録
			$insert = array();
			$insert['table'] = 't_account';
			$insert['set']['mailaddr']		 = $mailaddr; // $this->form->getInputValue('mailaddr');
			$insert['set']['mailaddr_md5']	 = $mailaddr_md5; // md5($this->form->getInputValue('mailaddr'));
			$insert['set']['password']		 = $password_md5; // md5($this->form->getInputValue('password'));
			$id = $this->mysql->insert($insert);
		}
	  
		return $id;
	}

	/**
	 * account_idからcustomer_idを求める
	 *
	 * @param   integer  t_account.account_id
	 * @return  integer  t_customer.customer_id
	 */
	function GetCustomerIdByAccountId($account_id){
		$select['table'] = 't_customer';
		$select['where']['account_id'] = $account_id;
		$select['limit'] = 1;
		$select['as'][] = 'customer_id';
		$temp = $this->mysql->select($select);
		
		return $temp['customer_id'];
	}

	/**
	 * customer_idからt_addressに次に登録するseq_noを求める
	 *
	 * @param   integer  t_customer.customer_id
	 * @return  integer  t_address.seq_no
	 */
	function GetNextSeqNoOfTAddressByCustomerId($customer_id){
		$select['table'] = 't_address';
		$select['as']['max'] = 'max(seq_no)';
		$select['where']['customer_id'] = $customer_id;
		$select['limit'] = 1;
		$temp = $this->mysql->select($select);

		return $temp['max'] +1;
	}

	function GetTCustomerByAccountId( $account_id ){

		$select['table'] = 't_customer';
		$select['where']['account_id'] = $account_id;
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		return $t_temp;
	}

	function GetTAddressByAccountId( $account_id, $seq_no ){
		$customer_id = $this->GetCustomerIdByAccountId($account_id);
		$t_temp = $this->GetTAddressByCustomerId( $customer_id, $seq_no );

		return $t_temp;
	}

	function GetTAddressByCustomerId( $customer_id, $seq_no ){
		$select['table'] = 't_address';
		$select['where']['customer_id'] = $customer_id;
		$select['where']['seq_no'] = $seq_no;
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		return $t_temp;
	}

	function GetAddressSeqNoByAccountId( $account_id ){
		$costomer_id = $this->GetCustomerIdByAccountId( $account_id );
		return $this->GetAddressSeqNoByCostomerId($costomer_id);
	}

	function GetAddressSeqNoByCostomerId( $costomer_id ){
		$select['table'] = 't_customer';
		$select['where']['customer_id'] = $costomer_id;
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		return $t_temp['address_seq_no'];
	}

	//	販売済みのクーポンの枚数を取得
	function GetNumberOfSoldTheCouponStock($stock_id){

		$select = array();
		$select['table'] = 't_passport_sales';
		$select['where']['stock_id'] = $stock_id;
		$count = $this->mysql->count($select);

		return $count;
	}

	//	クーポンが販売中か取得
	function isCouponForSale($stock_id){

		$select = array();
		$select['table'] = 't_passport_stock';
		$select['where']['stock_id'] = $stock_id;
		$select['limit'] = 1;
		$t_stock = $this->mysql->select($select);

		$count = $this->GetNumberOfSoldThePassportStock($stock_id);

		return $count < $t_stock['limit'] ? true: false;
	}

	/**
	 * $coupon_idのクーポン名
	 *
	 * @param  integer  t_coupon.coupon_id
	 * @return integer  クーポン名
	 */
	function GetCouponName( $coupon_id ){
		$select = array();
		$select['table'] = 't_coupon';
		$select['where']['coupon_id'] = $coupon_id;
		$select['as'][] = 'coupon_title';
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		if( 0 ){
			$this->d($t_temp);
			$this->d($this->mysql->qu());
			$this->d($select);
		}

		return $t_temp['coupon_title'];
	}

	/**
	 * $coupon_idのクーポンの販売価格
	 *
	 * @param  integer  t_coupon.coupon_id
	 * @return integer  クーポンの販売価格
	 */
	function GetCouponPrice( $coupon_id ){
		$select = array();
		$select['table'] = 't_coupon';
		$select['where']['coupon_id'] = $coupon_id;
		$select['as'][] = 'coupon_sales_price';
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		if( 0 ){
			$this->d($t_temp);
			$this->d($this->mysql->qu());
			$this->d($select);
		}

		return $t_temp['coupon_sales_price'];
	}

	/**
	 * $coupon_idの販売済みの枚数を取得
	 *
	 * @param  integer  t_coupon.coupon_id
	 * @return integer  販売枚数
	 */
	function GetCouponSoldNum( $coupon_id ){
		$select = array();
		$select['table'] = 't_buy';
		$select['where']['coupon_id'] = $coupon_id;
		$select['as']['sum'] = 'sum(num)';
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		if( 0 ){
			$this->d($t_temp);
			$this->d($this->mysql->qu());
			$this->d($select);
		}

		return (int)$t_temp['sum'];
	}

	/**
	 * $coupon_idの販売可能な上限の枚数を取得
	 *
	 * @param  integer  t_coupon.coupon_id
	 * @return integer  販売可能下限枚数
	 */
	function GetCouponStockTop( $coupon_id ){
		$select = array();
		$select['table'] = 't_coupon';
		$select['where']['coupon_id'] = $coupon_id;
		$select['as'][] = 'coupon_sales_num_bottom';
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		if( 1 ){
			$this->d($t_temp);
			$this->d($this->mysql->qu());
			$this->d($select);
		}

		return (int)$t_temp['coupon_sales_num_bottom'];
	}

	function GetCouponSerialNo( $args ){
//		$this->d($args);

		/*
		 $seler_id	 = sprintf("%02d",1);
		 $stock_id	 = sprintf("%02d",$stock_id);
		 $seq_no		 = sprintf("%02d",$count +1);
		 $serial_no	 = sprintf('1-%s-%s-%s', $seler_id, $stock_id, $seq_no);
		 */

		$account_id = $args['user_id'];
		$coupon_id  = $args['item_id'];

		$select = array();
		$select['table'] = 't_coupon';
		$select['as'][] = 'shop_id';
		$select['where']['coupon_id'] = $coupon_id;
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);
		$seler_id = $t_temp['shop_id'];

		if( 0 ){
			$this->d($t_temp);
			$this->d($this->mysql->qu());
			$this->d($select);
		}

		$select = array();
		$select['table'] = 't_buy';
		$select['where']['account_id']	 = $account_id;
		$select['where']['coupon_id']	 = $coupon_id;
		$seq_no = $this->mysql->count($select);

		if( 0 ){
			$this->d($seq_no);
			$this->d($this->mysql->qu());
			$this->d($select);
		}

		$serial_no	 = sprintf('%03d-%03d-%03d-%s', $seler_id, $coupon_id, $account_id, $seq_no+1 );
		$this->mark("serial_no = $serial_no");

		return $serial_no;
	}

	function CreateSID(){

		do{
			$sid = date('m_d_His_').rand(100,999);
			//	SIDの重複チェック
			$select = array();
			$select['table'] = 't_buy';
			$select['where']['SID'] = $sid;
			$count = $this->mysql->count($select);
			if( 0 ){
				$this->mark($count);
				$this->mark($this->mysql->qu());
				$this->d($select);
			}
				
		}while($count);

		$this->SetSession('sid',$sid);

		return $sid;
	}

	function GetSID(){
		return $this->GetSession('sid');
	}

	function CreateIP_USER_ID( $account_id, $op_uniq_id, $mailaddr_md5, $card_no, $card_exp ){

		if( strlen($card_no) != 4 ){
			$this->StackError("String length is not 4 characters.($card_no)");
			return false;
		}
		if( strlen($card_exp) != 4 ){
			$this->StackError("String length is not 4 characters.($card_exp)");
			return false;
		}

		$select = array();
		$select['table'] = 'dc_ip_user_id';
		$select['where']['account_id']	 = $account_id;
		$select['where']['op_uniq_id']	 = $op_uniq_id;
		$select['where']['mailaddr_md5'] = $mailaddr_md5;
		$select['where']['card_number']	 = $card_no;
		$select['where']['card_expire']	 = $card_exp;
		$select['as'][] = 'ip_user_id';
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);

		if( 0 ){
			$this->d($this->mysql->qu());
			$this->d($t_temp);
			$this->d($select);
		}

		if( $t_temp['ip_user_id'] ){
			$ip_user_id = $t_temp['ip_user_id'];
		}else{
			$insert = array();
			$insert['table'] = 'dc_ip_user_id';
			$insert['set']['account_id']	 = $account_id;
			$insert['set']['op_uniq_id']	 = $op_uniq_id;
			$insert['set']['mailaddr_md5']	 = $mailaddr_md5;
			$insert['set']['card_number']	 = $card_no;
			$insert['set']['card_expire']	 = $card_exp;
			$ip_user_id = $this->mysql->insert($insert);
		}

		if( 0 ){
			$this->mark($this->mysql->qu());
			$this->d($insert);
			$this->mark($ip_user_id);
		}

		return 'coupon_'.$ip_user_id;
	}

	function GetIP_USER_ID( $user_id, $card_no, $card_exp ){

		$account_id = $user_id;
		$op_uniq_id = $this->GetEnv('op-uniq_id');
		$mailaddr   = $this->GetMailaddrFromId($account_id);
		$mailaddr_md5 = md5($mailaddr);

		$card_no = substr( $card_no, -4, 4 );
		if( strlen($card_no) != 4 ){
			$this->StackError("String length is not 4 characters.(card_no=$card_no)");
			return false;
		}
		if( strlen($card_exp) != 4 ){
			$this->StackError("String length is not 4 characters.(card_exp=$card_exp)");
			return false;
		}

		$select = array();
		$select['table'] = 't_customer';
		$select['where']['account_id'] = $account_id;
		$select['limit'] = 1;
		$select['as'][] = 'IP_USER_ID';
		$t_temp = $this->mysql->select($select);
		$ip_user_id = $t_temp['IP_USER_ID'];

		if( $ip_user_id ){
			//	exists
		}else{
			//	empty
			$ip_user_id = $this->CreateIP_USER_ID( $account_id, $op_uniq_id, $mailaddr_md5, $card_no, $card_exp );
		}

		if( 0 ){
			$this->mark($this->mysql->qu());
			$this->d($select);
			$this->d($t_temp);
			$this->mark($ip_user_id);
		}

		return $ip_user_id;
	}

	function SetIP_USER_ID( $user_id, $ip_user_id ){

		if( empty($user_id) ){
			throw new Exception('empty user_id, PATH='.__FILE__.': '.__LINE__);
		}

		if( empty($ip_user_id) ){
			throw new Exception('empty ip_user_id, PATH='.__FILE__.': '.__LINE__);
		}
		
		$select = array();
		$select['table'] = 't_customer';
		$select['where']['account_id'] = $user_id;
		$select['limit'] = 1;
		$t_temp = $this->mysql->select($select);
		if( $t_temp['IP_USER_ID'] ){
			// すでに登録済み
			return true;
		}
		
		$update = array();
		$update['table'] = 't_customer';
		$update['where']['account_id'] = $user_id;
		$update['set']['IP_USER_ID']   = $ip_user_id;
		$num = $this->mysql->update($update);
		
		// 直近のSQL文がエラーだったかチェック
		if(!$this->mysql->io){
			throw new Exception('MySQL UPDATE Failed. SQL='.$this->mysql->qu().', PATH='.__FILE__.': '.__LINE__);
		}

		return true;
	}

	/**
	 * 売上テーブル（t_buy）に記録（トランザクション中）
	 *
	 *
	 *
	 */
	function InsertBuy( $user_id, $item_id, $item_num, $sid ){

		if( empty($user_id) ){
			throw new Exception('empty user_id, PATH='.__FILE__.': '.__LINE__);
		}

		if( empty($item_id) ){
			throw new Exception('empty item_id, PATH='.__FILE__.': '.__LINE__);
		}

		if( empty($item_num) ){
			throw new Exception('empty item_num, PATH='.__FILE__.': '.__LINE__);
		}

		if( empty($sid) ){
			throw new Exception('empty sid, PATH='.__FILE__.': '.__LINE__);
		}

		$insert = array();
		$insert['table'] = 't_buy';
		$insert['set']['account_id']	 = $user_id;
		$insert['set']['coupon_id']		 = $item_id;
		$insert['set']['num']			 = $item_num;
		$insert['set']['settle_flag']	 = 51; // 51=クレジットカード, 
		$insert['set']['SID']			 = $sid;
		$buy_id = $this->mysql->insert($insert);

		if(!$buy_id){
			throw new Exception('MySQL INSERT Failed. SQL='.$this->mysql->qu().', PATH='.__FILE__.': '.__LINE__);
		}

		return true;
	}

	function DC_Authority( $args, &$error ){

		include_once('modules/DigitalCheck.mod.php');
		$dc = new DigitalCheck($this);

		$args['SID']	 = $this->CreateSID();
		$args['KAKUTEI'] = 0;
		$args['STORE']	 = 51;

		$io = $dc->Settlement( $args, $error );
		$this->mark("$io, $error");

		return $io;
	}

	function DC_Decision( $args, &$error ){

		include_once('modules/DigitalCheck.mod.php');
		$dc = new DigitalCheck($this);

		$args['SID'] = $this->GetSID();

		$io = $dc->Decision( $args, $error );

		return $io;
	}

	function DC_Cancel( $args, &$error ){

		include_once('modules/DigitalCheck.mod.php');
		$dc = new DigitalCheck($this);

		$args['SID']	 = $this->GetSID();

		$io = $dc->Cancel( $args, $error, false );

		return $io;
	}
}
