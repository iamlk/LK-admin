<?php

namespace app\models;


/**
 * This is the model class for table "stream".
 *
 * @property integer $id
 * @property string $uid
 * @property string $type
 * @property integer $start_time
 * @property integer $end_time
 * @property string $start_weight
 * @property string $end_weight
 * @property string $the_weight
 * @property integer $total_weight
 * @property string $property_no
 * @property string $well_no
 * @property string $team_no
 * @property string $well_class
 */
class Stream extends \app\components\AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stream';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'start_weight', 'end_weight', 'team_no', 'type', 'property_no'], 'required'],
            [['start_time', 'end_time'], 'safe'],
            [['start_weight', 'end_weight', 'the_weight', 'total_weight'], 'number'],
            [['uid', 'property_no', 'well_no', 'team_no', 'well_class'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 6],
            [['uid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '索引',
            'type' => '类型',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'start_weight' => '起始量',
            'end_weight' => '结束量',
            'the_weight' => '本次量',
            'total_weight' => '累计量',
            'property_no' => '资产号',
            'well_no' => '井号',
            'team_no' => '队号',
            'well_class' => '钻井队',
            'is_deal' =>'已处理'
        ];
    }

    public static function importData($list){
        $type = [];
        $time = '2050-12-12 12:12:12';
        foreach($list as $li){
            $model = new Stream();
            $model->attributes = $li;
            $type[$li['well_no']] = StreamType::WELL;
            $model->is_deal = 0;
            if($model->save()){//数据合法，才比较time
                if($time>$li['start_time']) $time = $li['start_time'];
            }
        }
        foreach($type as $team => $t){
            $model = new StreamType();
            $model->type = $t;
            $model->value = $team;
            $model->save();
        }
        Stream::updateAll(['is_deal'=>0],'start_time>="'.$time.'"');
    }

    public static function initData($limit=100){
        $count = Stream::find()->where(['is_deal'=>0])->count();
        if($count == 0) return 0;
        $list = null;
        $teamList = StreamType::GetAll(StreamType::WELL);
        $team = [];
        foreach($teamList as $item){
            $model = Stream::find()->where(['and','is_deal=1','team_no="'.$item.'"','type="'.StreamType::IN.'"'])->orderBy(['start_time'=>SORT_DESC])->limit(1)->one();
            if($model){
                $team[StreamType::IN][$item] = $model->total_weight;
            }else{
                $team[StreamType::IN][$item] = 0;
            }
            $model = Stream::find()->where(['and','is_deal=1','team_no="'.$item.'"','type="'.StreamType::OUT.'"'])->orderBy(['start_time'=>SORT_DESC])->limit(1)->one();
            if($model){
                $team[StreamType::OUT][$item] = $model->total_weight;
            }else{
                $team[StreamType::OUT][$item] = 0;
            }
        }
        $list = Stream::find()->where(['is_deal'=>0])->limit($limit)->all();
        //foreach(Stream::find()->where(['is_deal'=>0])->limit($limit)->each(50) as $li){
        foreach($list as $li){
            $li->the_weight = ($li->type == StreamType::IN)?($li->end_weight - $li->start_weight):($li->start_weight - $li->end_weight);
            $team[$li->type][$li->well_no] += $li->the_weight;
            $li->total_weight = $team[$li->type][$li->well_no];
            $li->is_deal = 1;
            $li->save();
        }
        return $count-$limit;
    }
}
