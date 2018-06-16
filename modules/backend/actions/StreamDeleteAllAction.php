<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2017/1/5
 * Time: 13:49
 * Email:wap@iamlk.cn
 */

namespace app\modules\backend\actions;

use yii\base\Action;
use app\models\Stream;
use app\models\ContentQuery;
use Yii;
use yii\base\Exception;
use yii\web\Response;
/**
 * Class ContentDeleteAllAction
 * @property \app\modules\backend\components\BackendController $controller
 * @package app\modules\backend\actions
 */
class StreamDeleteAllAction extends Action
{

    public function init()
    {
        parent::init();
    }

    /**
     * @return array
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ids =  Yii::$app->request->post('ids');
        if(empty($ids)){
            return ['data'=>'id不能为空','code'=>1];
        }

        /** @var $query ContentQuery */
        $query = Stream::find();

        $query->andFilterWhere([
            'in', 'id', $ids
        ]);
        try {
            //Stream::deleteAll($query->where);
            return [
                'code'=>0,
                'data'=>'操作成功'
            ];
        }catch(Exception $e){
            return [
                'code'=>1,
                'data'=>$e->getMessage()
            ];
        }
    }
}