<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/8/28
 * Time: 17:04
 */
include_once "./spider/db/Db.php";
include_once "./spider/model/Car.php";
include_once "./spider/model/Chezhiwang.php";
include_once "./spider/model/Bbs.php";
include_once "./spider/model/Category.php";
include_once "./spider/tool/Match.php";
include_once "./spider/tool/Http.php";

use spider\model\Category;
use spider\model\Chezhiwang;
use spider\model\Yichewang;
use spider\tool\Match;

set_time_limit(0);
$car = new Chezhiwang();
$id = @intval($_GET['id']);
$car->matchAll($id);
