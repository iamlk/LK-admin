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

class Pcmatch extends Db{
    public $table = 'pcmatch';
    public $attr = [
        'id',
        'cate_id',
        'bbs_id',
        'head_keyword',
        'keyword',
        'keyword_end'
    ];
    public $data = [];

    public function match(){

    }
}