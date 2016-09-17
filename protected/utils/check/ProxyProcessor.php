<?php

class ProxyProcessor implements ICheckProcessor {
	
	public $check;
	
	public function ProxyProcessor($check) {
		$this->check = $check;
	}
	
	public function Check() {
		$result = '';
		if ($this->check != null) {
			$result = $this->check->Check();
			if (!empty($result)) {
				return 
				"<div style='background-color:#C60000;color:#fff;padding: 15px 10px;'>" . $result . 
				"</div>";
			}
		}
	}
	

}

