<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
header("Access-Control-Allow-Origin:http://www.cixi518.com");/* http://www.cixi518.com */
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Token");
header("Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS");
header('X-Powered-By:3.2.1');
header('Content-Type:application/x-www-form-urlencoded;charset=utf-8');

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
