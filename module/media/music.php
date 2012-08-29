<?php
require '../../source/core.inc.php';
if($_gp['id'] != 1 && $_gp['id'] != 2 && $_gp['id'] != 3) die('404');
?>
<div id="guidesubmenu">
	<a id="guidesubmenu1" class="guidesubmenu c link" href="#!/media/music/1" style="left:0;<?php if($_gp['id'] == 1) echo 'display:none;';?>">游戏原声</a>
	<a id="guidesubmenu2" class="guidesubmenu c link" href="#!/media/music/2" style="left:120px;<?php if($_gp['id'] == 2) echo 'display:none;';?>">同人音乐</a>
	<a id="guidesubmenu3" class="guidesubmenu c link" href="#!/media/music/playing" style="left:240px;<?php if($_gp['id'] == 3) echo 'display:none;';?>">正在播放</a>
	<div id="musicsubmenu1" class="guidesubmenubig c" style="left:0;<?php if($_gp['id'] == 1) echo 'display:block;';?>">游戏原声</div>
	<div id="musicsubmenu2" class="guidesubmenubig c" style="left:120px;<?php if($_gp['id'] == 2) echo 'display:block;';?>">同人音乐</div>
	<div id="musicsubmenu3" class="guidesubmenubig c" style="left:240px;<?php if($_gp['id'] == 3) echo 'display:block;';?>">正在播放</div>
</div>
<div id="guidemain">
	<div id="guidecontent" class="music">
		<ul id="playlist"></ul>
	</div>
	<div id="lrc"></div>
</div>
<script>
$(function(){
<?php
	$query = $db->query("SELECT `id`,`name`,`intro`,`site` FROM `pal4_media` WHERE `id` LIKE '1___' ORDER BY `id`;");
	$songlist = '';
	while($row = $db->fetch_array($query)) {
		$row['intro'] = str_replace("\r\n", "<br />", $row['intro']);
		$songlist .= ','.$row['id'].':{name:"'.$row['name'].'",mp3:"'.$row['site'].'",lrc:"'.$row['intro'].'"}';
	}
?>
	songList = {<?php echo substr($songlist, 1);?>};
<?php
if($_gp['id'] == 3) {
?>
	var listItem = '<li><a href="javascript:;" id="jpminusall" class="jp-minus jpbg" title="清空播放列表" style="display:block;"></a><strong>清空播放列表</strong></li>';
	$.each(playList, function(key, val) {
		listItem += '<li><a href="javascript:;" id="jpminus' + key + '" class="jp-minus jpbg" title="从播放列表删除" style="display:block;"></a>';
		listItem += '<a href="' + songList[key].mp3 + '" class="jp-download jpbg" target="_blank" title="下载"></a><a href="javascript:;" id="jpitem' + key + '" class="jpitem">' + songList[key].name + '</a></li>';
	});
	$('#playlist').html(listItem);
	$('#guidecontent').height($('#playlist').height() + 2);
	$('#guidescroll').scroll($('#guidecontent'));
	if(playItem != -1) {
		$('#jpitem' + playSongs[playItem]).parent().addClass('jp-current');
		$('#lrc').html(songList[playSongs[playItem]].lrc);
	}
	$('#playlist').on('click', '.jpitem', function(e) {
		var index = $(e.target).attr('id').substr(6);
		if(playSongs[playItem] != index) bgmControl.change(index);
		else $('#bgm').jPlayer('play');
	}).on('click', '.jp-minus', function(e) {
		var target = $(e.target),
			index = target.attr('id').substr(7);
		if(index == 'all') {
			playItem = -1;
			playSongs = [];
			playList = {};
			$('#playlist').empty();
			$('#bgm').jPlayer('clearMedia');
		} else {
			if(playSongs[playItem] == index) bgmControl.next();
			for(var i = 0; i < playSongs.length; i ++) {
				if(playSongs[i] == index) {
					playItem --;
					playSongs.splice(i, 1);
					delete playList[index];
					target.parent().remove();
					break;
				}
			}
			if(playSongs.length == 0) {
				playItem = -1;
				$('#playlist').empty();
				$('#bgm').jPlayer('clearMedia');
			}
		}
	});
<?php
} else {
?>
	var listItem = '<li><a href="javascript:;" id="jpplusall" class="jp-plus jpbg" title="添加以下所有歌曲到播放列表"></a><a href="javascript:;" id="jpminusall" class="jp-minus jpbg" title="从播放列表删除以下所有歌曲"></a><strong>添加/删除以下所有歌曲</strong></li>';
	$.each(songList, function(key, val) {
		if(key.substr(1,1) == <?php echo $_gp['id'];?>) {
			listItem += '<li><a href="javascript:;" id="jpplus' + key + '" class="jp-plus jpbg" title="添加到播放列表"';
			if($.inArray(key, playSongs) >= 0) listItem += ' style="display:none;"';
			listItem += '></a>';
			listItem += '<a href="javascript:;" id="jpminus' + key + '" class="jp-minus jpbg" title="从播放列表删除"';
			if($.inArray(key, playSongs) >= 0) listItem += ' style="display:block;"';
			listItem += '></a>';
			listItem += '<a href="' + val.mp3 + '" class="jp-download jpbg" target="_blank" title="下载"></a><a href="javascript:;" id="jpitem' + key + '" class="jpitem">' + val.name + '</a></li>';
		}
	});
	$('#playlist').html(listItem);
	$('#guidecontent').height($('#playlist').height() + 2);
	$('#guidescroll').scroll($('#guidecontent'));
	if(playItem != -1) {
		$('#jpitem' + playSongs[playItem]).parent().addClass('jp-current');
		$('#lrc').html(songList[playSongs[playItem]].lrc);
	}
	$('#playlist').on('click', '.jpitem', function(e) {
		var index = $(e.target).attr('id').substr(6);
		if($.inArray(index, playSongs) >= 0) {
			if(playSongs[playItem] != index) bgmControl.change(index);
			else $('#bgm').jPlayer('play');
		} else {
			playSongs.push(index);
			playList[index] = {name: songList[index].name, mp3: songList[index].mp3};
			bgmControl.change(index);
			$('#jpplus' + index).hide();
			$('#jpminus' + index).show();
		}
	}).on('click', '.jp-plus', function(e) {
		var target = $(e.target),
			index = target.attr('id').substr(6);
		if(index == 'all') {
			$.each(songList, function(key, val) {
				if($.inArray(key, playSongs) < 0 && key.substr(1,1) == <?php echo $_gp['id'];?>) {
					playSongs.push(key);
					playList[key] = {name: songList[key].name, mp3: songList[key].mp3};
					$('#jpplus' + key).hide();
					$('#jpminus' + key).show();
				}
			});
			target.hide();
			$('#jpminusall').show();
			if(playItem == -1) bgmControl.change(playSongs[0]);
		} else {
			playSongs.push(index);
			playList[index] = {name: songList[index].name, mp3: songList[index].mp3};
			target.hide();
			$('#jpminus' + index).show();
			if(playItem == -1) bgmControl.change(index);
		}
	}).on('click', '.jp-minus', function(e) {
		var target = $(e.target),
			index = target.attr('id').substr(7);
		if(index == 'all') {
			var wait = 0;
			$.each(songList, function(key, val) {
				if($.inArray(key, playSongs) >= 0 && key.substr(1,1) == <?php echo $_gp['id'];?>) {
					if(playSongs[playItem] == key && wait == 0) wait = key;
					for(var i = 0; i < playSongs.length; i ++) {
						if(playSongs[i] == key) {
							playSongs.splice(i, 1);
							delete playList[key];
							break;
						}
					}
					$('#jpminus' + key).hide();
					$('#jpplus' + key).show();
				}
			});
			target.hide();
			$('#jpplusall').show();
			if(wait > 0) {
				$('#jpitem' + wait).parent().removeClass('jp-current');
				if(playSongs.length == 0) {
					$('#bgm').jPlayer('clearMedia');
					playItem = -1;
				} else {
					bgmControl.change(playSongs[0]);
				}
			}
		} else {
			if(playSongs[playItem] == index) bgmControl.next();
			for(var i = 0; i < playSongs.length; i ++) {
				if(playSongs[i] == index) {
					playItem --;
					playSongs.splice(i, 1);
					delete playList[index];
					break;
				}
			}
			if(playSongs.length == 0) {
				$('#jpitem' + index).parent().removeClass('jp-current');
				$('#bgm').jPlayer('clearMedia');
				$('#jpminusall').hide();
				$('#jpplusall').show();
				playItem = -1;
			}
			target.hide();
			$('#jpplus' + index).show();
		}
	});
<?php
	if(isset($_gp['play'])) echo '$("#jpitem'.$_gp['play'].'").click();';
}
?>
});
function playListChange(index) {
	$('#jpitem' + playSongs[playItem]).parent().removeClass('jp-current');
	$('#jpitem' + index).parent().addClass('jp-current');
	$('#lrc').html(songList[index].lrc);
}
</script>