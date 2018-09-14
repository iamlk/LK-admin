<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/13
 * Time: 下午7:37
 */

namespace spider\model;
use spider\db\Db;

class Koubei extends Db{
    public $table = 'koubei';
    public $attr = [
        'id',
        'car_id',
        'author',
        'note',
        'star',
        'merit',
        'defect',
        'summary',
        'uid',
        'created'
    ];
    public $data = [];
}

