<?php

namespace yz\admin\models;

use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Security;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\web\IdentityInterface;
use yz\db\ActiveRecord;
use yz\interfaces\ModelInfoInterface;

/**
 * Class BaseUser implements admin panel user
 * @property integer $id
 * @property string $login
 * @property string $passhash
 * @property string $auth_key
 * @property boolean $is_super_admin
 * @property boolean $is_active
 * @property string $name
 * @property string $email
 * @property string $logged_at
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property DbManager $authManager
 * @property array $rolesItems 
 * @property array $rolesItemsValues 
 * 
 * @package yz\admin\models
 */
class User extends \yz\db\ActiveRecord implements IdentityInterface, ModelInfoInterface
{
    const AUTH_KEY_LENGTH = 32;

    /**
     * @var DbManager
     */
    protected $_authManager;
    /**
     * @var array
     */
    protected $_rolesItems;

    public static function tableName()
    {
        return '{{%admin_users}}';
    }

    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'Administrator');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'Administrators');
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function($event) { return new Expression('NOW()'); }
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login','email','name'], 'required'],
            [['is_super_admin', 'is_active'], 'boolean'],
            [['logged_at', 'created_at', 'updated_at'], 'safe'],
            [['login'], 'string', 'max' => 32],
            [['login'], 'unique'],
            [['passhash', 'auth_key', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['name'], 'string', 'max' => 64],
            [['rolesItems'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('admin/t', 'ID'),
            'login' => \Yii::t('admin/t', 'Login'),
            'passhash' => \Yii::t('admin/t', 'Passhash'),
            'auth_key' => \Yii::t('admin/t', 'Auth Key'),
            'is_super_admin' => \Yii::t('admin/t', 'Is Super Admin'),
            'is_active' => \Yii::t('admin/t', 'Is Active'),
            'name' => \Yii::t('admin/t', 'Name'),
            'email' => \Yii::t('admin/t', 'Email'),
            'logged_at' => \Yii::t('admin/t', 'Login Time'),
            'created_at' => \Yii::t('admin/t', 'Create Time'),
            'updated_at' => \Yii::t('admin/t', 'Update Time'),
            'adminAuthAssignment' => \Yii::t('admin/t', 'Admin Auth Assignment'),
            'itemNames' => \Yii::t('admin/t', 'Item Names'),
            'rolesItems' => \Yii::t('admin/t','Assigned Roles'),
        ];
    }

    /**
     * @return DbManager
     */
    public function getAuthManager()
    {
        if ($this->_authManager == null) {
            $this->_authManager = \Yii::$app->authManager;
        }
        return $this->_authManager;
    }

    /**
     * @param array $rolesItems
     */
    public function setRolesItems($rolesItems)
    {
        $this->_rolesItems = $rolesItems;
    }

    /**
     * @return array
     */
    public function getRolesItems()
    {
        if ($this->_rolesItems == null && !$this->isNewRecord) {
            $this->_rolesItems = array_keys($this->getAuthManager()->getAssignments($this->id));
        } elseif ($this->_rolesItems == null) {
            $this->_rolesItems = [];
        }
        return $this->_rolesItems;
    }

    /**
     * @return array
     */
    public function getRolesItemsValues()
    {
        /** @var ActiveQuery $query */
        $query = AuthItem::find()->asArray()
            ->where(['type' => [Item::TYPE_ROLE]]);
        return ArrayHelper::map($query->all(), 'name','description');
    }


    /**
     * @return bool
     */
    public function getIsSuperAdmin()
    {
        return $this->is_super_admin;
    }

    /**
     * @param bool $isSuperAdmin
     * @property bool
     */
    public function setIsSuperAdmin($isSuperAdmin)
    {
        $this->is_super_admin = $isSuperAdmin;
    }

    /**
     * @param $login
     * @return User
     */
    public static function findByLogin($login)
    {
        return static::find()
            ->where(['login' => $login])->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        if ($this->isNewRecord)
            return false;

        return Security::validatePassword($password, $this->passhash);
    }

    /**
     * @param $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return Security::generatePasswordHash($password);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Security::generateRandomKey(static::AUTH_KEY_LENGTH);
            }
            return true;
        } else
            return false;
    }

    public function afterSave($insert)
    {
        if ($this->_rolesItems !== null) {
            foreach ($this->_rolesItems as $itemName) {
                $this->getAuthManager()->assign($this->getAuthManager()->getRole($itemName), $this->id);
            }
        }

        parent::afterSave($insert);
    }


}
