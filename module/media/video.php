<?php
require '../../source/core.inc.php';
if($_gp['id'] > 10) {
	$type = substr($_gp['id'], 1, 1);
} else {
	$type = $_gp['id'];
	$_gp['id'] = '3'.$_gp['id'].'01';
}
$query = $db->query("SELECT `id` FROM `pal4_media` WHERE `id`='$_gp[id]';");
if($db->num_rows($query) == 0) die('404');
?>
<div id="guidesubmenu">
	<a id="guidesubmenu1" class="guidesubmenu c link" href="#!/media/video/1" style="left:0;<?php if($type == 1) echo 'display:none;';?>">前尘忆梦</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/media/video/2" style="left:120px;<?php if($type == 2) echo 'display:none;';?>">全剧情MV</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/media/video/3" style="left:240px;<?php if($type == 3) echo 'display:none;';?>">PAS游戏剧</a>
	<div id="videosubmenu1" class="guidesubmenubig c" style="left:0;<?php if($type == 1) echo 'display:block;';?>">前尘忆梦</div>
	<div id="videosubmenu2" class="guidesubmenubig c" style="left:120px;<?php if($type == 2) echo 'display:block;';?>">全剧情MV</div>
	<div id="videosubmenu3" class="guidesubmenubig c" style="left:240px;<?php if($type == 3) echo 'display:block;';?>">PAS游戏剧</div>
</div>
<div id="guidemain">
	<div id="videoplayer"></div>
<?php
	$query = $db->query("SELECT `id`,`intro`,`site` FROM `pal4_media` WHERE `id` LIKE '3".$type."__' ORDER BY `id`;");
	$height = $db->num_rows($query) * 101;
	$top = (1 - substr($_gp['id'], 2, 2)) * 101;
	if($top < 460 - $height) $top = 460 - $height;
	echo '<div id="guidecontent" class="video" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
		$row['intro'] = str_replace("\r\n", "<br />", $row['intro']);
?>
		<div class="medialistone" style="background:url(./images/media/video/<?php echo $row['id'];?>.jpg);">
			<a id="video<?php echo $row['id'];?>" class="mediacover link" href="#!/media/video/<?php echo $row['id'];?>" hidefocus="true" site="<?php echo $row['site'];?>"></a>
			<div class="mediaintro">
				<div class="mediaintrot"></div>
				<div class="mediaintroc">
					<div class="mediaintromain"><?php echo $row['intro'];?></div>
				</div>
				<div class="mediaintrob"></div>
				<div class="mediaarrowbg"></div>
				<div class="mediaarrow"></div>
			</div>
		</div>
<?php
	}
?>
	</div>
</div>