<?php
/* @var $this App */
$this->mark('Formのサンプル');

//  Formの設定
$config = new Config();

//  Formの名前
$form_name = 'form_test';
$config->name = $form_name;

//  input text
$input_name = 'nickname';
$config->input->$input_name->name  = $input_name;
$config->input->$input_name->label = 'ニックネーム';
$config->input->$input_name->validate->required = true;

//  select
$input_name = 'gender';
$config->input->$input_name->name  = $input_name;
$config->input->$input_name->type  = 'select';
$config->input->$input_name->label = '性別';
$config->input->$input_name->validate->required = true;

	$config->input->$input_name->option->none->value = '';

	$config->input->$input_name->option->male->label   = '男性';
	$config->input->$input_name->option->male->value   = 'male';

	$config->input->$input_name->option->female->label = '女性';
	$config->input->$input_name->option->female->value = 'female';
	
//  input submit
$input_name = 'submit';
$config->input->$input_name->name  = $input_name;
$config->input->$input_name->type  = 'submit';
$config->input->$input_name->value = ' Submit!! ';

//  debug
//$this->d( Toolbox::toArray($config) );

//  フォームを設定する
$this->form()->AddForm($config);

if( $this->form()->Secure($form_name) ){
	$this->p('Submit form is successful!!');
}else{
	$this->form()->debug($form_name);
}

?>
<style>

div.table{
	border: 0px dotted black;
    display: table;
}

div.tr{
	border: 0px dotted blue;
    display: table-row;
}

div.td{
	border: 0px dotted yellow;
    display: table-cell;
    vertical-align: top;
}

</style>
<div>
	<?php $this->form()->Start($form_name); ?>
	<div class="table">
		<div class="tr">
			<div class="td" style="padding: 0em 1em;">
				<?php $this->form()->Label('nickname'); ?>
			</div>
			<div class="td">
				<?php $this->form()->Input('nickname'); ?>
				<p><?php $this->form()->Error('nickname'); ?></p>
			</div>
		</div>
		<div class="tr">
			<div class="td" style="padding: 0em 1em;">
				<?php $this->form()->Label('gender'); ?>
			</div>
			<div class="td">
				<?php $this->form()->Input('gender'); ?>
				<p><?php $this->form()->Error('gender'); ?></p>
			</div>
		</div>
		<div class="tr">
			<div class="td">
				<!-- empty -->
			</div>
			<div class="td">
				<?php $this->form()->Input('submit'); ?>
			</div>
		</div>
	</div>
	<?php $this->form()->Finish($form_name); ?>
</div>