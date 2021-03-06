<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/6/20
 * Time: 21:04
 */
namespace app\models;

use yii\base\Model;

class Upload extends Model
{
    public $file;

    public function rules(){
        return [
            [['file'], 'file', 'maxFiles' => 10, 'extensions' => 'bin'],
        ];
    }


    public function attributeLabels(){
        return [
            'file'=>'请选择数据文件.'
        ];
    }
}