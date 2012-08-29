<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_monster` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/monster/bg.jpg" class="preload" />
<div id="datatitle">怪物</div>
<div id="mstlist">
<?php
	$query = $db->query("SELECT `id`,`name`,`level`,`race` FROM `pal4_monster` ORDER BY `id`;");
	$height = $db->num_rows($query) * 33;
	$top = (1 - $_gp['id']) * 33;
	if($top < 198 - $height) $top = 198 - $height;
	echo '<div id="datalistmain" style="height:'.$height.'px;top:'.$top.'px;">';
	while($row = $db->fetch_array($query)) {
?>
		<a id="monster<?php echo $row['id'];?>" class="datalistone link" href="#!/data/monster/<?php echo $row['id'];?>" hidefocus="true">
			<div class="data3left">&nbsp;<?php echo $row['name']; if($row['id'] >= 95) echo '&nbsp;<img src="./images/data/monster/b.gif" alt="BOSS" title="BOSS" />';?></div>
			<div class="data3center">&nbsp;<?php echo $row['level'];?></div>
			<div class="data3right">&nbsp;<?php echo $row['race'];?></div>
		</a>
<?php
	}
?>
	</div>
</div>
<div id="datascroll">
	<div class="totop" title="向上滚动">向上滚动</div>
	<div class="scrollbar">
		<div class="scrollto" title="拖拽滚动">拖拽滚动</div>
	</div>
	<div class="tobottom" title="向下滚动">向下滚动</div>
</div>
<div id="mstimg"></div>
<div id="mstintro"></div>
<div id="msteffect"></div>
<?php
}
if(isset($_gp['id2'])) {
	$query = $db->query("SELECT `name`,`hp`,`property`,`exp`,`special`,`magic`,`map`,`steal`,`drop`,`money` FROM `pal4_monster` WHERE `id`='$_gp[id2]';");
	$row = $db->fetch_array($query);
	
	$arr = explode('，', $row['magic']);
	for($i = 0; $i < count($arr); $i ++) {
		$mgcname = trim($arr[$i]);
		$query = $db->query("SELECT `id` FROM `pal4_magic` WHERE `name`='$mgcname';");
		$row2 = $db->fetch_array($query);
		$row['magic'] = str_replace($mgcname, '<a class="link" href="#!/data/magic/'.$row2['id'].'">'.$mgcname.'</a> ', $row['magic']);
		$row['magic'] = str_replace('，', '', $row['magic']);
	}
	$arr = explode('，', $row['steal']);
	for($i = 0; $i < count($arr); $i ++) {
		$objname = trim($arr[$i]);
		if(substr($objname, 0, 1) > 0 && substr($objname, 0, 1) < 10) break;
		$query = $db->query("SELECT `id` FROM `pal4_object` WHERE `name`='$objname';");
		$row2 = $db->fetch_array($query);
		$row['steal'] = str_replace($objname, '<a class="link" href="#!/data/object/'.$row2['id'].'">'.$objname.'</a> ', $row['steal']);
		$row['steal'] = str_replace('，', '', $row['steal']);
	}
	$arr = explode('，', $row['drop']);
	for($i = 0; $i < count($arr); $i ++) {
		$objname = trim($arr[$i]);
		$query = $db->query("SELECT `id` FROM `pal4_object` WHERE `name`='$objname';");
		$row2 = $db->fetch_array($query);
		$row['drop'] = str_replace($objname, '<a class="link" href="#!/data/object/'.$row2['id'].'">'.$objname.'</a> ', $row['drop']);
		$row['drop'] = str_replace('，', '', $row['drop']);
	}
	
	$row['effect'] = '<strong>出现地图：</strong>'.$row['map'];
	$row['effect'] .= $row['special'] != '' ? '<br /><strong>特殊属性：</strong>'.$row['special'] : '';
	$row['effect'] .= $row['magic'] != '' ? '<br /><strong>仙术特技：</strong>'.$row['magic'] : '';
	$row['effect'] .= $row['steal'] != '' ? '<br /><strong>可偷物品：</strong>'.$row['steal'] : '';
	$row['effect'] .= $row['drop'] != '' ? '<br /><strong>掉落物品：</strong>'.$row['drop'] : '';
	$row['effect'] .= $row['money'] != '' ? '<br /><strong>掉落金钱：</strong>'.$row['money'] : '';
	echo $row['name'],'|','<strong>精：</strong>',$row['hp'],'<br /><strong>五灵属性：</strong>',$row['property'],'<br /><strong>经验：</strong>',$row['exp'],'|',$row['effect'];
}
?>