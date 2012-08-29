<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_casting` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/casting/bg.jpg" class="preload" />
<div id="datatitle">
<?php
	$type = substr($_gp['id'], 0, 1);
	$typeArr = array('', '熔铸', '锻冶', '注灵');
	echo $typeArr[$type];
?>
</div>
<div id="cstlist">
<?php
	$query = $db->query("SELECT `id`,`name`,`property`,`price` FROM `pal4_casting` WHERE `id` LIKE '".$type."__' ORDER BY `id`;");
	$height = $db->num_rows($query) * 33;
	$top = (1 - substr($_gp['id'], 1, 2)) * 33;
	if($top < 198 - $height) $top = 198 - $height;
	echo '<div id="datalistmain" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
?>
		<a id="casting<?php echo $row['id'];?>" class="datalistone link" href="#!/data/casting/<?php echo $row['id'];?>" hidefocus="true">
			<div class="data3left">&nbsp;<?php echo $row['name'];?></div>
			<div class="data3center">&nbsp;<?php echo $row['property'];?></div>
			<div class="data3right">&nbsp;<?php echo $row['price'];?></div>
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
<div id="cstimg"></div>
<div id="cstintro"></div>
<div id="csteffect"></div>
<div id="datasubmenu">
<?php
	$submenu = $submenubig = '';
	for($i = 1; $i <= 3; $i ++) {
		$submenu .= '<a id="cstmenu'.$i.'" class="datasubmenu link" href="#!/data/casting/'.$i.'01" title="'.$typeArr[$i].'"';
		$submenubig .= '<div id="cstmenubig'.$i.'" class="datasubmenubig"';
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
}
if(isset($_gp['id2'])) {
	$query = $db->query("SELECT `name`,`intro`,`need`,`effect`,`cast`,`power` FROM `pal4_casting` WHERE `id`='$_gp[id2]';");
	$row = $db->fetch_array($query);
	
	$arr = explode('，', $row['need']);
	for ($i = 0; $i < count($arr); $i ++) {
		$objname = preg_replace("/\*[0-9]{1,2}/", "", $arr[$i]);
		$query = $db->query("SELECT `id` FROM `pal4_object` WHERE `name`='$objname';");
		if($db->num_rows($query)) {
			$row2 = $db->fetch_array($query);
			$row['need'] = str_replace($objname, '<a class="link" href="#!/data/object/'.$row2['id'].'">'.$objname.'</a>', $row['need']);
		} else {
			$query = $db->query("SELECT `id` FROM `pal4_equipment` WHERE `name`='$objname';");
			$row2 = $db->fetch_array($query);
			$row['need'] = str_replace($objname, '<a class="link" href="#!/data/equipment/'.$row2['id'].'">'.$objname.'</a>', $row['need']);
		}
	}
	
	switch(substr($_gp['id2'], 0, 1)) {
	case 1:
		$query = $db->query("SELECT `id` FROM `pal4_equipment` WHERE `name`='$row[name]';");
		$row2 = $db->fetch_array($query);
		echo $row['name'],'|',$row['intro'],'<p class="r"><a class="link" href="#!/data/equipment/',$row2['id'],'">查看成品</a></p>|<strong>所需材料：</strong>',$row['need'],'<br />','<strong>属性：</strong>',$row['effect'],'<br /><strong>灵蕴：</strong>',$row['cast'],'<br /><strong>潜力：</strong>',$row['power'];
		break;
	case 2:
		echo $row['name'],'|',$row['intro'],'|<strong>所需材料：</strong>',$row['need'],'<br />','<strong>属性：</strong>',$row['effect'],'<br /><strong>所需潜力：</strong>',$row['power'];
		break;
	case 3:
		echo $row['name'],'|',$row['intro'],'|<strong>所需材料：</strong>',$row['need'],'<br />','<strong>属性：</strong>',$row['effect'],'<br /><strong>所需灵蕴：</strong>',$row['cast'];
		break;
	}
}
?>