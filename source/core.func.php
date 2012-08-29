<?php

/**
 *     仙剑奇侠传四资料站 - 巴里切罗 http://www.baliqieluo.com/pal4new/
 *     Barrichello(巴里切罗) <ydfzgyj@baliqieluo.com>
 *     核心处理函数库
 */
/**
 *     newHtmlSpecialChars
 *     替换特殊符号
 *     @params string string
 *     @return string string
 */
function newHtmlSpecialChars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = newHtmlSpecialChars($val);
		}
	} else {
		str_replace(array('&', '"', '<', '>', '\'', '\\'), array('&amp;', '&quot;', '&lt;', '&gt;', '&#39;', '&#92;'), $string);
		if(strpos($string, '&amp;#') !== false) {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
		$string = trim($string);
	}
	return $string;
}

/**
 *     multipage
 *     分页函数
 *     @params number cp
 *     @params number pages
 *     @params string url
 *     @return string multi
 */
function multipage($cp, $pages, $url) {
	$multi = '';
	if($cp > 5) {
		$multi .= '<a href="'.$url.'1">1...</a><a href="'.$url.($cp - 1).'">&lt;&lt;</a>';
		$pstart = $cp - 4;
	} else {
		$pstart = 1;
	}
	if($cp < $pages - 4) {
		$pend = $cp + 4;
	} else {
		$pend = $pages;
	}
	for($i = $pstart; $i <= $pend; $i ++) {
		if($i != $cp) {
			$multi .= '<a href="'.$url.$i.'">'.$i.'</a>';
		} else {
			$multi .= '<strong>'.$i.'</strong>';
		}
	}
	if($cp < $pages - 4) {
		$multi .= '<a href="'.$url.($cp + 1).'">&gt;&gt;</a><a href="'.$url.$pages.'">...'.$pages.'</a>';
	}
	return $multi;
}