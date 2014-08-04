<?php

namespace yz\admin\models;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Query;


/**
 * Class UsersQuery
 * @package \yz\admin\models
 */
class UsersQuery extends ActiveQuery
{
    /**
     * @param array $roles
     * @return static
     */
    public function byRoles($roles)
    {
        $this->innerJoinWith([
            'roles' => function($query) use ($roles) {
                    /** @var ActiveQuery $query */
                    return $query->where([
                        'roles.name' => $roles,
                    ]);
                }
        ]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return array|null|User
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return array|User[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


} 