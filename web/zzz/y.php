<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/8/28
 * Time: 17:04
 */
include_once "./spider/db/Db.php";
include_once "./spider/model/Car.php";
include_once "./spider/model/Yichewang.php";
include_once "./spider/model/Bbs.php";
include_once "./spider/model/Category.php";
include_once "./spider/tool/Match.php";
include_once "./spider/tool/Http.php";

use spider\model\Car;
use spider\model\Yichewang;
use spider\tool\Match;

set_time_limit(0);
$car = new Yichewang();
$id = @intval($_GET['id']);
//$car->matchAll($id);


$s = '广汽 传祺GA6 ga6gainianche 
红旗 红旗H5 hongqih5 
长安 长安欧诺 ounuo 
长安 欧诺S ounuo 
荣威 荣威Ei5 rongweiei5 koubei 
荣威 荣威ei6 ei6 
荣威 荣威RX5新能源 rongweierx5-5122 
荣威 荣威RX8 rongweirx8 
东风 东风风神AX4 dongfengfengshenax4-5116 
吉利 吉利远景S1 jilis1 
领克 领克01 lynkco01gainianche 
领克 领克02 lingke02 koubei 
哈弗 哈弗H4 hafuh4 
比亚迪 宋新能源 songdm 
奇瑞 奇瑞瑞虎8 ruihu8 
东风 风光330 fengguang330
东风 东风风光580 fengguang580 
东风 东风风光S560 s560 
东风 东风小康K07S xiaokangk07s koubei 
众泰 众泰E200 e100 
君马 君马MEET3 meet3 ';

$list = explode("\n",$s);
$car = new Car();
foreach($list as $li){
    unset($car->data['id']);
    $data = explode(' ',$li);
    $car->data['bbs'] = '易车网';
    $car->data['brand'] = $data[0];
    $car->data['model'] = $data[1];
    $car->data['core'] = $data[2];
    $car->data['is_fetch'] = 1;
    $car->insert();
}
