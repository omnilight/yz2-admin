<?php

namespace yz\admin\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yz\admin\models\Role;

/**
 * RoleSearch represents the model behind the search form about `yz\admin\models\Role`.
 */
class RoleSearch extends Model
{
    public $name;
    public $type;
    public $description;
    public $biz_rule;
    public $data;

    public function rules()
    {
        return [
            [['name', 'description', 'biz_rule', 'data'], 'safe'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge((new Role)->attributeLabels(), [
            // Custom parameter names
        ]);
    }

    public function search($params)
    {
        $query = Role::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'name', true);
        $this->addCondition($query, 'type');
        $this->addCondition($query, 'description', true);
        $this->addCondition($query, 'biz_rule', true);
        $this->addCondition($query, 'data', true);
        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}
