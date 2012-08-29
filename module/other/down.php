<?php
require '../../source/core.inc.php';
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id` FROM `pal4_down` WHERE `id`='$_gp[id]';");
	if($db->num_rows($query) == 0) die('404');
}
?>
<div id="guidemain">
<?php
if(isset($_gp['id'])) {
	$query = $db->query("SELECT `id`,`title`,`content`,`author` FROM `pal4_down` WHERE `id`='$_gp[id]';");
	$row = $db->fetch_array($query);
?>
	<div id="guidecontent" class="guidecontent">
		<a class="link" href="#!/down">返回列表</a>
		<h1><?php echo $row['title'];?></h1>
		<p class="c"><small><?php echo '作者：',$row['author']?></small></p><br />
		<?php echo $row['content'];?>
		<br /><br />
		<a class="link" href="#!/down">返回列表</a>
<?php
} else {
	$query = $db->query("SELECT `id`,`title`,`author` FROM `pal4_down` ORDER BY `id` DESC;");
?>
	<div id="guidecontent" class="guidelist">
	<table>
		<tr><th style="width:500px;">标题</th><th>作者</th></tr>
<?php
	while($row = $db->fetch_array($query)) {
	?>
		<tr><td><a class="link" href="#!/down/<?php echo $row['id'];?>"><?php echo $row['title'];?></a></td><td><?php echo $row['author'];?></td></tr>
<?php
	}
	echo '</table>';
}
?>
	</div>
</div>
<div id="guidescroll">
	<div class="totop" title="向上滚动">向上滚动</div>
	<div class="scrollbar">
		<div class="scrollto" title="拖拽滚动">拖拽滚动</div>
	</div>
	<div class="tobottom" title="向下滚动">向下滚动</div>
</div>
<a id="exittomain" class="link" href="#!/index" title="返回首页">返回首页</a>
<img src="./images/other/down.jpg" class="preload" />