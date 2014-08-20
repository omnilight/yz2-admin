<?php

namespace yz\admin\models;

use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_admin_auth_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property string $biz_rule
 * @property integer $created_at
 *
 * @property User $user
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_auth_assignment}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'Auth Assignment');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'Auth Assignments');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => \Yii::t('admin/t', 'Item Name'),
            'user_id' => \Yii::t('admin/t', 'User ID'),
            'created_at' => \Yii::t('admin/t', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
}
