<?php
require '../../source/core.inc.php';
$mod = $_gp['mod'];
if($mod != 1 && $mod != 2 && $mod != 3) die('404');
if($_gp['id'] > 10) {
	$type = substr($_gp['id'], 1, 1);
	if($mod == 2 && $type == 2) {
		$query = $db->query("SELECT `id` FROM `pal4_gonglue` WHERE `id`='".substr($_gp['id'], 2)."';");
	} else {
		$query = $db->query("SELECT `id` FROM `pal4_guide` WHERE `id`='$_gp[id]';");
	}
	if($db->num_rows($query) == 0) die('404');
} else {
	$type = $_gp['id'];
	$page = $_gp['page'];
	if($type != 1 && $type != 2) die('404');
}
?>
<div id="guidesubmenu">
<?php
if($mod == 1) {
?>
	<a id="guidesubmenu1" class="guidesubmenu c link" href="#!/guide/js/1" style="left:0;<?php if($type == 1) echo 'display:none;';?>">游戏操作</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/guide/js/2" style="left:120px;<?php if($type == 2) echo 'display:none;';?>">游戏背景</a>
	<div id="listsubmenu1" class="guidesubmenubig c" style="left:0;<?php if($type == 1) echo 'display:block;';?>">游戏操作</div>
	<div id="listsubmenu2" class="guidesubmenubig c" style="left:120px;<?php if($type == 2) echo 'display:block;';?>">游戏背景</div>
<?php
} elseif($mod == 2) {
?>
	<a id="guidesubmenu1" class="guidesubmenu c link" href="#!/guide/gl/1" style="left:0;<?php if($type == 1) echo 'display:none;';?>">官方流程</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/guide/gl/2" style="left:120px;<?php if($type == 2) echo 'display:none;';?>">玩家攻略</a>
	<div id="listsubmenu1" class="guidesubmenubig c" style="left:0;<?php if($type == 1) echo 'display:block;';?>">官方流程</div>
	<div id="listsubmenu2" class="guidesubmenubig c" style="left:120px;<?php if($type == 2) echo 'display:block;';?>">玩家攻略</div>
<?php
} elseif($mod == 3) {
?>
	<a id="guidesubmenu1" class="guidesubmenu c link" href="#!/guide/mj/1" style="left:0;<?php if($type == 1) echo 'display:none;';?>">游戏秘籍</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/guide/mj/2" style="left:120px;<?php if($type == 2) echo 'display:none;';?>">修改器介绍</a>
	<div id="listsubmenu1" class="guidesubmenubig c" style="left:0;<?php if($type == 1) echo 'display:block;';?>">游戏秘籍</div>
	<div id="listsubmenu2" class="guidesubmenubig c" style="left:120px;<?php if($type == 2) echo 'display:block;';?>">修改器介绍</div>
<?php
}
?>
</div>
<div id="guidemain">
<?php
if($_gp['id'] > 10) {
	if($mod == 2 && $type == 2) {
		$id = substr($_gp['id'], 2);
		$query = $db->query("SELECT `id`,`title`,`content`,`author`,`from` FROM `pal4_gonglue` WHERE `id`='$id';");
		$row = $db->fetch_array($query);
		$query = $db->query("SELECT `id`,`title` FROM `pal4_gonglue` WHERE `id`='".($id - 1)."';");
		if($db->num_rows($query) > 0) {
			$row2 = $db->fetch_array($query);
			$prev = '<a class="link" href="#!/guide/gl/22'.$row2['id'].'">上一篇：'.$row2['title'].'</a> |';
		} else $prev = '';
		$query = $db->query("SELECT `id`,`title` FROM `pal4_gonglue` WHERE `id`='".($id + 1)."';");
		if($db->num_rows($query) > 0) {
			$row2 = $db->fetch_array($query);
			$next = '| <a class="link" href="#!/guide/gl/22'.$row2['id'].'">下一篇：'.$row2['title'].'</a>';
		} else $next = '';
	?>
	<div id="guidecontent" class="guidecontent">
		<?php echo $prev;?> <a class="link" href="#!/guide/gl/2">返回列表</a> <?php echo $next;?>
		<h1><?php echo $row['title'];?></h1>
		<p class="c"><small><?php echo '作者：',$row['author'],'　来源：',$row['from'];?></small></p><br />
		<?php echo $row['content'];?>
		<br /><br />
		<?php echo $prev;?> <a class="link" href="#!/guide/gl/2">返回列表</a> <?php echo $next;?>
<?php
	} else {
		if($mod == 1) $modname = 'js';
		else if($mod == 2) $modname = 'gl';
		else if($mod == 3) $modname = 'mj';
		$query = $db->query("SELECT `id`,`title`,`content` FROM `pal4_guide` WHERE `id`='$_gp[id]';");
		$row = $db->fetch_array($query);
		$query = $db->query("SELECT `id`,`title` FROM `pal4_guide` WHERE `id`='".($_gp['id'] - 1)."';");
		if($db->num_rows($query) > 0) {
			$row2 = $db->fetch_array($query);
			$prev = '<a class="link" href="#!/guide/'.$modname.'/'.$row2['id'].'">上一篇：'.$row2['title'].'</a> |';
		} else $prev = '';
		$query = $db->query("SELECT `id`,`title` FROM `pal4_guide` WHERE `id`='".($_gp['id'] + 1)."';");
		if($db->num_rows($query) > 0) {
			$row2 = $db->fetch_array($query);
			$next = '| <a class="link" href="#!/guide/'.$modname.'/'.$row2['id'].'">下一篇：'.$row2['title'].'</a>';
		} else $next = '';
?>
	<div id="guidecontent" class="guidecontent">
		<?php echo $prev;?> <a class="link" href="#!/guide/<?php echo $modname;?>/<?php echo $type;?>">返回列表</a> <?php echo $next;?>
		<h1><?php echo $row['title'];?></h1>
		<?php echo $row['content'];?>
		<br /><br />
		<?php echo $prev;?> <a class="link" href="#!/guide/<?php echo $modname;?>/<?php echo $type;?>">返回列表</a> <?php echo $next;?>
<?php
	}
} else {
	if($mod == 2 && $type == 2) {
		$id = substr($_gp['id'], 2);
		$query = $db->query("SELECT * FROM `pal4_gonglue`;");
		$pages = ceil($db->num_rows($query) / 18);
		$query = $db->query("SELECT `id`,`title`,`author` FROM `pal4_gonglue` ORDER BY `id` DESC LIMIT ".(($page - 1) * 18).",18;");
?>
	<div id="guidecontent" class="guidelist">
	<table>
		<tr><th style="width:500px;">标题</th><th>作者</th></tr>
<?php
		while($row = $db->fetch_array($query)) {
?>
		<tr><td><a class="link" href="#!/guide/gl/22<?php echo $row['id'];?>"><?php echo $row['title'];?></a></td><td><?php echo $row['author'];?></td></tr>
<?php
		}
		echo '</table></div><div id="multipage">', multipage($page, $pages, '#!/guide/gl/2/'), '</div></div>';
	} else {
		if($mod == 1) $modname = 'js';
		else if($mod == 2) $modname = 'gl';
		else if($mod == 3) $modname = 'mj';
		$query = $db->query("SELECT `id`,`title` FROM `pal4_guide` WHERE `id` LIKE '".$mod.$type."__' ORDER BY `id`;");
		echo '<div id="guidecontent" class="guidelist">';
		while($row = $db->fetch_array($query)) {
?>
		<br /><a class="link" href="#!/guide/<?php echo $modname;?>/<?php echo $row['id'];?>"><?php echo $row['title'];?></a>
<?php
		}
	}
	echo '</div></div>';
}
?>