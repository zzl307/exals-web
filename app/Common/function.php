<?php
	// json格式化
	function format_json ($json, $html = false)
	{
		$tabcount = 0;
		$result = '';
		$inquote = false;
		$ignorenext = false;
		if ($html) {
			$tab = "   ";
			$newline = "<br/>";
		} else {
			$tab = "\t";
			$newline = "\n";
		}
		for($i = 0; $i < strlen($json); $i++) {
			$char = $json[$i];
			if ($ignorenext) {
				$result .= $char;
				$ignorenext = false;
			} else {
				switch($char) {
					case '{':
						$tabcount++;
						$result .= $char . $newline . str_repeat($tab, $tabcount);
					break;
					case '}':
						$tabcount--;
						$result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char;
					break;
					case ',':
						$result .= $char . $newline . str_repeat($tab, $tabcount);
						break;
					case '"':
						$inquote = !$inquote;
						$result .= $char;
					break;
					case '\\':
						if ($inquote) {
							$ignorenext = true;
						}
						$result .= $char;
					break;
					default:
						$result .= $char;
				}
			}
		}
		return $result;
	}

	// 对象转换数组
	function toArray ($obj){
		return json_decode(json_encode($obj), true);
	}
?>