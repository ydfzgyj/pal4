<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_equipment` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/equipment/bg.jpg" class="preload" />
<div id="datatitle">
<?php
	$type = substr($_gp['id'], 0, 1);
	$typeArr = array('', '武器', '头部防具', '身体防具', '足部防具', '佩戴');
	echo $typeArr[$type];
?>
</div>
<div id="eqplist">
<?php
	$query = $db->query("SELECT `id`,`name`,`level`,`price` FROM `pal4_equipment` WHERE `id` LIKE '".$type."__' ORDER BY `id`;");
	$height = $db->num_rows($query) * 33;
	$top = (1 - substr($_gp['id'], 1, 2)) * 33;
	if($top < 198 - $height) $top = 198 - $height;
	echo '<div id="datalistmain" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
		$query2 = $db->query("SELECT `id` FROM `pal4_casting` WHERE `name`='$row[name]';");
?>
		<a id="equipment<?php echo $row['id'];?>" class="datalistone link" href="#!/data/equipment/<?php echo $row['id'];?>" hidefocus="true">
			<div class="data3left">&nbsp;<?php echo $row['name']; if($db->num_rows($query2)) echo '&nbsp;<img src="./images/data/equipment/c.gif" alt="可铸造" title="可铸造" />';?></div>
			<div class="data3center">&nbsp;<?php echo $row['level'];?></div>
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
<div id="eqpimg"></div>
<div id="eqpintro"></div>
<div id="eqpeffect"></div>
<div id="datasubmenu">
<?php
	$submenu = $submenubig = '';
	for($i = 1; $i <= 5; $i ++) {
		$submenu .= '<a id="eqpmenu'.$i.'" class="datasubmenu link" href="#!/data/equipment/'.$i.'01" title="'.$typeArr[$i].'"';
		$submenubig .= '<div id="eqpmenubig'.$i.'" class="datasubmenubig"';
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
	$query = $db->query("SELECT `name`,`intro`,`effect`,`cast`,`power` FROM `pal4_equipment` WHERE `id`='$_gp[id2]';");
	$row = $db->fetch_array($query);
	$query = $db->query("SELECT `id` FROM `pal4_casting` WHERE `name`='$row[name]';");
	if($db->num_rows($query)) {
		$row2 = $db->fetch_array($query);
		echo $row['name'],'|',$row['intro'],'<p class="r"><a class="link" href="#!/data/casting/',$row2['id'],'">查看图谱</a></p>|<strong>属性：</strong>',$row['effect'],'<br /><strong>灵蕴：</strong>',$row['cast'],'<br /><strong>潜力：</strong>',$row['power'];
	} else {
		echo $row['name'],'|',$row['intro'],'|<strong>属性：</strong>',$row['effect'],'<br /><strong>灵蕴：</strong>',$row['cast'],'<br /><strong>潜力：</strong>',$row['power'];
	}
}
?>