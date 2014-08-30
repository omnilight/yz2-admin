<?php

namespace yz\admin\models;

use Yii;
use yii\base\ModelEvent;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\helpers\Json;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "ms_smart_admin_system_events".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $message
 * @property string $url_raw
 * @property string $created_at
 * @property integer $is_viewed
 * @property string $last_viewed_at
 * 
 * @property array|string $url
 *
 * @property User $user
 */
class SystemEvent extends \yz\db\ActiveRecord implements ModelInfoInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_system_events}}';
    }

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return Yii::t('admin/t', 'System Event');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return Yii::t('admin/t', 'System Events');
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [self::EVENT_BEFORE_INSERT => ['created_at'],],
                'value' => new Expression('NOW()'),
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'is_viewed'], 'integer'],
            [['message', 'url_raw',], 'string'],
            [['created_at', 'last_viewed_at'], 'safe'],
            [['type'], 'string', 'max' => 16]
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
            'type' => Yii::t('admin/t', 'Type'),
            'message' => Yii::t('admin/t', 'Message'),
            'url_raw' => Yii::t('admin/t', 'Url Raw'),
            'created_at' => Yii::t('admin/t', 'Created At'),
            'is_viewed' => Yii::t('admin/t', 'Is Viewed'),
            'last_viewed_at' => Yii::t('admin/t', 'Last Viewed At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return array|string
     */
    public function getUrl()
    {
        return Json::decode($this->url_raw);
    }

    /**
     * @param array|string $url
     */
    public function setUrl($url)
    {
        $this->url_raw = Json::encode($url);
    }

    public function beforeSave($insert)
    {
        if ($this->is_viewed == 1 && $this->isAttributeChanged('is_viewed')) {
            $this->last_viewed_at = new Expression('NOW()');
        }
        return parent::beforeSave($insert);
    }


    /**
     * Creates system event
     * @param string $type
     * @param int $user_id
     * @param string $message
     * @param array|string $url
     * @return SystemEvent
     */
    public static function create($type, $user_id, $message, $url = null)
    {
        $model = new self;
        $model->type = $type;
        $model->user_id = $user_id;
        $model->message = $message;
        $model->url = $url;

        $model->save();
        return $model;
    }

}
