Works only for yii2-advanced-template ~2.0.14

Will override default template user interaction, including:
- routes
- identity class


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
-- add to frontend/backend components -> request 'baseUrl',
```
'request' => [
    /**
     * ...
     */
    'baseUrl' => '',
    //'baseUrl' => '/backend', //for backend
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
        'senderEmail' => 'desired.email@mailbox.com', // desired email
    ],
],
```

## oauth
to make it work just configure corresponding class in components -> authClientCollection
facebook, google works for sure. others wasnt tested, sorry
```
'authClientCollection' => [
    'class' => 'yii\authclient\Collection',
    'clients' => [
        /**
         * key name gonna be displayed on signup/login page
         * name it as you want it to displayed
         */
        'facebook' => [ 
            'class' => 'yii\authclient\clients\Facebook',
            'clientId' => '',
            'clientSecret' => '',
        ],
        // ... so on
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
-- frontend\models\ResendVerificationEmailForm
-- frontend\models\PasswordResetRequestForm

- Cleanup unused views:
-- backend/views/site/login.php
-- frontend/views/site/login.php
-- frontend/views/site/requestPasswordResetToken.php
-- frontend/views/site/resendVerificationEmail.php
-- frontend/views/site/resetPassword.php
-- frontend/views/site/signup.php
-- common/mail/*

