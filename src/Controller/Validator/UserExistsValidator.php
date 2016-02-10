<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Controller\Validator;

use Windwalker\Core\Authentication\User;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Validator\AbstractValidator;

/**
 * The UseeExistsValidator class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UserExistsValidator extends AbstractValidator
{
	/**
	 * Property value.
	 *
	 * @var  string
	 */
	protected $field;

	/**
	 * Property key.
	 *
	 * @var  string
	 */
	protected $key;

	/**
	 * UserExistsValidator constructor.
	 *
	 * @param string $field
	 * @param string $key
	 */
	public function __construct($field, $key = 'id')
	{
		$this->field = $field;
		$this->key = $key;
	}

	/**
	 * Test value and return boolean
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 * @throws ValidFailException
	 */
	protected function test($value)
	{
		$user = User::get(array($this->field => $value));

		if ($user->notNull())
		{
			throw new ValidFailException(sprintf('User %s: %s exists.', $this->field, $value));
		}

		return true;
	}
}
