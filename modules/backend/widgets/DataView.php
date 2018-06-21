<?php
namespace app\modules\backend\widgets;

use yii\bootstrap\Html;
use yii\helpers\Url;
use Yii;
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2017/1/5
 * Time: 10:56
 * Email:wap@iamlk.cn
 */

class DataView extends GridView
{
    public $layout = "{items}\n<div style='height: 30px'><div class='pull-left'>{operation}</div><div class='pull-right'>{summary}</div></div>\n";

    public function renderOperation()
    {
        $id = $this->options['id'];
        $buttonList = [
            Html::tag('button', '按周',[
                'class'=>'content-operation btn btn-xs btn-success',
                'id'=>'week',
                'data-action'=>Url::to(['data'])
            ]),
            Html::tag('button', '按月',[
                'class'=>'content-operation btn btn-xs btn-warning',
                'id'=>'month',
                'data-action'=>Url::to(['data'])
            ]),
            Html::tag('button', '按季度',[
                'id'=>'season',
                'class'=>'content-operation btn btn-xs btn-danger',
                'data-action'=>Url::to(['data'])
            ]),
        ];
        $view = $this->getView();
        $view->registerJs('
$(\'#week\').click(function(){
    var self = this;
    var url = $(this).data(\'action\');
    $.ajax({
                    "url":url,
                    "type":"post",
                    "data":{"type":"0"},
                    "dataType":"json"
                }).done(function(res){
        $(\'#'.$id.'\').yiiGridView(\'applyFilter\');
    });
});
$(\'#month\').click(function(){
    var self = this;
    var url = $(this).data(\'action\');
    $.ajax({
                    "url":url,
                    "type":"post",
                    "data":{"type":"1"},
                    "dataType":"json"
                }).done(function(res){
        $(\'#'.$id.'\').yiiGridView(\'applyFilter\');
    });
});
$(\'#season\').click(function(){
    var self = this;
    var url = $(this).data(\'action\');
    $.ajax({
                    "url":url,
                    "type":"post",
                    "data":{"type":"2"},
                    "dataType":"json"
                }).done(function(res){
        $(\'#'.$id.'\').yiiGridView(\'applyFilter\');
    });
});');
        return Html::tag('div', implode('', $buttonList), [
            'class'=>'btn-group'
        ]);
    }
}
