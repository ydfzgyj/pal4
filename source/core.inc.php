<?php

/**
 *     仙剑奇侠传四资料站 - 巴里切罗 http://www.baliqieluo.com/pal4new/
 *     Barrichello(巴里切罗) <ydfzgyj@baliqieluo.com>
 *     核心处理
 */

if(basename($_SERVER['PHP_SELF']) == 'core.inc.php') {
	exit('Access Denied');
}

define('ROOT', substr(dirname(__FILE__), 0, -6));
date_default_timezone_set('PRC');

$_config = array();

// 数据库服务器设置
$_config['db']['host'] = 'host';
$_config['db']['user'] = 'user';
$_config['db']['pw'] = 'pw';
$_config['db']['name'] = 'name';
$_config['db']['charset'] = 'utf8';
$_config['db']['pconnect'] = 0;

//连接数据库
require ROOT.'/source/db.class.php';
$db = new db(
	$_config['db']['host'],
	$_config['db']['user'],
	$_config['db']['pw'],
	$_config['db']['charset'],
	$_config['db']['name'],
	$_config['db']['pconnect']
);

include ROOT.'source/core.func.php';

//Get和Post处理
foreach(array_merge($_POST, $_GET) as $k => $v) {
	$_gp[$k] = newHtmlSpecialChars($v);
}
$cp = empty($_gp['cp']) ? 1 : max(1, intval($_gp['cp']));