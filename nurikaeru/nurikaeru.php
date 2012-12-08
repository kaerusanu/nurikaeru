<?php

$nurikaeru = new Nurikaeru();

class Nurikaeru {

	static $debug = true;

	const REGEX_PERSE_CLASS_ID = '/(\.|#)([a-zA-Z0-9_\-@\s]+){([^{^}]*)}/';
	static $cssClasses = array();

	static function init() {
		ob_start();
	}

	static function import($path) {
		if (file_exists($path)) {
			include($path);
		} else {
			throw new Exception(__CLASS__.'::import() '.$path.' not found!');
		}
	}

	static function exe() {
		$css = strtr(ob_get_contents(), array("\n" => '', "\r" => '',"\t" => ''));
		ob_clean();
		$new_css = '';
		
		if (preg_match_all(self::REGEX_PERSE_CLASS_ID, $css, $matches)) {
			$css_classes = array();
			foreach($matches[0] as $key => $st ) {
				list($class_id_name_text, $extends_class_text) = explode(' @extends ', $matches[1][$key].$matches[2][$key].' @extends ');
				$contents = trim($matches[3][$key], ';');
				
				//contents
				$class_contents = array();
				$parts = explode(';', $contents);
				foreach ($parts as $part) {
					list($attr , $value) = explode(':', $part);
					$class_contents[trim($attr)] = trim($value);
				}

				//extends
				$extends_classes = array();
				if ($extends_class_text) {
					$tmp_ext = explode(' ', $extends_class_text);
					array_shift($tmp_ext);
					$extends_classes = $tmp_ext;
				}

				//is_class
				$is_class = (substr($class_id_name_text,0,1) == '.');
				$class_name = substr($class_id_name_text,1);

				self::$cssClasses[$class_name] = new CssClassOrId($is_class,$class_name,$extends_classes,$class_contents);
			}

			//継承解決
			foreach (self::$cssClasses as $cssClass) {
				$cssClass->resolveExtends();
			}

			//css生成
			foreach (self::$cssClasses as $cssClass) {
				$new_css .= $cssClass;
			}

		} else {
			$new_css = $css;
		}
		echo $new_css;
	}
	
	public function __construct() {
		self::init();
	}
	
	public function __destruct() {
		self::exe();
	}
}

class CssClassOrId{
	public $isClass = true;
	public $name = '';
	public $extendsClasses = array();
	public $contents = array();

	public function __construct($is_class,$name,$extClasses,$contents) {
		$this->isClass = $is_class;
		$this->name = $name;
		$this->extendsClasses = $extClasses;
		$this->contents = $contents;
	}

	public function resolveExtends() {
		foreach($this->extendsClasses as $class) {
			if (isset(Nurikae::$cssClasses[$class])) {
				Nurikae::$cssClasses[$class]->resolveExtends();
				$this->contents = Nurikae::$cssClasses[$class]->contents + $this->contents;
			} else {
				throw new Exception('css class:'.$this->name.' not found!');
			}
		}
		$this->extendsClasses = array();
	}

	/*
	 * 出力
	 */
	public function __toString() {
		$debug = Nurikaeru::$debug;
		$newline = "";
		$tab = "";
		if ($debug) {
			$newline = "\n";
			$tab = "\t";
		}

		$view = ($this->isClass ? '.' : '#').$this->name .' {'.$newline;
		foreach ($this->contents as $attr => $val) {
			$view .= $tab.$attr.':'.$val.';'.$newline;
		}
		$view .= '}'.$newline.$newline;
		return $view;
	}
}

function mergeColor($base_color, $add_color, $add_rate = 0) {
	if ($add_rate < 0) {
		$add_rate = 0;
	} elseif($add_rate > 100) {
		$add_rate = 100;
	}

	$base_color = trim($base_color, '#');
	$add_color = trim($add_color, '#');
	if (strlen($base_color) == 3) {
		$base_color = substr($base_color, 0, 1).'0'.substr($base_color, 1, 1).'0'.substr($base_color, 2, 1).'0';
	}
	if (strlen($add_color) == 3) {
		$add_color = substr($add_color, 0, 1).'0'.substr($add_color, 1, 1).'0'.substr($add_color, 2, 1).'0';
	}
	
	$color_perse = function($color) {
		$result = array();
		foreach(array('r','g','b') as $k => $c) {
			$result[$c] = hexdec(substr($color, $k * 2, 2 ));
		}
		return $result;
	};
	$baseColor10 = $color_perse($base_color);
	$addColor10 = $color_perse($add_color);
	
	$base_rate = 100 - $add_rate;
	$result_color = '';
	foreach($baseColor10 as $c => $v) {
		$result_color .= dechex(round($v * $base_rate / 100 + $addColor10[$c] * $add_rate / 100));
	}
	return $result_color;
}

