<?php

/**
 * Class Mark
 * 验证码生成类，是从PHP手册当中拷贝的
 * @see http://php.net
 */
class Mark {
	public static function genMark() {
		$num1 = rand ( 0, 100 ); // 操作数一
		$num2 = rand ( 0, 100 ); // 操作数二
		$num3 = 0; // 计算结果
		
		$operator = array (
				'-',
				'+' 
		); // 运算符
		shuffle ( $operator ); // 将数组内值的顺序打乱
		
		$operator = $operator [0];
		
		switch ($operator) {
			case '+' :
				$num3 = $num1 + $num2;
				break;
			case '-' :
				$num3 = $num1 - $num2;
				break;
		}
		
		$str = $num1 . $operator . $num2 . '= ?'; // 需绘制图片表达式
		
		// Create the image
		$im = imagecreatetruecolor ( 110, 24 );
		
		// Create some colors
		$white = imagecolorallocate ( $im, 255, 255, 255 );
		$grey = imagecolorallocate ( $im, 128, 128, 128 );
		$black = imagecolorallocate ( $im, 0, 0, 0 );
		// $bgcolor = array($white, $grey, $black);
		// shuffle($bgcolor);
		// imagefilledrectangle($im, 0, 0, 399, 29, $bgcolor[0]);
		imagefilledrectangle ( $im, 0, 0, 399, 29, $white );
		
		// $font = 'c:\windows\fonts\arial.ttf';
		$font = dirname ( __FILE__ ) . '/Duality.ttf';
		
		// Add some shadow to the text
		imagettftext ( $im, 17, 0, 11, 21, $grey, $font, $str );
		ob_clean();
		// Add the text
		imagettftext ( $im, 17, 0, 10, 20, $black, $font, $str );
		header ( 'Content-Type: image/png' );
		imagepng ( $im );
		imagedestroy ( $im );
		if (! session_id ())
			session_start ();
		$_SESSION ['mark'] = $num3;
	}
}



