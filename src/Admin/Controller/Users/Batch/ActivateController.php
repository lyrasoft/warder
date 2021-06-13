<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Lyrasoft\Warder\Admin\Controller\Users\Batch;

use Phoenix\Controller\Batch\AbstractBatchController;
use Windwalker\Legacy\Data\DataInterface;

/**
 * The UnpublishController class.
 *
 * @since  1.0
 */
class ActivateController extends AbstractBatchController
{
    /**
     * Property action.
     *
     * @var  string
     */
    protected $action = 'activate';

    /**
     * Property allowNullData.
     *
     * @var  boolean
     */
    protected $allowNullData = true;

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix = 'warder.';

    /**
     * Property data.
     *
     * @var  array
     */
    protected $data = [
        'activation' => '',
    ];

    /**
     * save
     *
     * @param   string|int    $pk
     * @param   DataInterface $data
     *
     * @return  mixed
     * @throws \Exception
     */
    protected function save($pk, DataInterface $data)
    {
        $data->{$this->keyName} = $pk;

        $data->activation = '';

        $this->repository->save($data);
    }
}
