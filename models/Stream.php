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
            'id' => '序号',
            'uid' => '索引',
            'type' => '进出类型',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'start_weight' => '起始重量',
            'end_weight' => '结束重量',
            'the_weight' => '本次进出料',
            'total_weight' => '累计进出料',
            'property_no' => '资产号',
            'well_no' => '井号',
            'team_no' => '队号',
            'well_class' => '钻井单位',
            'is_deal' =>'已处理'
        ];
    }

    public static function getTotalMessage($condition){
        $out = 0;
        $in = 0;
        $time_in = 0;
        $time_out = 0;
        foreach(Stream::find()->where($condition)->orderBy(['id'=>SORT_ASC])->each(50) as $li){
            if($li->type == '进料'){
                $in += $li->the_weight;
                $time_in++;
            }else{
                $out += $li->the_weight;
                $time_out++;
            }
        }
        $message = '<span style="font-size:18px;">';
        if($time_out>0){
            $message .= '合计出料次数：'.$time_out.'次&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;合计出料：'.$out.'Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if($time_in>0){
            $message .= '合计进料次数：'.$time_in.'次&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;合计进料：'.$in.'Kg';
        }
        $message .= '</span>';
        return $message;
    }

    public static function bySeason($condition){
        $json = [];
        $data=[];$y='';$a=0;$b=0;
        $month = 0;
        $season = [0,  1,1,1,  4,4,4,  7,7,7,  10,10,10];
        $current_date = '';
        foreach(Stream::find()->where($condition)->orderBy(['start_time'=>SORT_ASC])->each(50) as $li){
            $time = strtotime($li->start_time);
            $m = date('n',$time);
            if($month != $season[$m]){
                $y .= $current_date;
                echo $month.'-'.$m.' ';
                Stream::_resetData($json,$data, $y, $a, $b);
                $y = date('Y/m',$time).'-';
            }
            if($li->type==StreamType::IN){
                $a += $li->the_weight;
            }else{
                $b += $li->the_weight;
            }
            $current_date = date('Y/m',$time);
            $month = $season[$m];
        }
        if($a || $b){
            $y .= $current_date;
            Stream::_resetData($json,$data, $y, $a, $b);
        }
        $json = array_slice($json,1);
        return json_encode($json,true);
    }

    public static function byMonth($condition){
        $json = [];
        $data=[];$y='';$a=0;$b=0;
        $month = 0;
        foreach(Stream::find()->where($condition)->orderBy(['start_time'=>SORT_ASC])->each(50) as $li){
            $time = strtotime($li->start_time);
            $m = date('n',$time);
            if($m != $month){
                Stream::_resetData($json,$data, $y, $a, $b);
                $y = date('Y-m',$time);
            }
            if($li->type==StreamType::IN){
                $a += $li->the_weight;
            }else{
                $b += $li->the_weight;
            }
            $month = $m;
        }
        if($a || $b){
            Stream::_resetData($json,$data, $y, $a, $b);
        }
        $json = array_slice($json,1);
        return json_encode($json,true);
    }

    public static function byWeek($condition){
        $json = [];
        $data=[];$y='';$a=0;$b=0;
        $day = 8;   //当前星期几，理论上会越来越大，如果变小了就说明是下一周了
        $current_date = '';
        $oneweek = strtotime('2000-01-01');
        foreach(Stream::find()->where($condition)->orderBy(['start_time'=>SORT_ASC])->each(50) as $li){
            $time = strtotime($li->start_time);
            $w = date('w',$time);
            if($w == 0) $w = 7;
            if($w<$day || ($time-$oneweek)>86400*7){
                $oneweek = $time;
                $y .= $current_date;
                Stream::_resetData($json,$data, $y, $a, $b);
                $y = date('m.d',$time).'-';
            }
            if($li->type==StreamType::IN){
                $a += $li->the_weight;
            }else{
                $b += $li->the_weight;
            }
            $current_date = date('m.d',$time);
            $day = $w;
        }
        if($a || $b){
            $y .= $current_date;
            Stream::_resetData($json,$data, $y, $a, $b);
        }
        $json = array_slice($json,1);
        return json_encode($json,true);
    }

    private static function _resetData(&$json, &$data, &$y, &$a, &$b){
        $data['y'] = $y;
        $data['a'] = $a;
        $data['b'] = $b;
        $json[] = $data;
        $data = ['y'=>'','a'=>0,'b'=>0];
        $y = '';
        $a = 0;
        $b = 0;
    }

    public static function importData($list){
        $type = [];
        //$time = '2050-12-12 12:12:12';
        if(empty($list)) return false;
        foreach($list as $li){
            $model = new Stream();
            $model->attributes = $li;
            if(!isset($li['the_weight'])) return false;
            if($li['the_weight']<0) $model->the_weight = 0-$li['the_weight'];
            if(empty($li['well_no'])) continue;
            $type[$li['well_no']] = StreamType::WELL;
            $model->is_deal = 1;
            if($model->save()){//数据合法，才比较time
                //if($time>$li['start_time']) $time = $li['start_time'];
            }
        }
        foreach($type as $team => $t){
            $model = new StreamType();
            $model->type = $t;
            $model->value = $team;
            $model->save();
        }
        return true;
        //Stream::updateAll(['is_deal'=>0],'start_time>="'.$time.'"');
    }

    public static function json2Sql($inserts)
    {
        //取出第一个要保存的数据的key值来拼field
        $fields = "`".  implode("`,`", array_keys(current($inserts)))."`";

        //拼接要保存的值
        foreach($inserts as $insert)
        {
            $insert = array_map('addslashes', $insert); //使用addslashes，是避免在保存的值中出现' "这些会影响sql语句的情况。一般情况下，mysql设置为：转义后的值在保存到数据库后会自动反转义。
            $values[] = "\"".  implode("\",\"", $insert)."\"";  //拼接数据
        }
        $valueStr = implode("),(", $values);    //把数组数据拼接成字符串

        //注意要插入的数据可能已经存在
        $sql = "INSERT IGNORE INTO Stream::tableName() ($fields) VALUES ($valueStr)";    //重点是使用IGNORE,即遇到失败的插入直接跳过，如，纪录己存在

        return mysqlInsert($sql);   //自定义的一个数据插入方法
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
