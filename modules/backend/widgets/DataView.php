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
    public $layout = "{items}\n<div style='height: 30px'><div class='pull-left'>{operation}</div><div class='pull-right'></div></div>\n";

    public function renderOperation()
    {
        $id = $this->options['id'];
        $buttonList = [
            Html::tag('button', '按周',[
                'class'=>'content-operation btn btn-xs btn-success _data',
                'id'=>'week',
                'data-action'=>Url::to(['data?type=w'])
            ]),
            Html::tag('button', '按月',[
                'class'=>'content-operation btn btn-xs btn-warning _data',
                'id'=>'month',
                'data-action'=>Url::to(['data?type=m'])
            ]),/**
            Html::tag('button', '按季度',[
                'id'=>'season',
                'class'=>'content-operation btn btn-xs btn-danger _data',
                'data-action'=>Url::to(['data?type=s'])
            ]),*/
        ];
        $view = $this->getView();
        $view->registerJs('
$(\'._data\').click(function(){
    var self = this;
    var url = $(this).data(\'action\');
    window.location.href=url;
});');
        return Html::tag('div', implode('', $buttonList), [
            'class'=>'btn-group'
        ]);
    }
}
