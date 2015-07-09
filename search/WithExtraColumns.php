<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 24.06.2015
 * Time: 17:22
 */

namespace yz\admin\search;
use yii\db\ActiveQuery;


/**
 * Trait WithExtraColumns
 */
trait WithExtraColumns
{
    protected static function extraColumns()
    {
        return [];
    }

    protected static function selectWithExtraColumns($select)
    {
        $select = (array)$select;
        return array_merge($select, array_map([get_called_class(), 'columnName'], static::extraColumns()));
    }

    /**
     * @param ActiveQuery $query
     */
    protected function filtersForExtraColumns($query)
    {
        foreach (self::extraColumns() as $attribute) {
            $query->andFilterWhere(['like', self::columnName($attribute, false), $this->getAttribute($attribute)]);
        }
    }

    /**
     * Transforms alias of the column from table__column format into table.column as table__column
     * @param $alias
     * @param bool $withAlias
     * @return mixed|string
     */
    protected static function columnName($alias, $withAlias = true)
    {
        $name = str_replace('__', '.', $alias);
        if ($withAlias)
            return $name . ' as ' . $alias;
        else
            return $name;
    }
}