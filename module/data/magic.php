<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_magic` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/magic/bg.jpg" class="preload" />
<div id="datatitle">
<?php
	$type = substr($_gp['id'], 0, 1);
	$typeArr = array('', '水系仙术', '火系仙术', '雷系仙术', '风系仙术', '土系仙术', '特技');
	echo $typeArr[$type];
?>
</div>
<div id="mgclist">
<?php
	$query = $db->query("SELECT `id`,`name`,`class`,`need`,`learn` FROM `pal4_magic` WHERE `id` LIKE '".$type."___' ORDER BY `id`;");
	$height = $db->num_rows($query) * 33;
	$top = (1 - substr($_gp['id'], 1, 3)) * 33;
	if($top < 264 - $height) $top = 264 - $height;
	echo '<div id="datalistmain" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
		if($row['class'] == '辅助') $row['classimg'] = 'f';
		elseif($row['class'] == '攻击') $row['classimg'] = 'g';
		elseif($row['class'] == '恢复') $row['classimg'] = 'h';
		$row['needwhat'] = substr($_gp['id'], 0, 1) > 5 ? '点气' : '点神';
?>
		<a id="magic<?php echo $row['id'];?>" class="datalistone link" href="#!/data/magic/<?php echo $row['id'];?>" hidefocus="true">
			<div class="data3left">&nbsp;<?php echo $row['name'];?>&nbsp;<img src="./images/data/magic/<?php echo $row['classimg'];?>.gif" alt="<?php echo $row['class'];?>" title="<?php echo $row['class'];?>" /></div>
			<div class="data3center">&nbsp;<?php echo $row['need'].$row['needwhat'];?></div>
			<div class="data3right">&nbsp;<?php echo $row['learn'];?></div>
		</a>
<?php
	}
?>
	</div>
</div>
<div id="datascroll" style="height:296px;">
	<div class="totop" title="向上滚动">向上滚动</div>
	<div class="scrollbar" style="height:238px;">
		<div class="scrollto" title="拖拽滚动">拖拽滚动</div>
	</div>
	<div class="tobottom" title="向下滚动">向下滚动</div>
</div>
<div id="mgcwx">
	<div id="mgcwx1" class="mgcwx"></div>
	<div id="mgcwx2" class="mgcwx"></div>
	<div id="mgcwx3" class="mgcwx"></div>
	<div id="mgcwx4" class="mgcwx"></div>
	<div id="mgcwx5" class="mgcwx"></div>
</div>
<div id="mgcintro"></div>
<div id="mgceffect"></div>
<div id="datasubmenu">
<?php
	$submenu = $submenubig = '';
	for($i = 1; $i <= 6; $i ++) {
		$submenu .= '<a id="mgcmenu'.$i.'" class="datasubmenu link" href="#!/data/magic/'.$i.'001" title="'.$typeArr[$i].'"';
		$submenubig .= '<div id="mgcmenubig'.$i.'" class="datasubmenubig"';
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
<div id="mgcswf">
	<div id="mgcswfmain"></div>
	<a id="exitmgcswf" href="javascript:;" title="关闭">关闭</a>
</div>
<?php
}
if(isset($_gp['id2'])) {
	$query = $db->query("SELECT `name`,`intro`,`useto`,`usefor`,`effect`,`learn` FROM `pal4_magic` WHERE `id`='$_gp[id2]';");
	$row = $db->fetch_array($query);
	$type = substr($_gp['id2'], 0, 1) > 5 ? '特技' : '仙术';
	if($row['learn'] == '敌特技') $row['intro'] .= '敌方特技';
	else if($row['learn'] == '敌仙术') $row['intro'] .= '（敌方仙术）';
	else if($row['name'] != '猎兽') $row['intro'] .= '<p class="r"><a id="openmgcswf" href="javascript:;" title="观看'.$type.'动画">观看'.$type.'动画</a></p>';
	$monster = '';
	$query = $db->query("SELECT `id`,`name` FROM `pal4_monster` WHERE `magic` LIKE '%$row[name]%' ORDER BY `id`;");
	while($row2 = $db->fetch_array($query)) {
		$monster .= '<a class="link" href="#!/data/monster/'.$row2['id'].'">'.$row2['name'].'</a> ';
	}
	if($monster != '') $monster = '<strong>所属怪物：</strong>'.$monster;
	echo $row['name'],'|',$row['intro'],'|<strong>作用：</strong>',$row['useto'],$row['usefor'],'，',$row['effect'],'<br />',$monster;
}
?>