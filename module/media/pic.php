<?php
require '../../source/core.inc.php';
if($_gp['id'] > 10) {
	$type = substr($_gp['id'], 1, 1);
} else {
	$type = $_gp['id'];
	$_gp['id'] = '2'.$_gp['id'].'01';
}
$query = $db->query("SELECT `id` FROM `pal4_media` WHERE `id`='$_gp[id]';");
if($db->num_rows($query) == 0) die('404');
?>
<div id="guidesubmenu">
	<a id="guidesubmenu1" class="guidesubmenu c link" href="#!/media/pic/1" style="left:0;<?php if($type == 1) echo 'display:none;';?>">丹青绘卷</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/media/pic/2" style="left:120px;<?php if($type == 2) echo 'display:none;';?>">官方壁纸</a>
	<div id="picsubmenu1" class="guidesubmenubig c" style="left:0;<?php if($type == 1) echo 'display:block;';?>">丹青绘卷</div>
	<div id="picsubmenu2" class="guidesubmenubig c" style="left:120px;<?php if($type == 2) echo 'display:block;';?>">官方壁纸</div>
</div>
<div id="guidemain">
	<div id="bigpic"></div>
<?php
	$query = $db->query("SELECT `id`,`intro`,`site` FROM `pal4_media` WHERE `id` LIKE '2".$type."__' ORDER BY `id`;");
	$height = $db->num_rows($query) * 101;
	$top = (1 - substr($_gp['id'], 2, 2)) * 101;
	if($top < 460 - $height) $top = 460 - $height;
	echo '<div id="guidecontent" class="pic" style="height:',$height,'px;top:',$top,'px;">';
	while($row = $db->fetch_array($query)) {
?>
		<div class="medialistone" style="background:url(./images/media/pic/<?php echo $row['id'];?>.jpg);">
			<a id="pic<?php echo $row['id'];?>" class="mediacover link" href="#!/media/pic/<?php echo $row['id'];?>" hidefocus="true" site="<?php echo $row['site'];?>"></a>
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