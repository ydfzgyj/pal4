<?php
require './source/core.inc.php';
if(isset($_POST['pid'])) {
	for($i = 0; $i < count($_POST['pid']); $i ++) {
		$db->query("UPDATE `pal4_point` SET `name`='".$_POST['name'][$i]."',`showname`='".$_POST['showname'][$i]."',`type`='".$_POST['type'][$i]."',`left`=".$_POST['left'][$i].",`top`=".$_POST['top'][$i]." WHERE `id`='".$_POST['pid'][$i]."';");
	}
}
if(isset($_POST['newname'])) {
	for($i = 0; $i < count($_POST['newname']); $i ++) {
		$db->query("INSERT INTO `pal4_point`(`name`,`showname`,`type`,`belong`,`left`,`top`) VALUES('".$_POST['newname'][$i]."', '".$_POST['newshowname'][$i]."', '".$_POST['newtype'][$i]."', ".$_gp['id'].", ".$_POST['newleft'][$i].", ".$_POST['newtop'][$i].");");
	}
}
$query = $db->query("SELECT `id`,`name`,`number`,`line` FROM `pal4_map` WHERE `id`='$_gp[id]' ORDER BY `id`;");
if($db->num_rows($query) == 0) die('404');
else $row = $db->fetch_array($query);
?>
<link rel="stylesheet" href="./script/core.css" />
<style>
#edit{background:#fff;height:750px;left:1000px;overflow:scroll;position:absolute;top:0;width:350px;}
#words{background:#fff;height:750px;left:1350px;position:absolute;top:0;width:80px;}
#addrow, button{border:1px solid #000;border-radius:2px;cursor:pointer;font-size:14px;padding:3px 10px;}
.w1{width:100px;}.w2{width:40px;}
</style>
<script src="./script/jquery.js"></script>
<script src="./script/jqueryui.js"></script>
<script>
$(function(){
	pnew = 1;
	var cv = document.getElementById('canvas');
	if($('#canvas').length > 0) {
		if(!cv.getContext) cv = G_vmlCanvasManager.initElement(cv);
		var cxt = cv.getContext('2d'),
			line = $('#canvas').attr('data').split(';');
		for(var i = 0; i < line.length; i ++) {
			line[i] = line[i].split(',');
			if(line[i][0] == 1 || line[i][0] == 2) {
				cxt.moveTo(line[i][1], line[i][2]);
				for(var j = 3; j < line[i].length; j += 2) {
					cxt.lineTo(line[i][j], line[i][j + 1]);
				}
				if(line[i][0] == 1) {
					var j = line[i].length - 1, len = 15,
						x1 = parseInt(line[i][j - 1]), y1 = parseInt(line[i][j]),
						x2 = parseInt(line[i][j - 3]), y2 = parseInt(line[i][j - 2]),
						alpha = Math.atan(Math.abs(y2 - y1) / Math.abs(x2 - x1));
					if(x1 > x2 && y1 <= y2) alpha = Math.PI - alpha;
					else if(x1 > x2 && y1 > y2) alpha += Math.PI;
					else if(x1 <= x2 && y1 > y2) alpha = Math.PI * 2 - alpha;
					var beta = alpha - Math.PI / 4, gamma = alpha + Math.PI / 4;
					cxt.lineTo(Math.cos(beta) * len + x1, Math.sin(beta) * len + y1);
					cxt.moveTo(x1, y1);
					cxt.lineTo(Math.cos(gamma) * len + x1, Math.sin(gamma) * len + y1);
				}
				cxt.lineWidth = 5;
				cxt.lineJoin = 'round';
				cxt.lineCap = 'round';
				cxt.strokeStyle = '#f00';
				cxt.stroke();
			} else if(line[i][0] == 3) {
				cxt.font = '20px 微软雅黑';
				cxt.lineWidth = 3;
				cxt.fillStyle = '#f00';
				for(var j = 1; j < line[i].length; j += 3) {
					cxt.fillText(line[i][j + 2], line[i][j], line[i][j + 1]);
				}
			}
		}
	}
	$('#mapicon').on('click', function() { $('#mapbg').find('div').toggle(); $('#legendmain').toggle(); });
	$('#mapgoto').on('click', function() { $('#mapgotolist').toggle(); });
	$('#mapbg').on('focus', 'a', function() { $(this).blur(); })
		.on('mouseenter mouseleave', 'li', function(e) { $(this).find('ul').toggle(); });
	$('#edit').on('blur', 'input', function(e) {
		var id = $(e.target).attr('id').split('-')[1],
			name = $('#name-' + id).val(),
			showname = $('#showname-' + id).val(),
			type = $('#type-' + id).val(),
			left = $('#left-' + id).val(),
			top = $('#top-' + id).val(),
			point = $('#p-' + id);
		if(showname == '') showname = name;
		switch(type) {
		case 'img':
			point.css({'left': left, 'top': top}).html('<img src="./images/map/icon/' + name + '.png" />');
		break;
		case 'not':
			point.css({'left': left, 'top': top}).html('<span class="mapnote" style="color:' + showname + '">' + name + '</span>');
		break;
		case 'obj':
			var nameArr = name.split('，');
			var shownameArr = showname.split('，');
			for(var i = 0; i < nameArr.length; i ++) {
				pname = nameArr[i];
				pshowname = shownameArr[i];
				name = name.replace(pname, '<a href="javascript:;" class="mapobj nopoint">' + pshowname + '</a>');
			}
			point.css({'left': left, 'top': top}).html(name.replace(/，/g, ''));
		break;
		case 'map':
			var nameArr = name.split('，');
			var shownameArr = showname.split('，');
			for(var i = 0; i < nameArr.length; i ++) {
				pname = nameArr[i];
				pshowname = shownameArr[i];
				name = name.replace(pname, '<a href="javascript:;" class="mapmap nopoint">' + pshowname + '</a>');
			}
			point.css({'left': left, 'top': top}).html(name.replace(/，/g, ''));
		break;
		case 'asm':
			var nameArr = name.split('，');
			var shownameArr = showname.split('，');
			for(var i = 0; i < nameArr.length; i ++) {
				pname = nameArr[i];
				pshowname = shownameArr[i];
				name = name.replace(pname, '<a href="javascript:;" class="mapasm nopoint">' + pshowname + '</a>');
			}
			point.css({'left': left, 'top': top}).html(name.replace(/，/g, ''));
		break;
		case 'mst':
			var nameArr = name.split('，');
			var shownameArr = showname.split('，');
			for(var i = 0; i < nameArr.length; i ++) {
				pname = nameArr[i];
				pshowname = shownameArr[i];
				name = name.replace(pname, '<a href="javascript:;" class="mapmst nopoint">' + pshowname + '</a>');
			}
			point.css({'left': left, 'top': top}).html(name.replace(/，/g, ''));
		break;
		case 'btn':
			point.css({'left': left, 'top': top}).html('<a href="javascript:;" class="mapbtn nopoint" style="background:url(./images/map/icon/' + name + '.png);" title="' + showname + '"></a>');
		break;
		case 'box':
			var shownameArr = showname.split('，');
			if(shownameArr[0] == '') shownameArr[0] = name;
			point.css({'left': left, 'top': top}).html('<div class="mapbox c" style="height:' + shownameArr[2] + 'px;line-height:' + shownameArr[2] + 'px;width:' + shownameArr[1] + 'px;"><a href="javascript:;" class="mapmap link">' + shownameArr[0] + '</a></div>');
		break;
		}
	});
	$('.p').draggable({
		stop: function(){
			var id = $(this).attr('id').substr(2);
			$('#left-' + id).val($(this).position().left);
			$('#top-' + id).val($(this).position().top);
		},
		containment: 'parent'
	});
	$('#addrow').on('click', function() {
		var newpoint = '<tr><td><input id="name-new' + pnew + '" name="newname[]" value="" class="w1" /></td>' +
		'<td><input id="showname-new' + pnew + '" name="newshowname[]" value="" class="w1" /></td>' +
		'<td><input id="type-new' + pnew + '" name="newtype[]" value="" class="w2" /></td>' +
		'<td><input id="left-new' + pnew + '" name="newleft[]" value="0" class="w2" /></td>' +
		'<td><input id="top-new' + pnew + '" name="newtop[]" value="0" class="w2" /></td></tr>';
		$('#table').append(newpoint);
		$('#mapbg').append('<div id="p-new' + pnew + '" class="p"></div>');
		$('#p-new' + pnew).draggable({
			stop: function(){
				var id = $(this).attr('id').substr(2);
				$('#left-' + id).val($(this).position().left);
				$('#top-' + id).val($(this).position().top);
			},
			containment: 'parent'
		});
		pnew ++;
	});
});
</script>
<div id="mapbg" style="background:url(./images/map/<?php echo $row['number'];?>.jpg);">
<?php
if($row['line'] != '') {
?>
	<div id="mapcanvas"><canvas id="canvas" width="1000" height="750" data="<?php echo $row['line'];?>"></canvas></div>
<?php
}
$query = $db->query("SELECT `id`,`name`,`showname`,`type`,`left`,`top` FROM `pal4_point` WHERE `belong`='$_gp[id]';");
while($row = $db->fetch_array($query)) {
	if($row['showname'] == '') $row['showname'] = $row['name'];
	echo '<div id="p-',$row['id'],'" class="p" style="left:',$row['left'],'px;top:',$row['top'],'px;">';
	if($row['type'] == 'obj') {
		$nameArr = explode('，', $row['name']);
		$shownameArr = explode('，', $row['showname']);
		for($i = 0; $i < count($nameArr); $i ++) {
			$name = $nameArr[$i];
			$showname = $shownameArr[$i];
			$row['name'] = str_replace($name, '<a href="javascript:;" class="mapobj nopoint">'.$showname.'</a>', $row['name']);
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
				$row['name'] = str_replace($name, '<a href="./addpoint.php?id='.$row2['id'].'" class="mapmap link">'.$showname.'</a>', $row['name']);
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
			$row['name'] = str_replace($name, '<a href="javascript:;" class="mapasm nopoint">'.$showname.'</a>', $row['name']);
		}
		echo str_replace('，', '', $row['name']);
	} elseif($row['type'] == 'mst') {
		$nameArr = explode('，', $row['name']);
		$shownameArr = explode('，', $row['showname']);
		for($i = 0; $i < count($nameArr); $i ++) {
			$name = $nameArr[$i];
			$showname = $shownameArr[$i];
			$row['name'] = str_replace($name, '<a href="javascript:;" class="mapmst nopoint">'.$showname.'</a>', $row['name']);
		}
		echo str_replace('，', '', $row['name']);
	} elseif($row['type'] == 'btn') {
		$query2 = $db->query("SELECT `id` FROM `pal4_map` WHERE `name`='$row[showname]';");
		$row2 = $db->fetch_array($query2);
		echo '<a href="./addpoint.php?id=',$row2['id'],'" class="mapbtn link" style="background:url(./images/map/icon/',$row['name'],'.png);" title="',$row['showname'],'"></a>';
	} elseif($row['type'] == 'box') {
		$query2 = $db->query("SELECT `id` FROM `pal4_map` WHERE `name`='$row[name]';");
		$row2 = $db->fetch_array($query2);
		$shownameArr = explode('，', $row['showname']);
		if($shownameArr[0] == '') $shownameArr[0] = $row['name'];
		echo '<div class="mapbox c" style="height:',$shownameArr[2],'px;line-height:',$shownameArr[2],'px;width:',$shownameArr[1],'px;"><a href="./addpoint.php?id=',$row2['id'],'" class="mapmap link">',$shownameArr[0],'</a></div>';
	}
	echo '</div>';
}
?>
	<a href="javascript:;" id="mapicon" class="mapbtn" style="background:url(./images/map/icon/toggle.png);" title="切换注记显示"></a>
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
<?php
	foreach($gotoArr as $value) {
		if(is_array($value)) {
			echo '<li>', $value[0], '<ul class="mapgotomain"', isset($value[3]) ? ' style="margin-top:-'.$value[3].'px"' : '', '>';
			for($i = $value[1]; $i <= $value[2]; $i ++) {
				echo '<li><a href="./addpoint.php?id=', $i, '" class="mapmap link">', $mapArr[$i], '</a></li>';
			}
			echo '</ul></li>';
		} else {
			echo '<li><a href="./addpoint.php?id=', $value, '" class="mapmap link">', $mapArr[$value], '</a></li>';
		}
	}
?>
	</ul>
</div>
<div id="edit">
<?php
$query = $db->query("SELECT `id`,`name`,`showname`,`type`,`left`,`top` FROM `pal4_point` WHERE `belong`='$_gp[id]';");
?>
	<form action="./addpoint.php?id=<?=$_gp['id']?>" method="POST">
	<span id="addrow" />新增</span>
	<table id="table">
	<tr><th>name</th><th>showname</th><th>type</th><th>left</th><th>top</th></tr>
<?php
while($row = $db->fetch_array($query)) {
?>
	<input id="pid-<?=$row['id']?>" name="pid[]" type="hidden" value="<?=$row['id']?>" />
	<tr><td><input id="name-<?=$row['id']?>" name="name[]" value="<?=$row['name']?>" class="w1" /></td>
	<td><input id="showname-<?=$row['id']?>" name="showname[]" value="<?=$row['showname']?>" class="w1" /></td>
	<td><input id="type-<?=$row['id']?>" name="type[]" value="<?=$row['type']?>" class="w2" /></td>
	<td><input id="left-<?=$row['id']?>" name="left[]" value="<?=$row['left']?>" class="w2" /></td>
	<td><input id="top-<?=$row['id']?>" name="top[]" value="<?=$row['top']?>" class="w2" /></td></tr>
<?php
}
?>
	</table>
	<button type="submit" />提交</button>
	</form>
</div>
<div id="words">
boss<br />common<br />day<br />drama<br />drugstore<br />entry<br />forger<br />grocer<br />inn<br />jump<br />maze<br />mechanism<br />night<br />ore<br />original<br />restaurant<br />return<br />ruin<br />savespot<br />special<br />teleport<br />treasure<br /><br />
asm<br />box<br />btn<br />img<br />map<br />mst<br />not<br />obj
</div>