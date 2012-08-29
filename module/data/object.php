<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_object` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/object/bg.jpg" class="preload" />
<div id="datatitle">
<?php
	$type = substr($_gp['id'], 0, 1);
	$typeArr = array('', '恢复物品', '攻击物品', '辅助物品', '材料', '剧情物品');
	echo $typeArr[$type];
?>
</div>
<div id="objlist">
<?php
	$query = $db->query("SELECT `id`,`name`,`price` FROM `pal4_object` WHERE `id` LIKE '".$type."___' ORDER BY `id`;");
	$height = $db->num_rows($query) * 33;
	$top = (1 - substr($_gp['id'], 1, 3)) * 33;
	if($top < 198 - $height) $top = 198 - $height;
	echo '<div id="datalistmain" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
?>
		<a id="object<?php echo $row['id'];?>" class="datalistone link" href="#!/data/object/<?php echo $row['id'];?>" hidefocus="true">
			<div class="data2left">&nbsp;<?php echo $row['name'];?></div>
			<div class="data2right">&nbsp;<?php echo $row['price'];?></div>
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
<div id="objimg"></div>
<div id="objintro"></div>
<div id="objeffect"></div>
<div id="datasubmenu">
<?php
	$submenu = $submenubig = '';
	for($i = 1; $i <= 5; $i ++) {
		$submenu .= '<a id="objmenu'.$i.'" class="datasubmenu link" href="#!/data/object/'.$i.'001" title="'.$typeArr[$i].'"';
		$submenubig .= '<div id="objmenubig'.$i.'" class="datasubmenubig"';
		if($type == $i) {
			$submenu .= ' style="display:none;"';
			$submenubig .= ' style="display:block;"';
		}
		$submenu .= '>'.$typeArr[$i].'</a>';
		$submenubig .= '></div>';
	}
	echo $submenu,$submenubig;
?>
</div>
<?php
} elseif(isset($_gp['id2'])) {
	$query = $db->query("SELECT `name`,`intro`,`effect`,`useto`,`usefor` FROM `pal4_object` WHERE `id`='$_gp[id2]';");
	$row = $db->fetch_array($query);
	if($row['useto'] != '') $row['effect'] = $row['useto'].$row['usefor'].$row['effect'];
	$row['effect'] = substr($_gp['id2'], 0, 1) == '5' ? $row['effect'] != '' ? '<strong>备注：</strong>'.$row['effect'] : $row['effect'] : '<strong>作用：</strong>'.$row['effect'];
	echo $row['name'],'|',$row['intro'],'<br />',$row['effect'];
	
	$name = $row['name'];
	$casting = $steal = $drop = '';
	$query = $db->query("SELECT `id`,`name` FROM `pal4_casting` WHERE `need` LIKE '%$name%' ORDER BY `id`;");
	while($row = $db->fetch_array($query)) {
		if($name != '兽皮' || $row['id'] < 20000) {
			$casting .= '<a class="link" href="#!/data/casting/'.$row['id'].'">'.$row['name'].'</a> ';
		}
	}
	$casting = $casting != '' ? '<strong>铸造所需：</strong>'.$casting : '';
	
	$query = $db->query("SELECT `id`,`name` FROM `pal4_monster` WHERE `steal` LIKE '%$name%' ORDER BY `id`;");
	while($row = $db->fetch_array($query)) {
		$steal .= '<a class="link" href="#!/data/monster/'.$row['id'].'">'.$row['name'].'</a> ';
	}
	$steal = $steal != '' ? $casting != '' ? $casting.'<br /><strong>怪物可偷：</strong>'.$steal : '<strong>怪物可偷：</strong>'.$steal : $casting;
	
	$query = $db->query("SELECT `id`,`name` FROM `pal4_monster` WHERE `drop` LIKE '%$name%' ORDER BY `id`;");
	while($row = $db->fetch_array($query)) {
		if($name != '兽皮') {
			$drop .= '<a class="link" href="#!/data/monster/'.$row['id'].'">'.$row['name'].'</a> ';
		}
	}
	$drop = $drop != '' ? $steal != '' ? $steal.'<br /><strong>怪物掉落：</strong>'.$drop : '<strong>怪物掉落：</strong>'.$drop : $steal;
	
	echo '|',$drop;
}
?>