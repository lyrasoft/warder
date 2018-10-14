# Windwalker Warder

## Installation Via Composer

Add this to `composer.json` and run `composer update`.

``` json
{
    "windwalker/warder": "1.*"
}
```

### Register Package

Register warder to Windwalker, you can prepare an `admin` and `front` package to support backend and frontend.

``` php
// src/Windwalker/Windwalker.php

use Phoenix\PhoenixPackage;
use Lyrasoft\Warder\WarderPackage;

// ...

    public static function loadPackages()
	{
		return array(
			'system'  => new SystemPackage,
			'phoenix' => new PhoenixPackage, // You must install phoenix first
			'warder'  => new WarderPackage, // Add warder package
			
			// Prepare an admin and frontend package
			'admin'   => AdminPackage,
			'front'   => FrontPackage
		);
	}
	
// ...
```

### Migration

Run `php bin/console migration migrate -p=warder --seed`

Or copy `vendor/windwalker/warder/src/Migration` and `vendor/windwalker/warder/src/Seed` files to 

`resources/migration` and `resources/seed`

You can add your own columns to support more profiles.

### Copy Config

Copy `vendor/windwalker/warder/src/config.dist.yml` to `etc/package/warder.yml`.

Or copy this config code:

``` yaml
user:
    login_name: username
    default_group: registered
    session_name: user

table:
    users: users
    user_socials: user_socials
    groups: groups
    user_group_maps: user_group_maps
    actions: actions

methods:
    warder: \Lyrasoft\Warder\Authentication\Method\WarderMethod
    # social: \Lyrasoft\Warder\Authentication\Method\SocialMethod

frontend:
    package: front
    view:
        extends: _global.html
    redirect:
        login: home
        logout: login
        forget: login
    login:
        return_key: return
    language:
        prefix: warder.

admin:
    package: admin
    view:
        extends: _global.admin.admin
    redirect:
        login: home
        logout: login
    login:
        return_key: return
    language:
        prefix: warder.

class:
    handler: Lyrasoft\Warder\Handler\UserHandler
    data: Lyrasoft\Warder\Data\UserData
```

If your package not named `admin` and `front`, set the package alias in this config.

### Register Routing

Add Warder's routing config and namespace aliases to your package that your package can auto fetch Warder pages.
 
``` php
// src/Front/FrontPackage.php

use Lyrasoft\Warder\Helper\WarderHelper;

// ...

	// ...

	public function loadRouting()
	{
		// ...

		$routes = array_merge($routes, WarderHelper::getFrontendRouting());

		return $routes;
	}
```

Now go to `/{front@routing}/login` your will see a login page auto fetched.

![p-2016-02-28-001](https://cloud.githubusercontent.com/assets/1639206/13378410/cd56734e-de40-11e5-9577-3e8510d2ea53.jpg)

You can do same thing to `admin` package

``` php
// src/Admin/AdminPackage.php

// ...

    public function loadRouting()
	{
		// ...

		$routes = array_merge($routes, WarderHelper::getAdminRouting());

		return $routes;
	}
	
// ...
```

Then you can see User admin at `/{admin@routing}/users`:

![p-2016-02-28-002](https://cloud.githubusercontent.com/assets/1639206/13378429/81d2ab26-de41-11e5-8721-2406162bb230.jpg)

![p-2016-02-28-003](https://cloud.githubusercontent.com/assets/1639206/13378428/81ce25f6-de41-11e5-9e8b-79cde415f061.jpg)

## Override Objects and Templates

You can override warder's objects in your package. For example, create `Front/Controller/User/LoginGetController.php` 
will load priority than `Windwalker/Warder/Controller/User/LoginGetController.php`. You can override something in this class.

``` php
<?php

namespace Front\Controller\User;

class LoginGetController extends \Lyrasoft\Warder\Controller\User\LoginGetController
{
	protected function prepareModelState(Model $repository)
	{
		// Do something
		
		parent::prepareModelState($repository);
	}
}
```

These files can be override:

Front:

```
# Controller
src/Controller/User/AuthController.php
src/Controller/User/Forget/CompleteGetController.php
src/Controller/User/Forget/ConfirmGetController.php
src/Controller/User/Forget/ConfirmSaveController.php
src/Controller/User/Forget/RequestGetController.php
src/Controller/User/Forget/RequestSaveController.php
src/Controller/User/Forget/ResetGetController.php
src/Controller/User/Forget/ResetSaveController.php
src/Controller/User/LoginGetController.php
src/Controller/User/LoginSaveController.php
src/Controller/User/LogoutSaveController.php
src/Controller/User/Profile/GetController.php
src/Controller/User/Profile/SaveController.php
src/Controller/User/Registration/ActivateSaveController.php
src/Controller/User/Registration/RegistrationGetController.php
src/Controller/User/Registration/RegistrationSaveController.php 
src/Controller/User/SocialLoginController.php

# Form
src/Form/Profile/EditDefinition.php
src/Form/User/ForgetConfirmDefinition.php
src/Form/User/ForgetRequestDefinition.php
src/Form/User/LoginDefinition.php
src/Form/User/RegistrationDefinition.php
src/Form/User/ResetDefinition.php

# Model
src/Model/ProfileModel.php
src/Model/UserModel.php

# Template
src/Templates/profile/profile.blade.php
src/Templates/user/forget/complete.blade.php
src/Templates/user/forget/confirm.blade.php
src/Templates/user/forget/request.blade.php
src/Templates/user/forget/reset.blade.php
src/Templates/user/login.blade.php
src/Templates/user/mail/forget.blade.php
src/Templates/user/mail/registration.blade.php
src/Templates/user/registration.blade.php

# View
src/View/Profile/ProfileHtmlView.php
src/View/User/UserHtmlView.php
```

Admin:

```
# Controller
src/Admin/Controller/User/GetController.php
src/Admin/Controller/User/LoginGetController.php
src/Admin/Controller/User/LoginSaveController.php
src/Admin/Controller/User/LogoutSaveController.php
src/Admin/Controller/User/SaveController.php
src/Admin/Controller/Users/Batch/ActivateController.php
src/Admin/Controller/Users/Batch/BlockController.php
src/Admin/Controller/Users/Batch/UnblockController.php
src/Admin/Controller/Users/Batch/UpdateController.php
src/Admin/Controller/Users/BatchController.php
src/Admin/Controller/Users/CopyController.php
src/Admin/Controller/Users/DeleteController.php
src/Admin/Controller/Users/FilterController.php
src/Admin/Controller/Users/GetController.php

# Form
src/Admin/Form/User/EditDefinition.php
src/Admin/Form/User/LoginDefinition.php
src/Admin/Form/Users/BatchDefinition.php
src/Admin/Form/Users/FilterDefinition.php

# Model
src/Admin/Model/UserModel.php

# Templates
src/Admin/Templates/user/login.blade.php
src/Admin/Templates/user/toolbar.blade.php
src/Admin/Templates/user/user.blade.php
src/Admin/Templates/users/batch.blade.php
src/Admin/Templates/users/modal.blade.php
src/Admin/Templates/users/toolbar.blade.php
src/Admin/Templates/users/users.blade.php

# View
src/Admin/View/User/UserHtmlView.php
src/Admin/View/Users/UsersHtmlView.php
```

## Override UserHandler and UserData

Change `class.data` in config file that you can add some new methods:

``` yaml
# etc/package/warder.yml

# ...

class:
    handler: Lyrasoft\Warder\Handler\UserHandler
    data: MyUserData
```

``` php
class MyUserData extends \Lyrasoft\Warder\Data\UserData
{
    const CUSTOMER = 0;
    const FREELANCER = 1;

    public function isFreelancer()
    {
        return (bool) $this->freelancer == static::FREELANCER;
    }
}
```

``` php
$user = User::get($id);

$user->isFreelancer();
```

## Social Login

Warder use [Hybrid Auth](http://hybridauth.sourceforge.net/) to support multiple OAuth social login.

Please install `"hybridauth/hybridauth": "^2.6"` first.

After composer updated, copy `vendor/wainwaler/warder/src/secret.dist.yml` to `etc/secret.yml`

``` yaml
#Social Login
social_login:
    facebook:
        enabled: false
        id:
        secret:
        scope: email
    twitter:
        enabled: false
        key:
        secret:
        scope:
    google:
        enabled: false
        id:
        secret:
        scope: 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
    yahoo:
        enabled: false
        key:
        secret:
        scope:
    github:
        enabled: false
        id:
        secret:
        scope:
```

> Currently Warder only support facebook, twitter, google, yahoo and github.

Go to [Documentation](http://hybridauth.sourceforge.net/userguide.html) to see how to register apps and get API key and secret code.
 
When a social provider set to enabled, the button will auto appear to login page:

![p-2016-02-28-004](https://cloud.githubusercontent.com/assets/1639206/13378560/3a912b62-de45-11e5-8469-daed75853437.jpg)

> If you use `Additional Providers` like `GitHub`, you must copy provider class file from `vendor/hybridauth/hybridauth/additional-providers` 
to `vendor/hybridauth/hybridauth/hybridauth/Hybrid/Providers`

