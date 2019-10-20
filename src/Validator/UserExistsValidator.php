<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Validator;

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Mailer\Punycode;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\User\User;
use Windwalker\Validator\AbstractValidator;

/**
 * The UseeExistsValidator class.
 *
 * @since  1.0
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
        $this->key   = $key;
    }

    /**
     * Test value and return boolean
     *
     * @param mixed $value
     *
     * @return bool
     * @throws ValidateFailException
     */
    protected function test($value)
    {
        if ($this->field === 'email') {
            $value = Punycode::toAscii($value);
        }

        $user = User::get([$this->field => $value]);

        if ($user->notNull()) {
            throw new ValidateFailException(Translator::sprintf('warder.user.save.message.exists', $this->field,
                $value));
        }

        return true;
    }
}
