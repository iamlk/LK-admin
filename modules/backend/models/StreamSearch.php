<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/6/14
 * Time: 21:28
 */

namespace app\modules\backend\models;


use yii\base\Model;
use app\models\Stream;
use yii\data\ActiveDataProvider;

class StreamSearch extends Stream
{
    /**
     * 自动更新详情
     * @var bool
     */
    public static $autoUpdateDetail = false;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['type', 'start_time','end_time', 'well_no'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getProvider()
    {
        $query = Stream::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id'=>SORT_ASC]],
            'pagination' => ['pageSize'=>1]
        ]);
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param int $pageSize
     *
     * @return ActiveDataProvider
     */
    public function search($params, $pageSize=20)
    {
        $query = Stream::find();
        if($pageSize==0) $query->where('1=0');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['start_time'=>SORT_ASC]],
            'pagination' => ['pageSize'=>$pageSize]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            //'property_no' => $this->property_no,
            'well_no' => $this->well_no,
            //'team_no' => $this->team_no,
            //'well_class' => $this->well_class,
        ]);

        if($this->start_time)
            $query->andFilterWhere(['>=','start_time',$this->start_time]);
        if($this->end_time){
            $this->end_time = date('Y-m-d',(strtotime($this->end_time)+86400));
            $query->andFilterWhere(['<=','start_time',$this->end_time]);
        }
        return $dataProvider;
    }

}