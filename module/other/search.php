<?php
require '../../source/core.inc.php';
$word = $_gp['word'];
$word = str_replace(array('&', '|', '%', '_', '<', '>'), '', $word);
?>
<input id="searchbox" value="<?php echo $word;?>" />
<a id="search" href="javascript:;" title="搜索" hidefocus="true">搜索</a>
<div id="guidemain">
	<div id="guidecontent" class="search">
<?php
if($word != '') {
	$result = array();
	$arr = explode(' ', $word);
	for ($i = 0; $i < count($arr); $i ++) {
		$str = trim($arr[$i]);
		if($str == ''){ continue; }
		//攻略
		$query = $db->query("SELECT `id`,`title`,`content` FROM `pal4_guide` WHERE `title` LIKE '%$str%' OR `content` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['title'] == $str) $weight = 20;
			elseif(stristr($row['title'], $str)) $weight = 10;
			else $weight = 2;
			if(array_key_exists('gud'.$row['id'], $result)) {
				$result['gud'.$row['id']]['weight'] += $weight;
			} else {
				$row['content'] = str_ireplace('<br>', ' ', $row['content']);
				$row['content'] = preg_replace("/\<(.+?)\>/i", "", $row['content']);
				$length = strlen($row['content']);
				if($length > 200) {
					$pos = stripos($row['content'], $str) - 20;
					if($pos <= 0) {
						$row['content'] = mb_strcut($row['content'], 0, 197, 'utf8').'...';
					} elseif($pos + 197 > $length) {
						$row['content'] = '...'.mb_strcut($row['content'], $length - 197, 200, 'utf8');
					} else {
						$row['content'] = '...'.mb_strcut($row['content'], $pos, 194, 'utf8').'...';
					}
				}
				if(substr($row['id'], 0, 1) == 1) $goto = 'js';
				elseif(substr($row['id'], 0, 1) == 2) $goto = 'gl';
				elseif(substr($row['id'], 0, 1) == 3) $goto = 'mj';
				$website = '#!/guide/'.$goto.'/'.$row['id'];
				$result['gud'.$row['id']] = array('name' => $row['title'], 'intro' => $row['content'], 'website' => $website, 'weight' => $weight);
			}
		}
		//玩家攻略
		$query = $db->query("SELECT `id`,`title`,`content` FROM `pal4_gonglue` WHERE `title` LIKE '%$str%' OR `content` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['title'] == $str) $weight = 20;
			elseif(stristr($row['title'], $str)) $weight = 10;
			else $weight = 2;
			if(array_key_exists('gl'.$row['id'], $result)) {
				$result['gl'.$row['id']]['weight'] += $weight;
			} else {
				$row['content'] = str_ireplace('<br>', ' ', $row['content']);
				$row['content'] = preg_replace("/\<(.+?)\>/i", "", $row['content']);
				$length = strlen($row['content']);
				if($length > 200) {
					$pos = stripos($row['content'], $str) - 20;
					if($pos <= 0) {
						$row['content'] = mb_strcut($row['content'], 0, 197, 'utf8').'...';
					} elseif($pos + 197 > $length) {
						$row['content'] = '...'.mb_strcut($row['content'], $length - 197, 200, 'utf8');
					} else {
						$row['content'] = '...'.mb_strcut($row['content'], $pos, 194, 'utf8').'...';
					}
				}
				$website = '#!/guide/gl/22'.$row['id'];
				$result['gl'.$row['id']] = array('name' => $row['title'], 'intro' => $row['content'], 'website' => $website, 'weight' => $weight);
			}
		}
		//人物
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_character` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 3;
			if(array_key_exists('crt'.$row['id'], $result)) {
				$result['crt'.$row['id']]['weight'] += $weight;
			} else {
				$length = strlen($row['intro']);
				if($length > 200) {
					$pos = stripos($row['intro'], $str) - 20;
					if($pos <= 0) {
						$row['intro'] = mb_strcut($row['intro'], 0, 197, 'utf8').'...';
					} elseif($pos + 197 > $length) {
						$row['intro'] = '...'.mb_strcut($row['intro'], $length - 197, 200, 'utf8');
					} else {
						$row['intro'] = '...'.mb_strcut($row['intro'], $pos, 194, 'utf8').'...';
					}
				}
				$website = '#!/data/character/'.$row['id'];
				$result['crt'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//物品
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_object` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 3;
			if(array_key_exists('obj'.$row['id'], $result)) {
				$result['obj'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/data/object/'.$row['id'];
				$result['obj'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//装备
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_equipment` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 3;
			if(array_key_exists('eqp'.$row['id'], $result)) {
				$result['eqp'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/data/equipment/'.$row['id'];
				$result['eqp'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//仙术
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_magic` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 3;
			if(array_key_exists('mgc'.$row['id'], $result)) {
				$result['mgc'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/data/magic/'.$row['id'];
				$result['mgc'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//熔铸
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_casting` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 3;
			if(array_key_exists('cst'.$row['id'], $result)) {
				$result['cst'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/data/casting/'.$row['id'];
				$result['cst'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//任务
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_assignment` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 3;
			if(substr($row['id'], 0, 1) == 4) $row['name'] .= 'NPC对话';
			if(array_key_exists('asm'.$row['id'], $result)) {
				$result['asm'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/data/assignment/'.$row['id'];
				$result['asm'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//对话
		$query = $db->query("SELECT `id`,`people`,`say` FROM `pal4_dialog` WHERE `people` LIKE '%$str%' OR `say` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			$weight = 1;
			$query2 = $db->query("SELECT `id`,`name` FROM `pal4_assignment` WHERE `from`<='$row[id]' AND `to`>='$row[id]';");
			$row2 = $db->fetch_array($query2);
			if(!array_key_exists('asm'.$row2['id'], $result)) {
				if(substr($row2['id'], 0, 1) == 4) $row2['name'] .= 'NPC对话';
				$row['intro'] = $row['people'].'：'.$row['say'];
				$length = strlen($row['intro']);
				if($length > 200) {
					$pos = stripos($row['intro'], $str) - 20;
					if($pos <= 0) {
						$row['intro'] = mb_strcut($row['intro'], 0, 197, 'utf8').'...';
					} elseif($pos + 197 > $length) {
						$row['intro'] = '...'.mb_strcut($row['intro'], $length - 197, 200, 'utf8');
					} else {
						$row['intro'] = '...'.mb_strcut($row['intro'], $pos, 194, 'utf8').'...';
					}
				}
				$website = '#!/data/assignment/'.$row2['id'];
				$result['asm'.$row2['id']] = array('name' => $row2['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//怪物
		$query = $db->query("SELECT `id`,`name` FROM `pal4_monster` WHERE `name` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			else $weight = 10;
			if(array_key_exists('mst'.$row['id'], $result)) {
				$result['mst'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/data/monster/'.$row['id'];
				$result['mst'.$row['id']] = array('name' => $row['name'], 'intro' => '', 'website' => $website, 'weight' => $weight);
			}
		}
		//地图
		$query = $db->query("SELECT `id`,`name` FROM `pal4_map` WHERE `name` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			else $weight = 10;
			if(array_key_exists('map'.$row['id'], $result)) {
				$result['map'.$row['id']]['weight'] += $weight;
			} else {
				$website = '#!/map/'.$row['id'];
				$result['map'.$row['id']] = array('name' => $row['name'], 'intro' => '', 'website' => $website, 'weight' => $weight);
			}
		}
		//音画
		$query = $db->query("SELECT `id`,`name`,`intro` FROM `pal4_media` WHERE `name` LIKE '%$str%' OR `intro` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['name'] == $str) $weight = 20;
			elseif(stristr($row['name'], $str)) $weight = 10;
			else $weight = 2;
			if(array_key_exists('med'.$row['id'], $result)) {
				$result['med'.$row['id']]['weight'] += $weight;
			} else {
				$length = strlen($row['intro']);
				if($length > 200) {
					$pos = stripos($row['intro'], $str) - 20;
					if($pos <= 0) {
						$row['intro'] = mb_strcut($row['intro'], 0, 197, 'utf8').'...';
					} elseif($pos + 197 > $length) {
						$row['intro'] = '...'.mb_strcut($row['intro'], $length - 197, 200, 'utf8');
					} else {
						$row['intro'] = '...'.mb_strcut($row['intro'], $pos, 194, 'utf8').'...';
					}
				}
				switch(substr($row['id'], 0, 1)) {
					case 1: $website = '#!/media/music/'.$row['id']; break;
					case 2: $website = '#!/media/pic/'.$row['id']; break;
					case 3: $website = '#!/media/video/'.$row['id']; break;
				}
				$result['med'.$row['id']] = array('name' => $row['name'], 'intro' => $row['intro'], 'website' => $website, 'weight' => $weight);
			}
		}
		//下载
		$query = $db->query("SELECT `id`,`title`,`content` FROM `pal4_down` WHERE `title` LIKE '%$str%' OR `content` LIKE '%$str%';");
		while($row = $db->fetch_array($query)) {
			if($row['title'] == $str) $weight = 20;
			if(stristr($row['title'], $str)) $weight = 10;
			else $weight = 2;
			if(array_key_exists('dl'.$row['id'], $result)) {
				$result['dl'.$row['id']]['weight'] += $weight;
			} else {
				$row['content'] = str_ireplace('<br>', ' ', $row['content']);
				$row['content'] = preg_replace("/\<(.+?)\>/i", "", $row['content']);
				$length = strlen($row['content']);
				if($length > 200) {
					$pos = stripos($row['content'], $str) - 20;
					if($pos <= 0) {
						$row['content'] = mb_strcut($row['content'], 0, 197, 'utf8').'...';
					} elseif($pos + 197 > $length) {
						$row['content'] = '...'.mb_strcut($row['content'], $length - 197, 200, 'utf8');
					} else {
						$row['content'] = '...'.mb_strcut($row['content'], $pos, 194, 'utf8').'...';
					}
				}
				$website = '#!/down/'.$row['id'];
				$result['dl'.$row['id']] = array('name' => $row['title'], 'intro' => $row['content'], 'website' => $website, 'weight' => $weight);
			}
		}
	}
	$error = array(
		'韩菱沙' => '韩菱纱','韩菱砂' => '韩菱纱','菱沙' => '菱纱','菱砂' => '菱纱','柳梦离' => '柳梦璃','柳梦漓' => '柳梦璃',
		'玄宵' => '玄霄','玄萧' => '玄霄','夙遥' => '夙瑶','夏元晨' => '夏元辰',
		'青峦峰' => '青鸾峰','女箩岩' => '女萝岩','思反谷' => '思返谷','太一仙境' => '太一仙径','紫薇道' => '紫微道','醉花阴' => '醉花荫',
		'烈岩洞' => '烈焰洞','烈炎洞' => '烈焰洞','炙岩洞' => '炙焰洞','炙炎洞' => '炙焰洞','丰都' => '酆都','幻暝界' => '幻瞑界','幻冥界' => '幻瞑界',
		'含灵果' => '晗灵果','含光琉璃' => '晗光琉璃',
		'义和剑' => '羲和剑','义和斩' => '羲和斩','动天贯日式' => '恸天贯日式'
	);
	if(array_key_exists($word, $error)) {
		echo '您要找的是不是：<a href="#!/search/'.$error[$word].'"><em>', $error[$word] ,'</em></a><br /><br />';
	}
	if(!empty($result)) {
		foreach($result as $value) $temparr[] = $value['weight'];
		array_multisort($temparr, SORT_DESC, $result);
		foreach($result as $value) {
			$value['website'] = 'http://www.xianjian.net.cn/'.$value['website'];
			for ($i = 0; $i < count($arr); $i ++) {
				$str = trim($arr[$i]);
				if($str==''){ continue; }
				$value['name'] = str_ireplace($str, '<em>'.$str.'</em>', $value['name']);
				$value['intro'] = str_ireplace($str, '<em>'.$str.'</em>', $value['intro']);
			}
			echo '<div><h3><a href="'.$value['website'].'" target="_blank">'.$value['name'].'</a></h3><br />';
			if($value['intro'] != '') echo $value['intro'].'<br />';
			echo '<cite>'.$value['website'].'</cite></div>';
		}
	} else {
		echo '您所查找的内容不存在！';
	}
}
?>
	</div>
</div>
<div id="guidescroll">
	<div class="totop" title="向上滚动">向上滚动</div>
	<div class="scrollbar">
		<div class="scrollto" title="拖拽滚动">拖拽滚动</div>
	</div>
	<div class="tobottom" title="向下滚动">向下滚动</div>
</div>
<a id="exittomain" class="link" href="#!/index" title="返回首页">返回首页</a>
<img src="./images/other/search.jpg" class="preload" />