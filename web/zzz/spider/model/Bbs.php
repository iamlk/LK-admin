<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/1
 * Time: 17:36
 */
namespace spider\model;
use spider\db\Db;
use spider\tool\Http;
use \DOMXPath;
use \DOMDocument;

class Bbs extends Db{
    public $table = 'bbs';
    public $attr = [
        'id',
        'car_id',
        'url',
        'title',
        'content',
        'core',
        'created'
    ];
    public $data = [];
}