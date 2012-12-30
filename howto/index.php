<?php

//  確認
$this->mark('このindex.phpにdispatch(配送)される');

//  URL引数の取得
$args = $this->GetArgs();
$this->d($args);

//  アクション相当
switch($args[0]){
	case '':
	case 'index':
		$this->template('index.phtml');
		break;
	
	case 'form':
		include('form.php');
		break;
		
	case 'pdo':
		include('pdo.php');
		break;
		
	case 'other':
		//  テンプレートに渡すオブジェクト（これにViewで使用する変数を入れる）
		$data = new Config();
		
		// subアクションを編集（コントロール）する
		$args[1] = isset($args[1]) ? $args[1]: 'null';
		
		// subアクション
		switch($args[1]){
			case 'null':
				$data->message = '';
				break;
				
			case '1':
				$data->message = 'OK';
				break;
				
			default:
				$data->message = 'NG';
		}
		
		$this->template('other.phtml',$data);
		break;
}

?>
[
 <a href="<?=$this->ConvertURL('ctrl:/index')?>">index</a> |
 <a href="<?=$this->ConvertURL('ctrl:/other')?>">other</a> | 
 <a href="<?=$this->ConvertURL('ctrl:/other/1')?>">OK</a> | 
 <a href="<?=$this->ConvertURL('ctrl:/other/0')?>">NG</a>
 ]