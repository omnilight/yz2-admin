<?php

namespace yz\admin\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;


/**
 * Class Role
 * @property DbManager $authManager
 * @property Item $authItem
 * @property array $childRoles
 * @property array $childOperationsAndTasks
 * @package \yz\admin\models
 */
class Role extends AuthItem
{
    /**
     * @var DbManager
     */
    protected $_authManager;
    /**
     * @var array
     */
    protected $_childRoles;
    /**
     * @var array
     */
    protected $_childPermissions;

    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'User Role');
    }

    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'User Roles');
    }

    public static function find()
    {
        return parent::find()->where(['type' => Item::TYPE_ROLE]);
    }


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'unique'],
            [['name'], 'match', 'pattern' => '/[a-zA-Z0-9_\-]+/',
                'message' => \Yii::t('admin/t', 'Name of the role must contain only characters from the list: {chars}', [
                        'chars' => 'a-z, A-Z, 0-9, -'
                    ])],
            [['childRoles', 'childPermissions'], 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'childRoles' => \Yii::t('admin/t', 'Child roles'),
            'childPermissions' => \Yii::t('admin/t', 'Child permissions'),
        ]);
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
     * @param DbManager $authManager
     */
    public function setAuthManager($authManager)
    {
        $this->_authManager = $authManager;
    }

    /**
     * @return \yii\rbac\Role
     */
    public function getAuthItem()
    {
        return $this->getAuthManager()->getRole($this->name);
    }

    /**
     * @param Item[] $items
     * @param integer|array $type
     * @return Item[]
     */
    protected static function filterItems($items, $type)
    {
        $type = (array)$type;
        return array_filter($items, function ($item) use ($type) {
            /** @var Item $item */
            return in_array($item->type, $type);
        });
    }

    /**
     * @param array $childRoles
     */
    public function setChildRoles($childRoles)
    {
        $this->_childRoles = $childRoles;
    }

    /**
     * @return array
     */
    public function getChildRoles()
    {
        if ($this->_childRoles === null && !$this->isNewRecord) {
            $this->_childRoles =
                ArrayHelper::getColumn(self::filterItems($this->getAuthManager()->getChildren($this->name), [Item::TYPE_ROLE]), 'name');
        } elseif ($this->_childRoles == null) {
            $this->_childRoles = [];
        }
        return $this->_childRoles;
    }

    /**
     * @return array
     */
    public function getChildRolesValues()
    {
        /** @var ActiveQuery $query */
        $query = AuthItem::find()->asArray()
            ->where(['type' => [Item::TYPE_ROLE]]);
        if (!$this->isNewRecord)
            $query->andWhere('name != :name', [':name' => $this->name]);
        return ArrayHelper::map($query->all(), 'name', 'description');
    }

    /**
     * @param array $childOperationsAndTasks
     */
    public function setChildPermissions($childOperationsAndTasks)
    {
        $this->_childPermissions = $childOperationsAndTasks;
    }

    /**
     * @return array
     */
    public function getChildPermissions()
    {
        if ($this->_childPermissions == null && !$this->isNewRecord) {
            $this->_childPermissions =
                ArrayHelper::getColumn(self::filterItems($this->getAuthManager()->getChildren($this->name), [Item::TYPE_PERMISSION]), 'name');
        } elseif ($this->_childPermissions == null) {
            $this->_childPermissions = [];
        }
        return $this->_childPermissions;
    }

    /**
     * @return array
     */
    public function getChildPermissionsValues()
    {
        /** @var ActiveQuery $query */
        $query = AuthItem::find()->asArray()
            ->where(['type' => [Item::TYPE_PERMISSION]]);
        if (!$this->isNewRecord)
            $query->andWhere('name != :name', [':name' => $this->name]);
        return ArrayHelper::map($query->all(), 'name', 'description');
    }

    public function beforeValidate()
    {
        $this->type = Item::TYPE_ROLE;

        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->_childPermissions !== null) {
            foreach ($this->_childPermissions as $name)
                $this->getAuthManager()->addChild($this->getAuthItem(), $this->getAuthManager()->getPermission($name));
        }

        if ($this->_childRoles !== null) {
            foreach ($this->_childRoles as $name)
                $this->getAuthManager()->addChild($this->getAuthItem(), $this->getAuthManager()->getRole($name));
        }
    }


}