Will override default template user interaction, including routes and etc


# installation, automated

in progress

# installation, manual

## pretty url
expected to work for template with enabled pretty routing and defined base route, so

-- to make it work uncomment\add part of components in your @app config/main.php 
```
'urlManager' => [
    /**
     * 'baseUrl' is not defined at just-cloned template. expected it to be added
     */
    'baseUrl' => '/',
    //'baseUrl' => '/backend', //for backend
    
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    ],
],
```
-- add to components -> request 'baseUrl' => '',
```
'request' => [
    /**
     * ...
     */
    'baseUrl' => '',
],
```
-- add to backend config components -> request 'baseUrl' => '/backend',
```
'request' => [
    /**
     * ...
     */
    'baseUrl' => '/backend',
],
```

## add to modules section
```
'modules' => [
    'user' => [
        'class' => 'achertovsky\user\Module',
        /**
        * Current email will be used as sender in any email of this module
        *
        * @var string
        */
        'senderEmail' => 'alexzaets.dev@gmail.com',
    ],
],
```

## migrations
Path is added via bootstrap, just `./yii migrate/up`

## suggestions:
- Higly suggested to do following actions in default frontend\controllers\SiteController:
-- remove actionLogin
-- remove actionLogout
-- remove actionSignup
-- remove actionRequestPasswordReset
-- remove actionResetPassword
-- remove actionVerifyEmail
-- remove actionResendVerificationEmail
-- remove behaviors (unless you need it)
-- remove captcha action from actions() method
- Higly suggested to do following actions in default backend\controllers\SiteController:
-- remove actionLogin
-- remove actionLogout
-- remove behaviors (unless you need it)

- Cleanup unused classes:
-- frontend\models\VerifyEmailForm
-- frontend\models\ResetPasswordForm

- Cleanup unused views:
-- backend/views/site/login.php
-- frontend/views/site/login.php
-- frontend/views/site/requestPasswordResetToken.php
-- frontend/views/site/resendVerificationEmail.php
-- frontend/views/site/resetPassword.php
-- frontend/views/site/signup.php
-- common/mail/*

