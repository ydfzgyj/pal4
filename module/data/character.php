<?php
require '../../source/core.inc.php';
$query = $db->query("SELECT `id` FROM `pal4_character` WHERE `id`='$_gp[id]';");
if($db->num_rows($query) == 0) die('404');
?>
<img src="./images/data/character/bg.jpg" class="preload" />
<div id="character">
<?php
$left = (ceil($_gp['id'] / 4) - 1) * 804;
$query = $db->query("SELECT `id`,`intro`,`identity`,`poem`,`py` FROM `pal4_character` ORDER BY `id`;");
$width = $db->num_rows($query) * 201 - 35;
echo '<div id="crtmain" style="left:-',$left,'px;width:',$width,'px;">';
while($row = $db->fetch_array($query)) {
	$row['intro'] = str_replace("\r\n", "<br />", $row['intro']);
	$row['poem'] = str_replace("\r\n", "<br />", $row['poem']);
	if($row['identity'] != '') {
		$row['intro'] = $row['identity'].'<br /><br /><p class="c">'.$row['poem'].'</p><br /><p class="r"><a class="opencrtmore" id="opencrtmore'.$row['id'].'" href="javascript:;" title="更多介绍">更多介绍</a></p>';
	}
	if($row['py'] != '') {
		$row['py'] = explode('|', $row['py']);
		$row['intro'] = '<p id="py'.$row['id'].'"><strong>配音：</strong>'.$row['py'][0].' <a href="javascript:;" class="jp-play">播放</a> <a href="javascript:;" class="jp-stop">停止</a></p><br />'.$row['intro'].'<div id="jplayer'.$row['id'].'"></div><script>$("#jplayer'.$row['id'].'").jPlayer({ready:function(){$(this).jPlayer("setMedia",{mp3:"http://pal4.baiyou100.com/pal4/zhuanti/Dub/flash/music/'.$row['py'][1].'.mp3"});}, cssSelectorAncestor: "#py'.$row['id'].'", solution: "flash, html", volume: 1, wmode: "window", swfPath:"./script"});</script>';
	}
?>
	<div id="character<?php echo $row['id'];?>" class="character" style="left:<?php echo 201 * ($row['id'] - 1);?>px;">
		<img class="crttop" src="./images/data/character/top-<?php echo $row['id'];?>.jpg" />
		<img class="crtimg" src="./images/data/character/<?php echo $row['id'];?>.png" />
		<div class="crtins"><?php echo $row['intro'];?></div>
	</div>
<?php
}
?>
	</div>
	<a id="crtlscroll" href="javascript:;" title="向左滚动" hidefocus="true">向左滚动</a>
	<a id="crtrscroll" href="javascript:;" title="向右滚动" hidefocus="true">向右滚动</a>
</div>
<div id="crtmore">
<?php
for($i = 1; $i <= 5; $i ++) {
	$query = $db->query("SELECT `name`,`intro`,`age`,`identity`,`weapon`,`poem`,`skill`,`attention` FROM `pal4_character` WHERE `id`='$i';");
	$row = $db->fetch_array($query);
	$row['intro'] = str_replace("\r\n", "<br />* ", $row['intro']);
	$row['poem'] = str_replace("\r\n", "<br />", $row['poem']);
	$row['attention'] = str_replace("\r\n", "<br />", $row['attention']);
?>
<div class="crtmore" id="crtmore<?php echo $i;?>">
	<div class="crtmorehead c"><?php echo $row['name'];?></div>
	<table class="crtmoremain">
		<tr>
		<td style="height:210px;width:180px;"><img src="./images/data/character/<?php echo $i;?>.png" /></td>
		<td style="vertical-align:middle;width:200px;" class="c"><?php echo $row['poem'];?></td>
		<td rowspan="2">
		<?php if($i != 5) {?>
		<br /><strong>年龄：</strong><?php echo $row['age'];?><br />
		<?php }?>
		<br /><strong>身份：</strong><?php echo $row['identity'];?><br />
		<br /><strong>武器：</strong><?php echo $row['weapon'];?>
		<?php if($i != 5) {?>
		<br /><br /><strong>迷宫技：</strong><?php echo $row['skill'];?>
		<br /><br /><strong>注意：</strong><br /><?php echo $row['attention'];?>
		<?php }?>
		</td>
		</tr>
		<tr>
		<td colspan="2" style="height:220px;width:380px;">* <?php echo $row['intro'];?></td>
		</tr>
	</table>
	<a class="exitcrtmore" href="javascript:;" title="关闭">关闭</a>
</div>
<?php
}
?>
</div>