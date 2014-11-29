<?php

namespace yz\admin\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "yz_admin_users_settings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $group
 * @property string $name
 * @property string $value_raw
 *
 * @property mixed $value
 *
 * @property User $user
 */
class UserSetting extends \yz\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_users_settings}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['value_raw'], 'string'],
            [['group', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin/t', 'ID'),
            'user_id' => Yii::t('admin/t', 'User ID'),
            'group' => Yii::t('admin/t', 'Group'),
            'name' => Yii::t('admin/t', 'Name'),
            'value_raw' => Yii::t('admin/t', 'Value Raw'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('settings');
    }

    protected $_value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        if ($this->_value === null) {
            $this->_value = Json::decode($this->value_raw);
        }
        return $this->_value;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
        $this->value_raw = Json::encode($this->_value);
    }

    public static function set($userId, $group, $name, $value)
    {
        $setting = self::findOne(['user_id' => $userId, 'group' => $group, 'name' => $name]);
        if ($setting === null) {
            $setting = new UserSetting();
            $setting->user_id = $userId;
            $setting->group = $group;
            $setting->name = $name;
        }
        $setting->value = $value;
        $setting->save();
    }

    /**
     * @param int $userId
     * @param string $group
     * @param string $name
     * @return UserSetting
     */
    public static function get($userId, $group, $name)
    {
        $setting = self::findOne(['user_id' => $userId, 'group' => $group, 'name' => $name]);
        if ($setting === null)
            return null;
        else
            return $setting->value;
    }

    /**
     * @param int $userId
     * @param string $group
     * @param string $name
     * @return int
     */
    public static function remove($userId, $group, $name)
    {
        return self::deleteAll(['user_id' => $userId, 'group' => $group, 'name' => $name]);
    }
}
