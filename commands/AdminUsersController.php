<?php

namespace yz\admin\commands;
use console\base\Controller;
use yii\console\Exception;
use yii\helpers\Json;
use yz\admin\models\User;


/**
 * Class AdminUsersController
 */
class AdminUsersController extends Controller
{
    public $login;
    public $name;
    public $email;
    public $password;
    public $active;
    public $superadmin;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID),
            $actionID == 'create' ? ['login', 'name', 'email', 'password', 'active', 'superadmin'] : [],
            $actionID == 'update' ? ['login', 'name', 'email', 'password', 'active', 'superadmin'] : []
        );
    }


    public function actionList()
    {
        foreach (User::find()->each() as $user) {
            /** @var User $user */
            $this->stdout(strtr("[id: {id}, name: {name}, login: {login}, email: {email}, active: {enabled}, superadmin: {superadmin}]\n", [
                '{id}' => $user->id,
                '{name}' => $user->name,
                '{login}' => $user->login,
                '{email}' => $user->email,
                '{enabled}' => $user->is_active ? 'yes' : 'no',
                '{superadmin}' => $user->is_super_admin ? 'yes' : 'no',
            ]));
        }
    }

    public function actionDelete($login)
    {
        /** @var User $user */
        $user = User::findOne(['login' => $login]);
        if ($user === null) {
            throw new Exception('User with this login not found');
        }
        $user->delete();
    }

    /**
     * Creates admin user
     */
    public function actionCreate()
    {
        $user = new User();

        $user->name = $this->name;
        $user->login = $this->login;
        $user->email = $this->email;
        $user->is_active = in_array($this->active, ['yes', 'y', '1']) ? 1 : 0;
        $user->is_super_admin = in_array($this->superadmin, ['yes', 'y', '1']) ? 1 : 0;
        $user->passhash = User::hashPassword($this->password);

        if ($user->save()) {
            return self::EXIT_CODE_NORMAL;
        } else {
            $this->stderr("Errors: ".Json::encode($user->getErrors()));
            return self::EXIT_CODE_ERROR;
        }
    }

    /**
     * Creates default admin user with login admin and password password
     */
    public function actionCreateDefaultAdmin()
    {
        $this->run('create', [
            'name' => \Yii::t('admin/t', 'Administrator'),
            'login' => 'admin',
            'email' => 'admin@domain.com',
            'is_active' => 1,
            'is_super_admin' => 1,
            'password' => 'qwerty',
        ]);
    }

    public function actionUpdate($login)
    {
        /** @var User $user */
        $user = User::findOne(['login' => $login]);
        if ($user === null) {
            throw new Exception('User with this login not found');
        }

        $assignParam = function($from, $to = null, $isBool = false) use ($user) {
            $to = $to === null ? $from : $to;
            if ($this->{$from} !== null) {
                $user->{$to} = $isBool ? ( in_array($this->{$from}, ['yes', 'y', '1']) ? 1: 0) : $this->{$from};
            }
        };

        $assignParam('login');
        $assignParam('name');
        $assignParam('email');
        $assignParam('active', 'is_active', true);
        $assignParam('superadmin', 'is_super_admin', true);

        if ($this->password !== null) {
            $user->passhash = User::hashPassword($this->password);
        }

        if ($user->save()) {
            return self::EXIT_CODE_NORMAL;
        } else {
            $this->stderr("Errors: ".Json::encode($user->getErrors()));
            return self::EXIT_CODE_ERROR;
        }
    }
}