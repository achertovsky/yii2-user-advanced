<?php

namespace achertovsky\user\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use achertovsky\user\Asset;
use yii\filters\VerbFilter;
use yii\authclient\AuthAction;
use yii\filters\AccessControl;
use achertovsky\user\models\User;
use yii\authclient\ClientInterface;
use frontend\models\VerifyEmailForm;
use yii\web\BadRequestHttpException;
use achertovsky\user\models\LoginForm;
use yii\base\InvalidArgumentException;
use achertovsky\user\models\SignupForm;
use achertovsky\helpers\AjaxValidationTrait;
use achertovsky\user\models\ResetPasswordForm;
use achertovsky\user\handlers\RegistrationHandler;
use achertovsky\user\actions\EmailInteractionAction;

class DefaultController extends Controller
{
    use AjaxValidationTrait;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => AuthAction::class,
                // if user is not logged in, will try to log him in, otherwise
                // will try to connect social account to user.
                'successCallback' => [$this, 'authenticate'],
            ],
            'request-password-reset' => [
                'class' => EmailInteractionAction::class,
                'viewName' => 'requestPasswordResetToken',
                'emailFunctionName' => 'sendPasswordResetRequest',
                'type' => 'reset',
            ],
            'resend-verification-email' => [
                'class' => EmailInteractionAction::class,
                'viewName' => 'resendVerificationEmail',
                'emailFunctionName' => 'sendEmailConfirm',
                'userStatus' => User::STATUS_INACTIVE,
                'type' => 'resend',
            ],
        ];
    }

    /**
     * Register asset for all views of this controller
     *
     * @param Action $action the action to be executed.
     * @return boolean
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        Asset::register($this->getView());
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * oauth success callback
     *
     * @return void
     */
    public function authenticate(ClientInterface $client)
    {
        $user = User::findOne(['email' => $client->getUserAttributes()['email']]);
        if (!is_null($user)) {
            Yii::$app->user->login($user);
            $this->action->successUrl = Url::home();
            return;
        }
        $result = RegistrationHandler::signup(
            $client->getUserAttributes()['email'],
            Yii::$app->security->generateRandomString(),
            User::STATUS_ACTIVE,
            $user
        );
        Yii::$app->user->login($user);
        if (!$result) {
            Yii::$app->session->setFlash('error', Yii::t('ach-user', "Unexpected issue occured. Signup/login was not successfull, sorry"));
            $this->action->successUrl = Url::previous();
        } else {
            $this->action->successUrl = Url::home();
        }
    }

    /**
     * Classical email login functionality
     *
     * @return mixed
     */
    public function actionLogin()
    {
        Url::remember();
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Code sugar
     *
     * @param SignupForm $model
     * @return void
     */
    protected function renderSignup($model)
    {
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Classical email signup functionality
     *
     * @return mixed
     */
    public function actionSignup()
    {
        Url::remember();
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $this->ajaxValidation($model);

            if (!$model->validate()) {
                return $this->renderSignup($model);
            }
            if (!RegistrationHandler::signup($model->email, $model->password, User::STATUS_INACTIVE, $user)) {
                Yii::$app->session->setFlash('error', Yii::t('ach-user', 'User was not created. Please, contact support'));
                return $this->goBack();
            }
            RegistrationHandler::signupEmail($user);
            Yii::$app->session->setFlash('success', Yii::t('ach-user', 'Thank you for registration. Please check your inbox for verification email').'.');
            return $this->goHome();
        }

        return $this->renderSignup($model);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('ach-user', 'New password saved').'.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', Yii::t('ach-user', 'Your email has been confirmed').'!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', Yii::t('ach-user', 'Sorry, we are unable to verify your account with provided token').'.');
        return $this->goHome();
    }
}
