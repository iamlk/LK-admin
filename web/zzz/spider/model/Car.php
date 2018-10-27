<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/1
 * Time: 17:36
 */
namespace spider\model;
use spider\db\Db;

class Car extends Db{
    protected $time = 0;
    const YICHEWANG='易车网';
    const AUTOCAR='汽车之家';
    const CHEZHIWANG='车质网';
    public $table = 'car';
    public $attr = [
        'id',
        'bbs',
        'letter',
        'brand',
        'model',
        'core',
        'url',
        'is_fetch'
    ];
    public $data = [];

    public function init(){
        parent::init();
        $this->time = strtotime('2018-01-01');
    }
}