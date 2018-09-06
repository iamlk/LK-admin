<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/1
 * Time: 10:46
 */
namespace spider\model;
use spider\db\Db;

class Category extends Db{
    public $table = 'Category';
    public $attr = [
        'id',
        'name',
        'pid',
        'level',
        'keywords'
    ];
    public $data = [];

    public function readfile($file){
        $file = fopen($file, 'r');
        $data = [];
        $level1_t = '';
        $level2_t = '';
        $level2 = [];
        $level3 = [];
        while(!feof($file)){
            $line = trim(fgets($file));
            if(empty($line)) continue;
            if(substr($line,0,1)=='1'){
                if($level1_t) $data[$level1_t] = $level2;
                $level1_t = substr($line,1);
                $level2 = [];
                $level3 = [];
                continue;
            }
            if(substr($line,0,1)=='2'){
                if($level2_t) $level2[$level2_t] = $level3;
                $level2_t = substr($line,1);
                $level3 = [];
                continue;
            }
            $level3[] = $line;
        }
        if($level1_t) $data[$level1_t] = $level2;
        return $data;
    }

    public function insertDB($data){
        foreach($data as $l1 => $list){
            $this->data['name'] = $l1;
            $this->data['pid'] = '';
            $this->data['level'] = 1;
            $pid = $this->insert();
            foreach($list as $l2 => $list2){
                $this->data['name'] = $l2;
                $this->data['pid'] = $pid;
                $this->data['level'] = 2;
                $pid2 = $this->insert();
                foreach($list2 as $l3){
                    $this->data['name'] = $l3;
                    $this->data['pid'] = $pid2;
                    $this->data['level'] = 3;
                    $this->insert();
                }
            }
        }
    }
}