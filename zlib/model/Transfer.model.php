<?php

class Model_Transfer extends Model_Model
{
	private $_url = null;
	private $_sec = 5;
	
	function __destruct()
	{
		if( $url = $this->_url ){
			print $this->_GetHTML();
			print $this->_GetJavaScript();
		}else{
			$this->mark("Does not set transfer URL.");
		}
		
		parent::__destruct();
	}
	
	function Help()
	{
		$this->mark('$data = $this->model("Transfer")->Set($url)->Get();');
	}
	
	function Set( $url, $sec=5 )
	{
		//	Save transfer URL.
		$this->_url = $this->ConvertURL($url);
		$this->_sec = $sec;
		
		return $this;
	}
	
	function Get( $html=true, $js=true )
	{
		if( $html ){
		print $this->_GetHTML();
		}
		
		if( $js ){
			print $this->_GetJavaScript();
		}
			
		//	Return values.
		$data = new Config();
		$data->url = $url;
		$data->sec = $sec;
		return $data;
	}
	
	private function _GetHTML()
	{
		static $print = null;
		if( $print ){
			return;
		}
		$print = true;
		
		$url = $this->_url;
		$sec = $this->_sec;
		return <<< "__EOL__"
		<div id="op_module_transfer">
			<div>
				URL : <a id="op_module_transfer_url" href="$url">$url</a>
			</div>
			<div>
				<span id="op_module_transfer_count" class="big red">$sec</span>
			</div> 
			<div>
				<input id="op_module_transfer_cancel" type="button" class="button cancel_button" value=" Cancel "/>
			</div>
		</div>
__EOL__;
	}
	
	private function _GetJavaScript()
	{
		static $print = null;
		if( $print ){
			return;
		}
		$print = true;
		
		$url = $this->_url;
		return <<< "__EOL__"
<script>
$(document).ready(function(){
    //	Do loop, every 1sec.
    var timer_id = setInterval(function(){
        var countdown = $('#op_module_transfer_count').text();
        if( countdown > 0 ){
            countdown--;
            $('#op_module_transfer_count').text(countdown);
        }else{
            clearInterval(timer_id);
            document.location = '$url';
        }
    }, 1000);
		
	//  Cancel
    $("#op_module_transfer_cancel").click(function(){
        clearInterval(timer_id);
    });
});
</script>
__EOL__;
	}
}