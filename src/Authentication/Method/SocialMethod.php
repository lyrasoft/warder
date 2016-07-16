<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Authentication\Method;

use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Authentication\Method\AbstractMethod;
use Windwalker\Core\User\User;
use Windwalker\Core\Router\CoreRouter;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Data\Data;
use Lyrasoft\Warder\Admin\DataMapper\UserSocialMapper;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Model\UserModel;
use Lyrasoft\Warder\WarderPackage;

/**
 * The SocialMethod class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SocialMethod extends AbstractMethod
{
	/**
	 * Property package.
	 *
	 * @var  WarderPackage
	 */
	protected $warder;

	/**
	 * Property auth.
	 *
	 * @var  \Hybrid_Auth
	 */
	protected $auth;

	/**
	 * WarderMethod constructor.
	 *
	 * @param WarderPackage $warder
	 */
	public function __construct(WarderPackage $warder)
	{
		$this->warder = $warder;
	}

	/**
	 * authenticate
	 *
	 * @param Credential $credential
	 *
	 * @return  integer
	 */
	public function authenticate(Credential $credential)
	{
		if (!class_exists('Hybrid_Auth'))
		{
			throw new \LogicException('Please install hybridauth/hybridauth first.');
		}

		if (!$credential->_provider)
		{
			$this->status = Authentication::INVALID_CREDENTIAL;

			return false;
		}

		$provider = $credential->_provider;

		$providers = $this->warder->app->get('social_login', array());

		// Check provider supported
		if (!in_array($provider, array_keys($providers)))
		{
			if (WINDWALKER_DEBUG)
			{
				throw new \DomainException('Social Login Provider: ' . $provider . ' not supported.');
			}

			$this->status = Authentication::INVALID_CREDENTIAL;

			return false;
		}

		// Start auth
		$auth = $this->getHybridAuth($this->getHAConfig());

		$adapter = $this->doAuthenticate($provider, $auth);

		// Process different data
		$method = 'process' . ucfirst($provider);

		if (!is_callable(array($this, $method)))
		{
			throw new \LogicException(__CLASS__ . '::' . $method . '() not exists.');
		}

		// Process for different providers
		$this->$method($adapter, $credential);

		$userProfile = $adapter->getUserProfile();

		// Default data
		$credential->avatar = $userProfile->photoURL;
		$credential->params = json_encode(array('raw_profile' => $userProfile));

		$this->prepareUserData($adapter, $credential);

		// Check User Socials
		$userSocialMapper = new UserSocialMapper;

		$mapping = array(
			'identifier' => $userProfile->identifier,
			'provider'   => $provider
		);

		$socialMapping = $userSocialMapper->findOne($mapping);

		// Check Socials
		if ($socialMapping->isNull() || User::get($socialMapping->user_id)->isNull())
		{
			$createUser = true;

			// Check user exists
			if ($credential->_loginName)
			{
				$user = User::get(array($credential->_loginName => $credential->{$credential->_loginName}));

				$createUser = $user->isNull();
			}

			if ($createUser)
			{
				$user = $this->createUser($credential);
			}

			$socialMapping = new Data($mapping);
			$socialMapping->user_id = $user->id;

			$userSocialMapper->createOne($socialMapping);
		}

		$user = User::get($socialMapping->user_id);

		$this->postAuthenticate($user, $socialMapping, $credential, $adapter);

		$credential->bind($user);

		$this->status = Authentication::SUCCESS;

		return true;
	}

	/**
	 * createUser
	 *
	 * @param Credential $credential
	 *
	 * @return  UserData
	 */
	protected function createUser(Credential $credential)
	{
		// Create user
		$user = $this->warder->createUserData();

		$user->bind($credential);

		$user->blocked = 0;
		
		$model = new UserModel;
		$model->register($user);

		return $user;
	}

	/**
	 * createHAuth
	 *
	 * @return  array
	 */
	protected function getHAConfig()
	{
		$package = $this->warder->app->getPackage();

		$haConfig = array(
			'base_url' => $package->router->route('social_auth', null, CoreRouter::TYPE_FULL),
			'providers' => array(
				'Facebook' => array(
					'enabled' => $this->warder->app->get('social_login.facebook.enabled'),
					'keys' => array(
						'id'     => $this->warder->app->get('social_login.facebook.id'),
						'secret' => $this->warder->app->get('social_login.facebook.secret'),
					),
					'scope' => $this->warder->app->get('social_login.facebook.scope', 'email')
				),
				'Twitter' => array(
					'enabled' => $this->warder->app->get('social_login.twitter.enabled'),
					'keys' => array(
						'key'    => $this->warder->app->get('social_login.twitter.key'),
						'secret' => $this->warder->app->get('social_login.twitter.secret'),
					),
					'scope' => $this->warder->app->get('social_login.twitter.scope')
				),
				"Google" => array(
					'enabled' => $this->warder->app->get('social_login.google.enabled'),
					'keys' => array(
						'id'     => $this->warder->app->get('social_login.google.id'),
						'secret' => $this->warder->app->get('social_login.google.secret'),
					),
					'scope' => $this->warder->app->get('social_login.google.scope', "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email")
				),
				"Yahoo" => array(
					'enabled' => $this->warder->app->get('social_login.yahoo.enabled'),
					'keys' => array(
						'key'    => $this->warder->app->get('social_login.yahoo.key'),
						'secret' => $this->warder->app->get('social_login.yahoo.secret'),
					),
					'scope' => $this->warder->app->get('social_login.yahoo.scope')
				),
				"GitHub" => array(
					'enabled' => $this->warder->app->get('social_login.github.enabled'),
					'keys' => array(
						'id'     => $this->warder->app->get('social_login.github.id'),
						'secret' => $this->warder->app->get('social_login.github.secret'),
					),
					'scope' => $this->warder->app->get('social_login.github.scope'),
					'wrapper' => array(
						'path' => WINDWALKER_VENDOR . '/hybridauth/hybridauth/additional-providers/hybridauth-github/Providers/GitHub.php',
						'class' => 'Hybrid_Providers_GitHub'
					)
				),
				"OpenID" => array (
					"enabled" => true
				)
			)
		);

		return $haConfig;
	}

	/**
	 * doAuthenticate
	 *
	 * @param string       $provider
	 * @param \Hybrid_Auth $auth
	 *
	 * @return \Hybrid_Provider_Adapter
	 */
	protected function doAuthenticate($provider, \Hybrid_Auth $auth)
	{
		$package = $this->warder->app->getPackage();

		$callbackUrl = $package->router->route('social_login', array('provider' => $provider, CsrfProtection::getFormToken() => 1), CoreRouter::TYPE_FULL);

		return $auth->authenticate($provider, array(
			'hauth_return_to' => $callbackUrl
		));
	}

	/**
	 * prepareUserData
	 *
	 * @param \Hybrid_Provider_Adapter $adapter
	 * @param Credential               $credential
	 *
	 * @return  void
	 */
	protected function prepareUserData(\Hybrid_Provider_Adapter $adapter, Credential $credential)
	{
	}

	/**
	 * postAuthenticate
	 *
	 * @param UserData                 $user
	 * @param Data                     $socialMapping
	 * @param Credential               $credential
	 * @param \Hybrid_Provider_Adapter $adapter
	 *
	 * @return  void
	 */
	protected function postAuthenticate(UserData $user, Data $socialMapping, Credential $credential,
		\Hybrid_Provider_Adapter $adapter)
	{
	}

	/**
	 * Method to get property Auth
	 *
	 * @param array $haConfig
	 *
	 * @return \Hybrid_Auth
	 */
	public function getHybridAuth($haConfig = array())
	{
		if (!$this->auth)
		{
			$this->auth = new \Hybrid_Auth($haConfig);
		}

		return $this->auth;
	}

	/**
	 * Method to set property auth
	 *
	 * @param   \Hybrid_Auth $auth
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setHybridAuth(\Hybrid_Auth $auth)
	{
		$this->auth = $auth;

		return $this;
	}

	/**
	 * processFacebook
	 *
	 * @param \Hybrid_Provider_Adapter $adapter
	 * @param Credential               $credential
	 *
	 * @return  Credential
	 *
	 * @throws \Exception
	 */
	protected function processFacebook(\Hybrid_Provider_Adapter $adapter, Credential $credential)
	{
		$userProfile = $adapter->getUserProfile();

		$loginName = $this->warder->getLoginName();

		// Generate a temp username that usr can edit it later.
		if ($loginName != 'email')
		{
			$username = strtolower(str_replace(' ', '', $userProfile->displayName)) . '-' . $userProfile->identifier;
			$credential->$loginName = $username;
		}

		$credential->email = $userProfile->email;
		$credential->name  = $userProfile->displayName;
		$credential->_loginName = 'email';

		return $credential;
	}

	/**
	 * processTwitter
	 *
	 * @param \Hybrid_Provider_Adapter $adapter
	 * @param Credential               $credential
	 *
	 * @return  Credential
	 */
	protected function processTwitter(\Hybrid_Provider_Adapter $adapter, Credential $credential)
	{
		$userProfile = $adapter->getUserProfile();

		$loginName = $this->warder->getLoginName();

		// Generate a temp username that usr can edit it later.
		if ($loginName != 'email')
		{
			$username = strtolower(str_replace(' ', '', $userProfile->displayName)) . '-' . $userProfile->identifier;
			$credential->$loginName = $username;
		}

		// Twitter cannot get email
		$credential->name  = $userProfile->firstName;

		return $credential;
	}

	/**
	 * processGoogle
	 *
	 * @param \Hybrid_Provider_Adapter $adapter
	 * @param Credential               $credential
	 *
	 * @return  Credential
	 */
	protected function processGoogle(\Hybrid_Provider_Adapter $adapter, Credential $credential)
	{
		$userProfile = $adapter->getUserProfile();

		$loginName = $this->warder->getLoginName();

		// Generate a temp username that usr can edit it later.
		if ($loginName != 'email')
		{
			$username = strtolower(str_replace(' ', '', $userProfile->displayName)) . '-' . $userProfile->identifier;
			$credential->$loginName = $username;
		}

		$credential->email = $userProfile->email;
		$credential->name  = $userProfile->displayName;
		$credential->_loginName = 'email';

		return $credential;
	}

	/**
	 * processYahoo
	 *
	 * @param \Hybrid_Provider_Adapter $adapter
	 * @param Credential               $credential
	 *
	 * @return  Credential
	 */
	protected function processYahoo(\Hybrid_Provider_Adapter $adapter, Credential $credential)
	{
		$userProfile = $adapter->getUserProfile();

		// Yahoo cannot get email
		$credential->name = $userProfile->displayName;

		$loginName = $this->warder->getLoginName();

		// Generate a temp username that usr can edit it later.
		if ($loginName != 'email')
		{
			$username = strtolower(str_replace(' ', '', $userProfile->displayName)) . '-' . $userProfile->identifier;
			$credential->$loginName = $username;
		}

		return $credential;
	}

	/**
	 * processGithub
	 *
	 * @param \Hybrid_Provider_Adapter $adapter
	 * @param Credential               $credential
	 *
	 * @return  Credential
	 */
	protected function processGitHub(\Hybrid_Provider_Adapter $adapter, Credential $credential)
	{
		$userProfile = $adapter->getUserProfile();

		$loginName = $this->warder->getLoginName();

		// Generate a temp username that usr can edit it later.
		if ($loginName != 'email')
		{
			$username = strtolower(str_replace(' ', '', $userProfile->displayName)) . '-' . $userProfile->identifier;
			$credential->$loginName = $username;
		}

		$credential->email = $userProfile->email;
		$credential->name  = $userProfile->displayName;
		$credential->_loginName = 'email';

		return $credential;
	}
}
