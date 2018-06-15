<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stream_type".
 *
 * @property integer $id
 * @property string $type
 * @property string $value
 */
class StreamType extends \app\components\AppActiveRecord
{

    const PROPERTY  = 'property';
    const WELL      = 'well';
    const TEAM      = 'team';
    const CLS       = 'class';

    const IN        = '进料';
    const OUT       = '出料';

    public static $TypeList = [
        self::IN => self::IN,
        self::OUT => self::OUT,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stream_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string', 'max' => 12],
            [['value'], 'string', 'max' => 24],
            [['type', 'value'], 'unique', 'targetAttribute' => ['type', 'value'], 'message' => 'The combination of Type and Value has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'value' => 'Value',
        ];
    }

    public static function GetList($type)
    {
        $list = self::findAll(['type'=>$type]);
        $data = [];
        foreach($list as $li){
            $data[$li->value] = $li->value;
        }
        return $data;
    }
}
