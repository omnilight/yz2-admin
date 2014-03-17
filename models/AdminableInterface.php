<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.12.13
 * Time: 13:53
 */

namespace yz\admin\models;

/**
 * Interface AdminableInterface
 * @package yz\admin\models
 */
interface AdminableInterface
{
    /**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle();

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural();

    /**
     * Returns attributes values, ex.:
     * ~~~
     *   [
     *      'genre' => [
     *          'male' => 'Male',
     *          'female' => 'Female',
     *   ]
     * ~~~
     * @return array
     */
    public function attributeValues();

    /**
     * Returns values for specified attribute, or throws an exception if passed attribute
     * is not found in {@see attributeValues()}
     * @param string $attribute
     * @return array
     * @throws \yii\base\Exception
     */
    public function getAttributeValues($attribute);
} 