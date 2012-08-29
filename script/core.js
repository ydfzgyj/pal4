$(function() {
	ajaxGlobal = [];
	timer = null;
	//背景音乐
	playItem = 0;
	playSongs = ['1101'];
	playList = {'1101': {name: 'P01-1 回梦游仙·原版', mp3: 'http://storage.live.com/items/A1E9796EF74E34CD!131?a.mp3'}};
	loopMode = 1;
	$('#bgm').jPlayer({
		ready: function() { $(this).jPlayer('setMedia', { mp3: playList[playSongs[playItem]].mp3 }).jPlayer('play'); },
		ended: function() { bgmControl.next(); },
		timeupdate: function(e) {
			e.jPlayer.status.seekPercent == 100 ? $('#load-percent').text('') : $('#load-percent').text('load:' + Math.floor(e.jPlayer.status.seekPercent) + '%');
		},
		cssSelectorAncestor: '#bgmcp',
		solution: "flash, html",
		wmode: "window",
		swfPath:"./script"
	});
	$('#bgmcp').on('mouseenter', function() {
		$(this).animate({ 'bottom': 0 }, 500);
	}).on('mouseleave', function() {
		if($('#playlist').length == 0 && $(this).css('bottom') == '0px') $(this).animate({ 'bottom': -82 }, 500);
	})
	$('#previous').on('click', function() { bgmControl.prev(); });
	$('#next').on('click', function() { bgmControl.next(); });
	$('#jp-loop').on('click', function() {
		$(this).hide();
		$('#jp-list').show();
		loopMode = 2;
	});
	$('#jp-list').on('click', function() {
		$(this).hide();
		$('#jp-random').show();
		loopMode = 3;
	});
	$('#jp-random').on('click', function() {
		$(this).hide();
		$('#jp-loop').show();
		loopMode = 1;
	});
	//初始化 $.history
	$.history.init(function(url) { $.sendRequest(url); }, { unescape: "/" });
	$('#loading').ajaxStart(function() { $(this).show(); })
		.ajaxStop(function() { $(this).hide(); });
	$('body').on('click', 'a.link', function() {
		$.history.load(this.href.replace(/^.*#/, ''));
		return false;
	}).on('mouseup', function() { clearTimeout(timer); });
});
(function($) {
	bgmControl = {
		change: function(index) {
			if($('#playlist')) playListChange(index);
			for(var i = 0; i < playSongs.length; i ++) {
				if(playSongs[i] == index) {
					playItem = i;
					break;
				}
			}
			$('#bgm').jPlayer('setMedia', { mp3: playList[playSongs[playItem]].mp3 }).jPlayer('play');
			$('#jp-songname').html(playItem == -1 ? '' : playList[playSongs[playItem]].name);
		},
		next: function() {
			var i;
			switch(loopMode) {
				case 1: i = playItem < playSongs.length - 1 ? playItem + 1 : 0; break;
				case 2: i = playItem < playSongs.length - 1 ? playItem + 1 : -1; break;
				case 3:
					do {
						i = Math.floor(Math.random() * playSongs.length);
					} while(i == playItem);
			}
			if(i != -1) bgmControl.change(playSongs[i]);
		},
		prev: function() {
			var i;
			switch(loopMode) {
				case 1: i = playItem >= 1 ? playItem - 1 : playSongs.length - 1; break;
				case 2: i = playItem >= 1 ? playItem - 1 : -1; break;
				case 3:
					do {
						i = Math.floor(Math.random() * playSongs.length);
					} while(i == playItem);
			}
			if(i != -1) bgmControl.change(playSongs[i]);
		}
	};
	var getContent = {
		//主页
		main: function() {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/main/bg.jpg)');
				$('#main').html(response);
				document.title = '仙剑奇侠传四资料站';
			};
			$.exAjax('./module/main.php', '', 'main', exFunction);
		},
		//404
		notfound: function() {
			var exFunction = function(response) {
				$('#main').html(response);
				document.title = '找不到内容 - 仙剑奇侠传四资料站';
			};
			$.exAjax('./module/other/404.php', '', '404', exFunction);
		},
		//攻略秘籍
		guide: function() {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/guide/bg.jpg)');
				$('#main').html(response);
			};
			$.exAjax('./module/guide/index.php', '', 'guide', exFunction);
		},
		//游戏攻略主内容
		guideList: function(mod, id, page) {
			var exFunction = function(response) {
				var title = ['游戏介绍', '游戏攻略', '游戏秘籍'],
					guidemenu = $('#guidemenu');
				if(guidemenu.data('id') != undefined) {
					$('#guidemenu' + guidemenu.data('id')).show();
					$('#guidemenubig' + guidemenu.data('id')).hide();
				}
				$('#guidemenubig' + mod).show();
				$('#guidemenu' + mod).hide();
				guidemenu.data('id', mod);
				$('#guidemainout').html(response);
				document.title = title[mod - 1] + ' - 攻略秘籍 - 仙剑奇侠传四资料站';
				$('#guidescroll').scroll($('#guidecontent'));
			};
			$.exAjax('./module/guide/list.php', 'mod=' + mod + '&id=' + id + '&page=' + page, '', exFunction);
		},
		//游戏资料
		data: function() {
			var exFunction = function(response) {
				$('#main').html(response);
				$.startTime();
			};
			$.exAjax('./module/data/index.php', '', 'data', exFunction);
		},
		//人物资料
		character: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/character/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig1').show();
				$('#datamenu1').hide();
				datamenu.data('id', 1);
				$('#datamain').html(response);
				document.title = '人物资料 - 仙剑奇侠传四资料站';
				$('#crtlscroll').on('click', function() {
					var div = $('#crtmain'),
						left = div.position().left;
					if(left < 0) div.css('left', left + 804);
				});
				$('#crtrscroll').on('click', function() {
					var div = $('#crtmain'),
						left = div.position().left;
					if(left > 769 - div.width()) div.css('left', left - 804);
				});
				$('#crtmain').on('click', '.opencrtmore', function(e) {
					var num = $(e.target).attr('id').substr(11);
					if(num > 0) $('#crtmore' + num).show();
				});
				$('#crtmore').on('click', '.exitcrtmore', function(e) { $(e.target).parent().hide(); });
			};
			$.exAjax('./module/data/character.php', 'id=' + id, 'character', exFunction);
		},
		//物品资料
		object: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/object/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig2').show();
				$('#datamenu2').hide();
				datamenu.data('id', 2);
				$('#datamain').html(response);
				getContent.objectId(id);
				$('#datascroll').scroll($('#datalistmain'));
			};
			$.exAjax('./module/data/object.php', 'id=' + id, 'object', exFunction);
		},
		objectId: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				$('#objimg').css('background', 'url(./images/data/object/' + id + '.jpg)');
				document.title = response[0] + ' - 物品资料 - 仙剑奇侠传四资料站';
				$('#objintro').html(response[1]);
				$('#objeffect').html(response[2]);
				var datalistmain = $('#datalistmain');
				if(datalistmain.data('id') != undefined) $('#object' + datalistmain.data('id')).css('backgroundPosition', '0 0');
				$('#object' + id).css('backgroundPosition', '0 33px');
				datalistmain.data('id', id);
			};
			$.exAjax('./module/data/object.php', 'id2=' + id, '', exFunction);
		},
		//装备资料
		equipment: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/equipment/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig3').show();
				$('#datamenu3').hide();
				datamenu.data('id', 3);
				$('#datamain').html(response);
				getContent.equipmentId(id);
				$('#datascroll').scroll($('#datalistmain'));
			};
			$.exAjax('./module/data/equipment.php', 'id=' + id, 'equipment', exFunction);
		},
		equipmentId: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				$('#eqpimg').css('background', 'url(./images/data/equipment/' + id + '.jpg)');
				document.title = response[0] + ' - 装备资料 - 仙剑奇侠传四资料站';
				$('#eqpintro').html(response[1]);
				$('#eqpeffect').html(response[2]);
				var datalistmain = $('#datalistmain');
				if(datalistmain.data('id') != undefined) $('#equipment' + datalistmain.data('id')).css('backgroundPosition', '0 0');
				$('#equipment' + id).css('backgroundPosition', '0 33px');
				datalistmain.data('id', id);
			};
			$.exAjax('./module/data/equipment.php', 'id2=' + id, '', exFunction);
		},
		//仙术特技
		magic: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/magic/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig4').show();
				$('#datamenu4').hide();
				datamenu.data('id', 4);
				$('#datamain').html(response);
				switch(Math.floor(id / 1000)) {
					case 1: $('#mgcwx1').show(); break;
					case 2: $('#mgcwx2').show(); break;
					case 3: $('#mgcwx3').show(); break;
					case 4: $('#mgcwx4').show(); break;
					case 5: $('#mgcwx5').show(); break;
					case 6: $('#mgcwx').children().show();
				}
				getContent.magicId(id);
				$('#datascroll').scroll($('#datalistmain'));
			};
			$.exAjax('./module/data/magic.php', 'id=' + id, 'magic', exFunction);
		},
		magicId: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				document.title = response[0] + ' - 仙术特技 - 仙剑奇侠传四资料站';
				$('#mgcintro').html(response[1]);
				$('#mgceffect').html(response[2]);
				var datalistmain = $('#datalistmain');
				if(datalistmain.data('id') != undefined) $('#magic' + datalistmain.data('id')).css('backgroundPosition', '0 0');
				$('#magic' + id).css('backgroundPosition', '0 33px');
				datalistmain.data('id', id);
				$('#mgcswfmain').html('<embed src="./images/data/magic/' + id + '.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>');
				$('#openmgcswf').on('click', function() { $('#mgcswf').show(); });
				$('#exitmgcswf').on('click', function() { $('#mgcswfmain').html(''); $('#mgcswf').hide(); });
			};
			$.exAjax('./module/data/magic.php', 'id2=' + id, '', exFunction);
		},
		//熔铸图谱
		casting: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/casting/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig5').show();
				$('#datamenu5').hide();
				datamenu.data('id', 5);
				$('#datamain').html(response);
				getContent.castingId(id);
				$('#datascroll').scroll($('#datalistmain'));
			};
			$.exAjax('./module/data/casting.php', 'id=' + id, 'casting', exFunction);
		},
		castingId: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				$('#cstimg').css('background', 'url(./images/data/casting/' + Math.floor(id / 100) + '.jpg)');
				document.title = response[0] + ' - 熔铸图谱 - 仙剑奇侠传四资料站';
				$('#cstintro').html(response[1]);
				$('#csteffect').html(response[2]);
				var datalistmain = $('#datalistmain');
				if(datalistmain.data('id') != undefined) $('#casting' + datalistmain.data('id')).css('backgroundPosition', '0 0');
				$('#casting' + id).css('backgroundPosition', '0 33px');
				datalistmain.data('id', id);
			};
			$.exAjax('./module/data/casting.php', 'id2=' + id, '', exFunction);
		},
		//任务对话
		assignment: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/assignment/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig6').show();
				$('#datamenu6').hide();
				datamenu.data('id', 6);
				$('#datamain').html(response);
				getContent.assignmentId(id);
				$('#datascroll').scroll($('#datalistmain'));
			};
			$.exAjax('./module/data/assignment.php', 'id=' + id, 'assignment', exFunction);
		},
		assignmentId: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				$('#asmimg').css('background', 'url(./images/data/assignment/' + id + '.jpg)');
				document.title = response[0] + ' - 任务对话 - 仙剑奇侠传四资料站';
				$('#asmintro').html(response[1]);
				$('#asmdialog').html(response[2]);
				var datalistmain = $('#datalistmain');
				if(datalistmain.data('id') != undefined) $('#assignment' + datalistmain.data('id')).css('backgroundPosition', '0 0');
				$('#assignment' + id).css('backgroundPosition', '0 33px');
				datalistmain.data('id', id);
				$('#asmscroll').scroll($('#asmdialogmain'));
			};
			$.exAjax('./module/data/assignment.php', 'id2=' + id, '', exFunction);
		},
		//怪物介绍
		monster: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/data/monster/bg.jpg)');
				var datamenu = $('#datamenu');
				if(datamenu.data('id') != undefined) {
					$('#datamenu' + datamenu.data('id')).show();
					$('#datamenubig' + datamenu.data('id')).hide();
				}
				$('#datamenubig7').show();
				$('#datamenu7').hide();
				datamenu.data('id', 7);
				$('#datamain').html(response);
				getContent.monsterId(id);
				$('#datascroll').scroll($('#datalistmain'));
			};
			$.exAjax('./module/data/monster.php', 'id=' + id, 'monster', exFunction);
		},
		monsterId: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				$('#mstimg').css('background', 'url(./images/data/monster/' + id + '.jpg)');
				document.title = response[0] + ' - 怪物介绍 - 仙剑奇侠传四资料站';
				$('#mstintro').html(response[1]);
				$('#msteffect').html(response[2]);
				var datalistmain = $('#datalistmain');
				if(datalistmain.data('id') != undefined) $('#monster' + datalistmain.data('id')).css('backgroundPosition', '0 0');
				$('#monster' + id).css('backgroundPosition', '0 33px');
				datalistmain.data('id', id);
			};
			$.exAjax('./module/data/monster.php', 'id2=' + id, '', exFunction);
		},
		//站点介绍
		aboutus: function() {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/other/aboutus.jpg)');
				$('#main').html(response);
				document.title = '站点介绍 - 仙剑奇侠传四资料站';
				$('#guidescroll').scroll($('#guidecontent'));
			};
			$.exAjax('./module/other/aboutus.php', '', 'aboutus', exFunction);
		},
		//全站搜索
		search: function(word) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/other/search.jpg)');
				document.title = word == '' ? '全站搜索 - 仙剑奇侠传四资料站' : word + ' - ' + '全站搜索 - 仙剑奇侠传四资料站';
				$('#main').html(response);
				$('#searchbox').focus().on('keydown', function(e) {
					if(e.keyCode == 13) $('#search').click();
				});
				$('#search').on('click', function() {
					var value = $('#searchbox').val();
					if(value != '')
						location.hash = '!/search/' + value;
				});
				$('#guidescroll').scroll($('#guidecontent'));
			};
			$.exAjax('./module/other/search.php', 'word=' + word, 'search', exFunction);
		},
		//游戏地图
		map: function(id) {
			var exFunction = function(response) {
				response = response.split('|');
				$('#main').html(response[1]);
				document.title = response[0] + ' - 游戏地图 - 仙剑奇侠传四资料站';
				var cv = document.getElementById('canvas');
				if($('#canvas').length > 0) {
					if(!cv.getContext) cv = G_vmlCanvasManager.initElement(cv);
					var cxt = cv.getContext('2d'),
						line = $('#canvas').attr('data').split(';');
					for(var i = 0; i < line.length; i ++) {
						line[i] = line[i].split(',');
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
					}
				}
				$('#maplegend').on('click', function() { $('#legendmain').show(); });
				$('#exitlegend').on('click', function() { $('#legendmain').hide(); });
				$('#mapicon').on('click', function() { $('#mapbg').find('div').toggle(); $('#legendmain').toggle(); });
				$('#mapgoto').on('click', function() { $('#mapgotolist').toggle(); });
				$('#mapbg').on('focus', 'a', function() { $(this).blur(); })
					.on('mouseenter mouseleave', 'li', function(e) { $(this).find('ul').toggle(); });
			};
			$.exAjax('./module/other/map.php', 'id=' + id, 'map', exFunction);
		},
		//音画欣赏
		media: function() {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/media/bg.jpg)');
				$('#main').html(response);
			};
			$.exAjax('./module/media/index.php', '', 'media', exFunction);
		},
		//音乐欣赏
		music: function(id) {
			var type = id > 10 ? id.toString().substr(1, 1) : id,
				exData = id > 10 ? 'id=' + type + '&play=' + id : 'id=' + type;
			var exFunction = function(response) {
					var title = ['游戏原声', '同人音乐', '正在播放'],
						mediamenu = $('#mediamenu');
					if(mediamenu.data('id') != undefined) {
						$('#mediamenu' + mediamenu.data('id')).show();
						$('#mediamenubig' + mediamenu.data('id')).hide();
					}
					$('#mediamenubig1').show();
					$('#mediamenu1').hide();
					mediamenu.data('id', 1);
					$('#mediamain').html(response);
					$('#bgmcp').css('bottom', 0);
					document.title = title[type - 1] + ' - 音乐欣赏 - 仙剑奇侠传四资料站';
				};
			$.exAjax('./module/media/music.php', exData, 'music', exFunction);
		},
		//图片欣赏
		pic: function(id) {
			var type = id > 10 ? id.toString().substr(1, 1) : id,
				exFunction = function(response) {
					var title = ['丹青绘卷', '官方壁纸'],
						mediamenu = $('#mediamenu');
					if(mediamenu.data('id') != undefined) {
						$('#mediamenu' + mediamenu.data('id')).show();
						$('#mediamenubig' + mediamenu.data('id')).hide();
					}
					$('#mediamenubig2').show();
					$('#mediamenu2').hide();
					mediamenu.data('id', 2);
					$('#mediamain').html(response);
					document.title = title[type - 1] + ' - 图片欣赏 - 仙剑奇侠传四资料站';
					$('#guidecontent').on('mouseenter', '.medialistone', function() {
						var target = $(this),
							cover = target.find('.mediacover');
						if($('#guidecontent').data('id') != cover.attr('id').substr(3)) cover.css('background','url(./images/media/cover.png)');
						target.find('.mediaintro').show();
					}).on('mouseleave', '.medialistone', function() {
						var target = $(this),
							cover = target.find('.mediacover');
						if($('#guidecontent').data('id') != cover.attr('id').substr(3)) cover.css('background','none');
						target.find('.mediaintro').hide();
					});
					$('#guidescroll').scroll($('#guidecontent'));
					id > 10 ? getContent.picId(id) : getContent.picId('2' + id + '01');
				};
			$.exAjax('./module/media/pic.php', 'id=' + id, 'pic', exFunction);
		},
		picId: function(id) {
			var guidecontent = $('#guidecontent'),
				pic = $('#pic' + id);
			if(guidecontent.data('id') != undefined) $('#pic' + guidecontent.data('id')).css('background','none');
			pic.css('background','url(./images/media/current.png)');
			guidecontent.data('id', id);
			$('#bigpic').html('<img src="' + pic.attr('site') + '" />');
		},
		//视频欣赏
		video: function(id) {
			var type = id > 10 ? id.toString().substr(1, 1) : id,
				exFunction = function(response) {
					var title = ['前尘忆梦', '全剧情MV', 'PAS游戏剧'],
						mediamenu = $('#mediamenu');
					if(mediamenu.data('id') != undefined) {
						$('#mediamenu' + mediamenu.data('id')).show();
						$('#mediamenubig' + mediamenu.data('id')).hide();
					}
					$('#mediamenubig3').show();
					$('#mediamenu3').hide();
					mediamenu.data('id', 3);
					$('#mediamain').html(response);
					document.title = title[type - 1] + ' - 视频欣赏 - 仙剑奇侠传四资料站';
					$('#guidecontent').on('mouseenter', '.medialistone', function() {
						var target = $(this),
							cover = target.find('.mediacover'),
							intro = target.find('.mediaintro').show(),
							height = intro.offset().top + intro.height();
						if($('#guidecontent').data('id') != cover.attr('id').substr(5)) cover.css('background','url(./images/media/cover.png)');
						if(height > 640) intro.css('top', 640 - height).find('.mediaarrow').css('top', height - 630).end().find('.mediaarrowbg').css('top', height - 630);
					}).on('mouseleave', '.medialistone', function() {
						var target = $(this),
							cover = target.find('.mediacover');
						if($('#guidecontent').data('id') != cover.attr('id').substr(5)) cover.css('background','none');
						target.find('.mediaintro').hide().css('top', 0).find('.mediaarrow').css('top', 10).end().find('.mediaarrowbg').css('top', 10);
					});
					$('#guidescroll').scroll($('#guidecontent'));
					if(id > 10) $.videoId(id);
				};
			$.exAjax('./module/media/video.php', 'id=' + id, 'video', exFunction);
		},
		videoId: function(id) {
			var guidecontent = $('#guidecontent'),
				video = $('#video' + id);
			if(guidecontent.data('id') != undefined) $('#video' + guidecontent.data('id')).css('background','none');
			video.css('background','url(./images/media/current.png)');
			guidecontent.data('id', id);
			$('#videoplayer').html('<embed src="' + video.attr('site') + '" width="548" height="450" quality="high" flashvars="isAutoPlay=true" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>');
		},
		//下载专区
		down: function(id) {
			var exFunction = function(response) {
				$('#bg').css('background', 'url(./images/other/down.jpg)');
				$('#main').html(response);
				document.title = '下载专区 - 仙剑奇侠传四资料站';
				$('#guidescroll').scroll($('#guidecontent'));
			};
			$.exAjax('./module/other/down.php', id != 0 ? 'id=' + id : '', 'down', exFunction);
		}
	};
	$.extend({
		//AJAX载入
		exAjax: function(exUrl, exData, exGlobal, exFunction) {
			$.ajax({
				type:'POST',
				url:exUrl,
				data:exData,
				global:$.runGlobal(exGlobal),
				success:function(response) {
					if(response == 404) {
						getContent.notfound();
						return false;
					}
					exFunction(response);
				}
			});
		},
		//判定是否加载Loading界面
		runGlobal: function(name) {
			var exist = $.inArray(name, ajaxGlobal);
			if(exist >= 0 || name == '') {
				return false;
			} else {
				ajaxGlobal.push(name);
				return true;
			}
		},
		//取得当前时间
		startTime: function() {
			var today = new Date(),
				y = today.getFullYear(),
				m = today.getMonth() + 1,
				d = today.getDate(),
				h = today.getHours(),
				i = today.getMinutes(),
				s = today.getSeconds(),
				l = today.getDay();
			switch(l) {
				case 1: l = '星期一'; break;
				case 2: l = '星期二'; break;
				case 3: l = '星期三'; break;
				case 4: l = '星期四'; break;
				case 5: l = '星期五'; break;
				case 6: l = '星期六'; break;
				default: l = '星期日';
			}
			if(i < 10) i = '0' + i;
			if(s < 10) s = '0' + s;
			$('#time').html(y + '/' + m + '/' + d + '<br />' + h + ': ' + i + ': ' + s + '<br />' + l);
			if($('#time').length > 0) setTimeout($.startTime, 500);
		},
		//滚动条
		scroll: function(sdiv, ldiv, add, time) {
			var top = ldiv.position().top;
			top += add;
			if(top > 0) {
				top = 0;
				time = 0;
			} else if(top < ldiv.parent().height() - ldiv.height()) {
				top = ldiv.parent().height() - ldiv.height();
				time = 0;
			}
			ldiv.css('top', top);
			sdiv.css('top', ldiv.position().top * (sdiv.parent().height() - sdiv.height()) / (ldiv.parent().height() - ldiv.height()));
			if(time == 1) timer = setTimeout(function() {$.scroll(sdiv, ldiv, add, 1)}, 200);
		},
		//根据地址进行分发
		sendRequest: function(url) {
			url = url.replace(/\!\//, '');
			var urlArr = url.split('/');
			if(urlArr[0] == '') urlArr[0] = 'index';
			switch(urlArr[0]) {
				case 'guide':
					if($('#guidemain').length == 0) getContent.guide();
					if(urlArr[1] == undefined || urlArr[1] == '') urlArr[1] = 'js';
					switch(urlArr[1]) {
						case 'js':
						case 'gl':
						case 'mj':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1';
							var type = urlArr[2].length > 1 ? urlArr[2].substr(1, 1) : urlArr[2];
							if(urlArr[1] == 'js') var mod = 1;
							else if(urlArr[1] == 'gl') var mod = 2;
							else if(urlArr[1] == 'mj') var mod = 3;
							else { getContent.notfound(); return false; }
							if(urlArr[3] == undefined) urlArr[3] = 1;
							getContent.guideList(mod, urlArr[2], urlArr[3]);
							break;
						default:
							getContent.notfound();
					}
					break;
				case 'data':
					if($('#datamain').length == 0) getContent.data();
					if(urlArr[1] == undefined || urlArr[1] == '') urlArr[1] = 'character';
					switch(urlArr[1]) {
						case 'character':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#character' + urlArr[2]).length == 0) getContent.character(urlArr[2]);
							break;
						case 'object':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1001';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#objmenubig' + urlArr[2].substr(0, 1)).css('display') != 'block') getContent.object(urlArr[2]);
							else getContent.objectId(urlArr[2]);
							break;
						case 'equipment':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '101';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#eqpmenubig' + urlArr[2].substr(0, 1)).css('display') != 'block') getContent.equipment(urlArr[2]);
							else getContent.equipmentId(urlArr[2]);
							break;
						case 'magic':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1001';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#mgcmenubig' + urlArr[2].substr(0, 1)).css('display') != 'block') getContent.magic(urlArr[2]);
							else getContent.magicId(urlArr[2]);
							break;
						case 'casting':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '101';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#cstmenubig' + urlArr[2].substr(0, 1)).css('display') != 'block') getContent.casting(urlArr[2]);
							else getContent.castingId(urlArr[2]);
							break;
						case 'assignment':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1001';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#asmmenubig' + urlArr[2].substr(0, 1)).css('display') != 'block') getContent.assignment(urlArr[2]);
							else getContent.assignmentId(urlArr[2]);
							break;
						case 'monster':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							if($('#mstlist').length == 0) getContent.monster(urlArr[2]);
							else getContent.monsterId(urlArr[2]);
							break;
						default:
							getContent.notfound();
					}
					break;
				case 'aboutus':
					getContent.aboutus();
					break;
				case 'search':
					if(urlArr[1] == undefined || urlArr[1] == '') urlArr[1] = '';
					getContent.search(urlArr[1]);
					break;
				case 'map':
					if(urlArr[1] == undefined || urlArr[1] == '') getContent.map(1);
					else getContent.map(urlArr[1]);
					break;
				case 'media':
					if($('#mediamain').length == 0) getContent.media();
					if(urlArr[1] == undefined || urlArr[1] == '') urlArr[1] = 'music';
					switch(urlArr[1]) {
						case 'music':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1';
							else if(urlArr[2] == 'playing') urlArr[2] = '3';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							var type = urlArr[2].length > 1 ? urlArr[2].substr(1, 1) : urlArr[2];
							if($('#musicsubmenu' + type).css('display') != 'block') getContent.music(urlArr[2]);
							break;
						case 'pic':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							var type = urlArr[2].length > 1 ? urlArr[2].substr(1, 1) : urlArr[2];
							if($('#picsubmenu' + type).css('display') != 'block') getContent.pic(urlArr[2]);
							else if(urlArr[2].length > 1) getContent.picId(urlArr[2]);
							break;
						case 'video':
							if(urlArr[2] == undefined || urlArr[2] == '') urlArr[2] = '1';
							if(!$.isNumeric(urlArr[2])) { getContent.notfound(); return false; }
							var type = urlArr[2].length > 1 ? urlArr[2].substr(1, 1) : urlArr[2];
							if($('#videosubmenu' + type).css('display') != 'block') getContent.video(urlArr[2]);
							else if(urlArr[2].length > 1) getContent.videoId(urlArr[2]);
							break;
						default:
							getContent.notfound();
					}
					break;
				case 'down':
					if(urlArr[1] == undefined || urlArr[1] == '') getContent.down(0);
					else getContent.down(urlArr[1]);
					break;
				case 'index':
					getContent.main();
					break;
				default:
					getContent.notfound();
			}
		}
	});
    $.fn.extend({
		scroll: function(ldiv) {
			var sdiv = $(this).find('.scrollto');
			if(ldiv.height() > ldiv.parent().height()) {
				sdiv.css('top', 0).show();
				sdiv.draggable({
					start: function(){ $(this).click(); },
					drag: function(){
						ldiv.css('top', Math.round(sdiv.position().top * (ldiv.parent().height() - ldiv.height()) / (sdiv.parent().height() - 19) / 33) * 33);
					},
					containment: 'parent',
					axis: 'y'
				});
				$(this).find('.scrollbar').on('mousedown', function(e) {
					var top = sdiv.offset().top;
					e.preventDefault();
					if(e.pageY > top + 19) $.scroll(sdiv, ldiv, -165, 1);
					else if(e.pageY < top) $.scroll(sdiv, ldiv, 165, 1);
				});
				$(this).find('.totop').on('mousedown', function(e) { e.preventDefault(); $.scroll(sdiv, ldiv, 33, 1); });
				$(this).find('.tobottom').on('mousedown', function(e) { e.preventDefault(); $.scroll(sdiv, ldiv, -33, 1); });
				ldiv.on("mousewheel", function(e, delta) {
					e.preventDefault();
					delta > 0 ? $.scroll(sdiv, ldiv, 165, 0) : $.scroll(sdiv, ldiv, -165, 0);
				});
			} else {
				sdiv.hide();
			}
		}
	});
	//鼠标滚轮
	/*! jQuery MouseWheel plugin
	 * Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
	 * Licensed under the MIT License (LICENSE.txt).
	 * Version: 3.0.6
	 */
	var types = ['DOMMouseScroll', 'mousewheel'];
	if ($.event.fixHooks) {
		for (var i = types.length; i; ) {
			$.event.fixHooks[ types[--i] ] = $.event.mouseHooks;
		}
	}
	$.event.special.mousewheel = {
		setup: function() {
			if (this.addEventListener)
				for (var i = types.length; i; )
					this.addEventListener(types[--i], handler, false);
			else
				this.onmousewheel = handler;
		},
		teardown: function() {
			if (this.removeEventListener)
				for (var i = types.length; i; )
					this.removeEventListener(types[--i], handler, false);
			else
				this.onmousewheel = null;
		}
	};
	function handler(event) {
		var orgEvent = event || window.event, args = [].slice.call( arguments, 1 ), delta = 0, deltaX = 0, deltaY = 0;
		event = $.event.fix(orgEvent);
		event.type = "mousewheel";
		// Old school scrollwheel delta
		if (orgEvent.wheelDelta) { delta = orgEvent.wheelDelta / 120; }
		if (orgEvent.detail) { delta = -orgEvent.detail / 3; }
		// New school multidimensional scroll (touchpads) deltas
		deltaY = delta;
		// Gecko
		if (orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
			deltaY = 0;
			deltaX = -1 * delta;
		}
		// Webkit
		if (orgEvent.wheelDeltaY !== undefined) { deltaY = orgEvent.wheelDeltaY / 120; }
		if (orgEvent.wheelDeltaX !== undefined) { deltaX = -1 * orgEvent.wheelDeltaX / 120; }
		// Add event and delta to the front of the arguments
		args.unshift(event, delta, deltaX, deltaY);
		return ($.event.dispatch || $.event.handle).apply(this, args);
	}
	//历史记录
	/*! jQuery history plugin
	 * The MIT License
	 * Copyright (c) 2006-2009 Taku Sano (Mikage Sawatari)
	 * Copyright (c) 2010 Takayuki Miwa
	 */
    var locationWrapper = {
        put: function(hash, win) { (win || window).location.hash = this.encoder(hash); },
        get: function(win) {
            var hash = ((win || window).location.hash).replace(/^#/, '');
            try {
                return $.browser.mozilla ? hash : decodeURIComponent(hash);
            } catch(error) {
                return hash;
            }
        },
        encoder: encodeURIComponent
    };
    var iframeWrapper = {
        id: "__jQuery_history",
        init: function() {
            var html = '<iframe id="'+ this.id +'" style="display:none" src="javascript:false;" />';
            $("body").prepend(html);
            return this;
        },
        _document: function() { return $("#"+ this.id)[0].contentWindow.document; },
        put: function(hash) {
            var doc = this._document();
            doc.open();
            doc.close();
            locationWrapper.put(hash, doc);
        },
        get: function() { return locationWrapper.get(this._document()); }
    };
    function initObjects(options) {
        options = $.extend({ unescape: false }, options || {});
        locationWrapper.encoder = encoder(options.unescape);
        function encoder(unescape_) {
            if(unescape_ === true) { return function(hash) { return hash; }; }
            if(typeof unescape_ == "string" && (unescape_ = partialDecoder(unescape_.split(""))) || typeof unescape_ == "function") {
                return function(hash) { return unescape_(encodeURIComponent(hash)); };
            }
            return encodeURIComponent;
        }
        function partialDecoder(chars) {
            var re = new RegExp($.map(chars, encodeURIComponent).join("|"), "ig");
            return function(enc) { return enc.replace(re, decodeURIComponent); };
        }
    }
    var implementations = {};
    implementations.base = {
        callback: undefined,
        type: undefined,
        check: function() {},
        load:  function(hash) {},
        init:  function(callback, options) {
            initObjects(options);
            self.callback = callback;
            self._options = options;
            self._init();
        },
        _init: function() {},
        _options: {}
    };
    implementations.timer = {
        _appState: undefined,
        _init: function() {
            var current_hash = locationWrapper.get();
            self._appState = current_hash;
            self.callback(current_hash);
            setInterval(self.check, 100);
        },
        check: function() {
            var current_hash = locationWrapper.get();
            if(current_hash != self._appState) {
                self._appState = current_hash;
                self.callback(current_hash);
            }
        },
        load: function(hash) {
            if(hash != self._appState) {
                locationWrapper.put(hash);
                self._appState = hash;
                self.callback(hash);
            }
        }
    };
    implementations.iframeTimer = {
        _appState: undefined,
        _init: function() {
            var current_hash = locationWrapper.get();
            self._appState = current_hash;
            iframeWrapper.init().put(current_hash);
            self.callback(current_hash);
            setInterval(self.check, 100);
        },
        check: function() {
            var iframe_hash = iframeWrapper.get(),
                location_hash = locationWrapper.get();
            if(location_hash != iframe_hash) {
                if(location_hash == self._appState) {    // user used Back or Forward button
                    self._appState = iframe_hash;
                    locationWrapper.put(iframe_hash);
                    self.callback(iframe_hash); 
                } else {                              // user loaded new bookmark
                    self._appState = location_hash;  
                    iframeWrapper.put(location_hash);
                    self.callback(location_hash);
                }
            }
        },
        load: function(hash) {
            if(hash != self._appState) {
                locationWrapper.put(hash);
                iframeWrapper.put(hash);
                self._appState = hash;
                self.callback(hash);
            }
        }
    };
    implementations.hashchangeEvent = {
        _init: function() {
            self.callback(locationWrapper.get());
            $(window).bind('hashchange', self.check);
        },
        check: function() { self.callback(locationWrapper.get()); },
        load: function(hash) { locationWrapper.put(hash); }
    };
    var self = $.extend({}, implementations.base);
    if($.browser.msie && ($.browser.version < 8 || document.documentMode < 8)) self.type = 'iframeTimer';
    else if("onhashchange" in window) self.type = 'hashchangeEvent';
    else self.type = 'timer';
    $.extend(self, implementations[self.type]);
    $.history = self;
})(jQuery);