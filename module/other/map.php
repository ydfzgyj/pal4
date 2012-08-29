<?php
require '../../source/core.inc.php';
$query = $db->query("SELECT `id`,`name`,`number`,`line` FROM `pal4_map` WHERE `id`='$_gp[id]';");
if($db->num_rows($query) == 0) die('404');
else $row = $db->fetch_array($query);
echo $row['name'],'|';
?>
<div id="mapbg" style="background:url(./images/map/<?php echo $row['number'];?>.jpg);">
<?php
if($row['line'] != '') {
?>
	<div id="mapcanvas"><canvas id="canvas" width="1000" height="750" data="<?php echo $row['line'];?>"></canvas></div>
<?php
}
$query = $db->query("SELECT `name`,`showname`,`type`,`left`,`top` FROM `pal4_point` WHERE `belong`='$_gp[id]';");
while($row = $db->fetch_array($query)) {
	if($row['showname'] == '') $row['showname'] = $row['name'];
	echo '<div style="left:',$row['left'],'px;top:',$row['top'],'px;">';
	if($row['type'] == 'obj') {
		$nameArr = explode('，', $row['name']);
		$shownameArr = explode('，', $row['showname']);
		for($i = 0; $i < count($nameArr); $i ++) {
			$name = $nameArr[$i];
			$showname = $shownameArr[$i];
			$query2 = $db->query("SELECT `id` FROM `pal4_object` WHERE `name`='$name';");
			if($db->num_rows($query2)) {
				$row2 = $db->fetch_array($query2);
				$row['name'] = str_replace($name, '<a href="#!/data/object/'.$row2['id'].'" target="_blank" class="mapobj">'.$showname.'</a>', $row['name']);
			} else {
				$query2 = $db->query("SELECT `id` FROM `pal4_equipment` WHERE `name`='$name';");
				if($db->num_rows($query2)) {
					$row2 = $db->fetch_array($query2);
					$row['name'] = str_replace($name, '<a href="#!/data/equipment/'.$row2['id'].'" target="_blank" class="mapobj">'.$showname.'</a>', $row['name']);
				} else {
					$row['name'] = str_replace($name, '<a href="javascript:;" class="mapobj nopoint">'.$showname.'</a>', $row['name']);
				}
			}
		}
		echo str_replace('，', '', $row['name']);
	} elseif($row['type'] == 'img') {
		echo '<img src="./images/map/icon/',$row['name'],'.png" />';
	} elseif($row['type'] == 'not') {
		echo '<span class="mapnote" style="color:',$row['showname'],'">',$row['name'],'</span>';
	} elseif($row['type'] == 'map') {
		$nameArr = explode('，', $row['name']);
		$shownameArr = explode('，', $row['showname']);
		for($i = 0; $i < count($nameArr); $i ++) {
			$name = $nameArr[$i];
			$showname = $shownameArr[$i];
			$query2 = $db->query("SELECT `id` FROM `pal4_map` WHERE `name`='$name';");
			if($db->num_rows($query2)) {
				$row2 = $db->fetch_array($query2);
				$row['name'] = str_replace($name, '<a href="#!/map/'.$row2['id'].'" class="mapmap link">'.$showname.'</a>', $row['name']);
			} else {
				$row['name'] = str_replace($name, '<a href="javascript:;" class="mapmap nopoint">'.$showname.'</a>', $row['name']);
			}
		}
		echo str_replace('，', '', $row['name']);
	} elseif($row['type'] == 'asm') {
		$nameArr = explode('，', $row['name']);
		$shownameArr = explode('，', $row['showname']);
		for($i = 0; $i < count($nameArr); $i ++) {
			$name = $nameArr[$i];
			$showname = $shownameArr[$i];
			$query2 = $db->query("SELECT `id` FROM `pal4_assignment` WHERE `name`='$name';");
			if($db->num_rows($query2)) {
				$row2 = $db->fetch_array($query2);
				$row['name'] = str_replace($name, '<a href="#!/data/assignment/'.$row2['id'].'" target="_blank" class="mapasm">'.$showname.'</a>', $row['name']);
			}
		}
		echo str_replace('，', '', $row['name']);
	} elseif($row['type'] == 'mst') {
		$nameArr = explode('，', $row['name']);
		$shownameArr = explode('，', $row['showname']);
		for($i = 0; $i < count($nameArr); $i ++) {
			$name = $nameArr[$i];
			$showname = $shownameArr[$i];
			$query2 = $db->query("SELECT `id` FROM `pal4_monster` WHERE `name`='$name';");
			if($db->num_rows($query2)) {
				$row2 = $db->fetch_array($query2);
				$row['name'] = str_replace($name, '<a href="#!/data/monster/'.$row2['id'].'" target="_blank" class="mapmst">'.$showname.'</a>', $row['name']);
			}
		}
		echo str_replace('，', '', $row['name']);
	} elseif($row['type'] == 'btn') {
		$query2 = $db->query("SELECT `id` FROM `pal4_map` WHERE `name`='$row[showname]';");
		$row2 = $db->fetch_array($query2);
		echo '<a href="#!/map/',$row2['id'],'" class="mapbtn link" style="background:url(./images/map/icon/',$row['name'],'.png);" title="',$row['showname'],'"></a>';
	} elseif($row['type'] == 'box') {
		$query2 = $db->query("SELECT `id` FROM `pal4_map` WHERE `name`='$row[name]';");
		$row2 = $db->fetch_array($query2);
		$shownameArr = explode('，', $row['showname']);
		if($shownameArr[0] == '') $shownameArr[0] = $row['name'];
		echo '<div class="mapbox c" style="height:',$shownameArr[2],'px;line-height:',$shownameArr[2],'px;width:',$shownameArr[1],'px;"><a href="#!/map/',$row2['id'],'" class="mapmap link">',$shownameArr[0],'</a></div>';
	}
	echo '</div>';
}
?>
	<a href="javascript:;" id="mapicon" class="mapbtn" style="background:url(./images/map/icon/toggle.png);" title="切换注记显示"></a>
	<a href="javascript:;" id="maplegend" class="mapbtn" style="background:url(./images/map/icon/legend.png);" title="图例"></a>
	<div id="legendmain">
		<div id="legendtitle" class="c">图例</div>
		<table id="legendcontent" class="c">
			<tr><th style="width:80px;">图例</th><th style="width:85px;">名称</th><th>说明</th></tr>
			<tr><td><img src="./images/map/icon/drugstore.png" /></td><td>药店</td><td>可以购买回复类物品及各种香，并可出售身上的物品</td></tr>
			<tr><td><img src="./images/map/icon/inn.png" /></td><td>客栈</td><td>可以将队伍中所有人物的精、神回复至满值</td></tr>
			<tr><td><img src="./images/map/icon/restaurant.png" /></td><td>食品店</td><td>可以购买食物，并可出售身上的物品</td></tr>
			<tr><td><img src="./images/map/icon/grocer.png" /></td><td>杂货店</td><td>可以购买攻击、辅助类物品、防具及其图谱、佩戴，并可出售身上的物品和装备</td></tr>
			<tr><td><img src="./images/map/icon/forger.png" /></td><td>武器店</td><td>可以购买攻击类物品、矿石材料、武器及其图谱并进行铸造，并可出售身上的物品和装备</td></tr>
			<tr><td><img src="./images/map/icon/treasure.png" /></td><td>宝箱</td><td>部分宝箱为隐藏宝箱，需要韩菱纱带队接近才会显现出来；部分宝箱只有韩菱纱才能打开</td></tr>
			<tr><td><img src="./images/map/icon/ore.png" /></td><td>矿石出现点</td><td>会出现矿石，挖取后3分钟产生出新的矿石；部分矿石只有慕容紫英才能挖取</td></tr>
			<tr><td><img src="./images/map/icon/entry.png" /></td><td>地图切换点</td><td>由此处可通往其他地图，点击其旁边的场景名可以切换至对应地图</td></tr>
			<tr><td><img src="./images/map/icon/mechanism.png" /></td><td>机关</td><td>在迷宫的路上会出现机关，需要破解机关才可以通过</td></tr>
			<tr><td><img src="./images/map/icon/jump.png" /></td><td>跳跃点</td><td>按空格键进行跳跃才可以通过的地点</td></tr>
			<tr><td><img src="./images/map/icon/teleport.png" /></td><td>传送点</td><td>可以由传送点传送至此场景的其他位置</td></tr>
			<tr><td><img src="./images/map/icon/drama.png" /></td><td>剧情发生点</td><td>显示主线和支线剧情的发生点</td></tr>
			<tr><td><img src="./images/map/icon/savespot.png" /></td><td>存盘点</td><td>可以在迷宫中存档，并且可以将队伍中所有人物的精、神回复至满值</td></tr>
			<tr><td><img src="./images/map/icon/common.png" /></td><td>普通敌人</td><td>普通敌人会在标示的点附近移动，接触会触发战斗</td></tr>
			<tr><td><img src="./images/map/icon/special.png" /></td><td>特殊敌人</td><td>特殊敌人会在标示的点附近移动，接触会触发特殊情况，详情可见地图附注</td></tr>
			<tr><td><img src="./images/map/icon/boss.png" width="16" /></td><td>BOSS</td><td>在剧情中出现的BOSS位置标注于剧情发生点旁</td></tr>
			<tr><td><a href="javascript:;" class="mapobj">止血草</a></td><td>物品名称</td><td>场景中可拾得的物品名称，点击可打开物品的详细介绍页；带*号表示位于室内</td></tr>
			<tr><td><a href="javascript:;" class="mapmap">石沉溪洞</a></td><td>场景名称</td><td>当前场景通往的场景名称或建筑名称，若为商店名称可点击打开商店的售卖列表</td></tr>
			<tr><td><a href="javascript:;" class="mapasm">进洞抓山猪</a></td><td>剧情名称</td><td>场景中发生的剧情名称，点击可打开剧情的详细介绍页；带*号表示为委托任务</td></tr>
			<tr><td><a href="javascript:;" class="mapmst">朱蛤</a></td><td>怪物名称</td><td>迷宫中出现的怪物名称，点击可打开怪物的详细介绍页</td></tr>
		</table>
		<a id="exitlegend" href="javascript:;" title="关闭">关闭</a>
	</div>
	<a href="javascript:;" id="mapgoto" class="mapbtn" style="background:url(./images/map/icon/goto.png);" title="御剑前往"></a>
<?php
	$query = $db->query("SELECT `id`,`name` FROM `pal4_map`;");
	while($row = $db->fetch_array($query)) {
		$mapArr[$row['id']] = $row['name'];
	}
	$gotoArr = array(
		1,
		array('青鸾峰', 2, 6),
		array('紫云架', 7, 8),
		9,
		array('巢湖', 10, 13),
		array('寿阳', 14, 16),
		17,
		array('女萝岩', 18, 30),
		array('淮南王陵', 31, 43),
		array('陈州', 44, 53),
		array('播仙镇', 54, 55),
		array('太一仙径', 56, 58),
		array('昆仑琼华派', 59, 78, 222),
		array('须臾幻境', 79, 80),
		array('月牙村', 81, 82),
		array('清风涧', 83, 84),
		array('即墨', 85, 87),
		array('炎帝神农洞', 88, 90),
		array('不周山', 91, 93),
		array('鬼界', 94, 97),
		98,
		array('封神陵', 99, 103, 90),
		array('幻瞑界', 104, 107, 90)
	);
?>
	<ul id="mapgotolist">
		<li><a href="#!/index" class="mapmap link">返回首页</a></li>
<?php
	foreach($gotoArr as $value) {
		if(is_array($value)) {
			echo '<li>', $value[0], '<ul class="mapgotomain"', isset($value[3]) ? ' style="margin-top:-'.$value[3].'px"' : '', '>';
			for($i = $value[1]; $i <= $value[2]; $i ++) {
				echo '<li><a href="#!/map/', $i, '" class="mapmap link">', $mapArr[$i], '</a></li>';
			}
			echo '</ul></li>';
		} else {
			echo '<li><a href="#!/map/', $value, '" class="mapmap link">', $mapArr[$value], '</a></li>';
		}
	}
?>
	</ul>
</div>