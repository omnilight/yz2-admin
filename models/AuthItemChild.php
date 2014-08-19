<?php

namespace yz\admin\models;

use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_admin_auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $childRecord
 * @property AuthItem $parentRecord
 */
class AuthItemChild extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_auth_item_child}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'Auth Item Child');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'Auth Item Children');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => \Yii::t('admin/t', 'Parent'),
            'child' => \Yii::t('admin/t', 'Child'),
            'childRecord' => \Yii::t('admin/t', 'Child'),
            'parentRecord' => \Yii::t('admin/t', 'Parent'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getChildRecord()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getParentRecord()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }
}
