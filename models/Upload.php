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
            [['file'], 'file', 'extensions' => 'json,js', 'mimeTypes' => '*/json',],
        ];
    }


    public function attributeLabels(){
        return [
            'file'=>'上传过程中需要耐心等待~'
        ];
    }
}