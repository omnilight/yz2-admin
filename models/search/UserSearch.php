<?php

namespace yz\admin\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yz\admin\models\User;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    public $id;
    public $login;
    public $passhash;
    public $auth_key;
    public $is_super_admin;
    public $is_active;
    public $name;
    public $email;
    public $login_time;
    public $create_time;
    public $update_time;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['login', 'passhash', 'auth_key', 'name', 'email', 'login_time', 'create_time', 'update_time'], 'safe'],
            [['is_super_admin', 'is_active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge((new User)->attributeLabels(), [
            // Custom parameter names
        ]);
    }

    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'id');
        $this->addCondition($query, 'login', true);
        $this->addCondition($query, 'passhash', true);
        $this->addCondition($query, 'auth_key', true);
        $this->addCondition($query, 'is_super_admin');
        $this->addCondition($query, 'is_active');
        $this->addCondition($query, 'name', true);
        $this->addCondition($query, 'email', true);
        $this->addCondition($query, 'login_time');
        $this->addCondition($query, 'create_time');
        $this->addCondition($query, 'update_time');
        return $dataProvider;
    }

    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        $value = $this->$attribute;
        if (trim($value) === '') {
            return;
        }
        if ($partialMatch) {
            $value = '%' . strtr($value, ['%' => '\%', '_' => '\_', '\\' => '\\\\']) . '%';
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}
