<?php

namespace app\models;

use Yii;

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
            [['start_time', 'end_time', 'start_weight', 'end_weight'], 'required'],
            [['total_weight'], 'integer'],
            [['start_weight', 'end_weight', 'the_weight'], 'number'],
            [['start_time','end_time'], 'fomateTime'],
            [['uid', 'property_no', 'well_no', 'team_no', 'well_class'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 6],
            [['uid'], 'unique'],
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->start_time   = date('Y-m-d H:i:s', $this->start_time);
        $this->end_time     = date('Y-m-d H:i:s', $this->end_time);
    }

    public function fomateTime($attr,$params)
    {
        if ($this->hasErrors()) return false;

        $datetime = $this->{$attr};

        $time = strtotime($datetime);
        // 验证时间格式是否正确
        if ($time === false) {
            $this->addError($attr, '时间格式错误.');
            return false;
        }
        // 将转换为时间戳后的时间赋值给time属性
        $this->{$attr} = $time;
        return true;
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
        ];
    }
}
