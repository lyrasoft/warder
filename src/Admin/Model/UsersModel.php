<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Model;

use Lyrasoft\Warder\Table\WarderTable;
use Phoenix\Model\Filter\FilterHelperInterface;
use Phoenix\Model\ListModel;
use Windwalker\Query\Query;

/**
 * The UsersModel class.
 *
 * @since  1.0
 */
class UsersModel extends ListModel
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'users';

    /**
     * Property allowFields.
     *
     * @var  array
     */
    protected $allowFields = ['activation'];

    /**
     * Property fieldMapping.
     *
     * @var  array
     */
    protected $fieldMapping = [];

    /**
     * configureTables
     *
     * @return  void
     */
    protected function configureTables()
    {
        $this->addTable('user', WarderTable::USERS)
            ->addTable('social', WarderTable::USER_SOCIALS, 'social.user_id = user.id')
//			->addTable('gmap',   WarderTable::USER_GROUP_MAPS, 'group.user_id = user.id')
//			->addTable('group',  WarderTable::GROUPS,          'gmap.group_id = group.id');
        ;
    }

    /**
     * The prepare getQuery hook
     *
     * @param Query $query The db query object.
     *
     * @return  void
     */
    protected function prepareGetQuery(Query $query)
    {
        // Add your logic
    }

    /**
     * The post getQuery object.
     *
     * @param Query $query The db query object.
     *
     * @return  void
     */
    protected function postGetQuery(Query $query)
    {
        // Add your logic
    }

    /**
     * Configure the filter handlers.
     *
     * Example:
     * ``` php
     * $filterHelper->setHandler(
     *     'user.date',
     *     function($query, $field, $value)
     *     {
     *         $query->where($field . ' >= ' . $value);
     *     }
     * );
     * ```
     *
     * @param FilterHelperInterface $filterHelper The filter helper object.
     *
     * @return  void
     */
    protected function configureFilters(FilterHelperInterface $filterHelper)
    {
        $filterHelper->setHandler('activation', function (Query $query, $field, $value) {
            if (((string) $value) === '0') {
                $query->where('CHAR_LENGTH(user.activation) > 0');
            } elseif ($value == 1) {
                $query->where('CHAR_LENGTH(user.activation) = 0');
            }
        });
    }

    /**
     * Configure the search handlers.
     *
     * Example:
     * ``` php
     * $searchHelper->setHandler(
     *     'user.title',
     *     function($query, $field, $value)
     *     {
     *         return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
     *     }
     * );
     * ```
     *
     * @param FilterHelperInterface $searchHelper The search helper object.
     *
     * @return  void
     */
    protected function configureSearches(FilterHelperInterface $searchHelper)
    {
        // Configure searches
    }
}
