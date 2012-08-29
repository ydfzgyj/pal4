<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_assignment` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/assignment/bg.jpg" class="preload" />
<div id="datatitle">
<?php
	$type = substr($_gp['id'], 0, 1);
	$typeArr = array('', '主线剧情', '支线剧情', '委托任务', 'NPC对话');
	echo $typeArr[$type];
?>
</div>
<div id="asmlist">
<?php
	$query = $db->query("SELECT `id`,`name` FROM `pal4_assignment` WHERE `id` LIKE '".$type."___' ORDER BY `id`;");
	$height = $db->num_rows($query) * 33;
	$top = (1 - substr($_gp['id'], 1, 3)) * 33;
	if($top < 231 - $height) $top = 231 - $height;
	echo '<div id="datalistmain" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
		$row['name'] = preg_replace("/[0-9]/", "", $row['name']);
?>
		<a id="assignment<?php echo $row['id'];?>" class="datalistone link" href="#!/data/assignment/<?php echo $row['id'];?>" hidefocus="true">
			&nbsp;<?php echo $row['name'];?>
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
<div id="asmimg"></div>
<div id="asmintro"></div>
<div id="asmdialog"></div>
<div id="asmscroll">
	<div class="totop" title="向上滚动">向上滚动</div>
	<div class="scrollbar">
		<div class="scrollto" title="拖拽滚动">拖拽滚动</div>
	</div>
	<div class="tobottom" title="向下滚动">向下滚动</div>
</div>
<div id="datasubmenu">
<?php
	$submenu = $submenubig = '';
	for($i = 1; $i <= 4; $i ++) {
		$submenu .= '<a id="asmmenu'.$i.'" class="datasubmenu link" href="#!/data/assignment/'.$i.'001" title="'.$typeArr[$i].'"';
		$submenubig .= '<div id="asmmenubig'.$i.'" class="datasubmenubig"';
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
	$query = $db->query("SELECT `name`,`intro`,`chain`,`npcfrom`,`npcto`,`findobj`,`prize`,`other`,`from`,`to` FROM `pal4_assignment` WHERE `id`='$_gp[id2]';");
	$row = $db->fetch_array($query);
	
	$row['name'] = preg_replace("/[0-9]/", "", $row['name']);
	$row['dialog'] = '';
	$query = $db->query("SELECT `id`,`people`,`say` FROM `pal4_dialog` WHERE `id`>='$row[from]' AND `id`<='$row[to]' ORDER BY `id`;");
	while($row2 = $db->fetch_array($query)) {
		if($row2['id'] == $row['prize']) $row['dialog'] .= '<hr />';
		$row['dialog'] .= '<li class="asm2left">'.$row2['people'].'</li><li class="asm2right">'.$row2['say'].'</li>';
	}
	$row['dialog'] .= '</ul>';
	
	switch(substr($_gp['id2'], 0, 1)) {
	case 1:
		echo $row['name'],'|',$row['intro'],'|<ul id="asmdialogmain">',$row['dialog'];
		break;
	case 2:
		$row['chaintext'] = '';
		$query = $db->query("SELECT `id`,`name` FROM `pal4_assignment` WHERE `chain`='$row[chain]';");
		while($row2 = $db->fetch_array($query)) {
			$row['chaintext'] .= '&gt;&gt;<a class="link" href="#!/data/assignment/'.$row2['id'].'">'.$row2['name'].'</a>';
		}
		$row['chaintext'] = '<li><a class="link" href="#!/guide/gl/2106"><strong>支线剧情攻略</strong></a></li><li><strong>任务链：</strong>'.substr($row['chaintext'], 8).'</li>';
		echo $row['name'],'|',$row['intro'],'|<ul id="asmdialogmain">',$row['chaintext'],'<li>&nbsp;</li>',$row['dialog'];
		break;
	case 3:
		if(empty($row['npcfrom'])) {
			$query = $db->query("SELECT `npcfrom`,`npcto`,`findobj`,`prize`,`other` FROM `pal4_assignment` WHERE `chain`='$row[chain]' LIMIT 1;");
			$row2 = $db->fetch_array($query);
			$row['npcfrom'] = $row2['npcfrom'];
			$row['npcto'] = $row2['npcto'];
			$row['findobj'] = $row2['findobj'];
			$row['prize'] = $row2['prize'];
			$row['other'] = $row2['other'];
		}
		$row['chaintext'] = '';
		$query = $db->query("SELECT `id`,`name` FROM `pal4_assignment` WHERE `chain`='$row[chain]';");
		while($row2 = $db->fetch_array($query)) {
			$row['chaintext'] .= '&gt;&gt;<a class="link" href="#!/data/assignment/'.$row2['id'].'">'.$row2['name'].'</a>';
		}
		$row['chaintext'] = '<li><strong>任务链：</strong>'.substr($row['chaintext'], 8).'</li>';
		$arr = explode('，', $row['npcfrom']);
		$query = $db->query("SELECT `id`,`name` FROM `pal4_assignment` WHERE `name`='$arr[0]';");
		$row2 = $db->fetch_array($query);
		$row['npcfrom'] = '<li><strong>任务NPC：</strong><a class="link" href="#!/data/assignment/'.$row2['id'].'">'.$arr[0].'</a>'.$arr[1].'</li>';
		if(!empty($row['npcto'])) {
			$arr = explode('，', $row['npcto']);
			$query = $db->query("SELECT `id`,`name` FROM `pal4_assignment` WHERE `name`='$arr[0]';");
			$row2 = $db->fetch_array($query);
			$row['npcto'] = '<li><strong>寻找NPC：</strong><a class="link" href="#!/data/assignment/'.$row2['id'].'">'.$arr[0].'</a>'.$arr[1].'</li>';
		}
		if(!empty($row['findobj'])) {
			$arr = explode('，', $row['findobj']);
			for ($i = 0; $i < count($arr); $i ++) {
				$objname = preg_replace("/\*.*/", "", $arr[$i]);
				$query = $db->query("SELECT `id` FROM `pal4_object` WHERE `name`='$objname';");
				if($db->num_rows($query)) {
					$row2 = $db->fetch_array($query);
					$row['findobj'] = str_replace($objname, '<a class="link" href="#!/data/object/'.$row2['id'].'">'.$objname.'</a>', $row['findobj']);
				} else {
					$query = $db->query("SELECT `id` FROM `pal4_equipment` WHERE `name`='$objname';");
					$row2 = $db->fetch_array($query);
					$row['findobj'] = str_replace($objname, '<a class="link" href="#!/data/equipment/'.$row2['id'].'">'.$objname.'</a>', $row['findobj']);
				}
			}
			$row['findobj'] = '<li><strong>寻找物品：</strong>'.$row['findobj'].'</li>';
		}
		$arr = explode('，', $row['prize']);
		for ($i = 0; $i < count($arr); $i ++) {
			$objname = preg_replace("/.*：/", "", $arr[$i]);
			$objname = preg_replace("/\*.*/", "", $objname);
			$query = $db->query("SELECT `id` FROM `pal4_object` WHERE `name`='$objname';");
			if($db->num_rows($query)) {
				$row2 = $db->fetch_array($query);
				$row['prize'] = str_replace($objname, '<a class="link" href="#!/data/object/'.$row2['id'].'">'.$objname.'</a>', $row['prize']);
				continue;
			}
			$query = $db->query("SELECT `id` FROM `pal4_casting` WHERE `name`='$objname';");
			if($db->num_rows($query)) {
				$row2 = $db->fetch_array($query);
				$row['prize'] = str_replace($objname, '<a class="link" href="#!/data/casting/'.$row2['id'].'">'.$objname.'</a>', $row['prize']);
				continue;
			}
			$query = $db->query("SELECT `id` FROM `pal4_equipment` WHERE `name`='$objname';");
			if($db->num_rows($query)) {
				$row2 = $db->fetch_array($query);
				$row['prize'] = str_replace($objname, '<a class="link" href="#!/data/equipment/'.$row2['id'].'">'.$objname.'</a>', $row['prize']);
			}
		}
		$row['prize'] = '<li><strong>任务奖励：</strong>'.$row['prize'].'</li>';
		if(!empty($row['other'])) $row['other'] = '<li><strong>备注：</strong>'.$row['other'].'</li>';
		
		echo $row['name'],'|',$row['intro'],'|<ul id="asmdialogmain">',$row['chaintext'],$row['npcfrom'],$row['npcto'],$row['findobj'],$row['prize'],$row['other'],'<li>&nbsp;</li>',$row['dialog'];
		break;
	case 4:
		echo $row['name'],'NPC对话|',$row['name'],'NPC对话|<ul id="asmdialogmain">',$row['dialog'];
		break;
	}
}
?>