<?php
namespace app\modules\backend\widgets;

use yii\widgets\Pjax;
use yii\bootstrap\Html;
use yii\grid\GridView as YiiGridView;
use yii\helpers\Url;
use Yii;
use app\modules\backend\grid\DataColumn;
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2017/1/5
 * Time: 10:56
 * Email:wap@iamlk.cn
 */

class GridView extends YiiGridView
{
    public $dataColumnClass = DataColumn::Class;
    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{errors}`: the filter model error summary. See [[renderErrors()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = "<div style='height: 30px'><div class='pull-left'>{operation}</div><div class='pull-right'>{summary}</div></div>\n{items}\n<div style='height: 30px'><div class='pull-left'>{operation}</div><div class='pull-right'>{summary}</div></div>{pager}\n";
    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{operation}':
                return $this->renderOperation();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderOperation()
    {
        $id = $this->options['id'];
        $buttonList = [
            Html::tag('button', '导出',[
                'title'=>'当前筛选所有结果将导出',
                'class'=>'content-operation btn btn-xs btn-success',
                'onclick'=>'if(confirm(\'您确定导出当前筛选结果？\'))window.location.href=\'/backend/stream/export\';',
            ]),
            Html::tag('button', '打印',[
                'class'=>'content-operation btn btn-xs btn-warning',
                'onclick'=>'$(\'#w0\').jqprint({printContainer:true});',
            ]),
            Html::tag('button', '删除',[
                'id'=>'delete',
                'class'=>'content-operation btn btn-xs btn-danger',
                'data-queren' => '您确定删除所选项？删除将不能恢复~',
                'data-action'=>Url::to(['delete-all'])
            ]),
        ];
        $view = $this->getView();
        $view->registerJs('$(\'#delete\').click(function(){
            var self = this;
            this.disabled =true;
            var url = $(this).data(\'action\');
            var queren = $(this).data(\'queren\');
            var ids = $(\'#'.$id.'\').yiiGridView(\'getSelectedRows\');
            if(!url){
                alert(\'action不能为空\');
                self.disabled = false;
                return;
            }
            if(ids==""){
                alert(\'请选择要处理的记录\');
                self.disabled = false;
                return;
            }
            if(confirm(queren)){
                $.ajax({
                    "url":url,
                    "type":"post",
                    "data":{"ids":ids},
                    "dataType":"json"
                }).done(function(res){
                    alert(res.data);
                    $(\'#'.$id.'\').yiiGridView(\'applyFilter\');
                    self.disabled = false;
                });
            }
            self.disabled = false;
        });');
        return Html::tag('div', implode('', $buttonList), [
            'class'=>'btn-group'
        ]);
    }
    public function run()
    {
        ob_start();
        ob_implicit_flush(false);
        Pjax::begin();
        parent::run();
        Pjax::end();
        return ob_get_clean();
    }
}