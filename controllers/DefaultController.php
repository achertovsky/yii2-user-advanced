<?php

namespace achertovsky\user\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use achertovsky\user\models\User;
use yii\authclient\ClientInterface;
use achertovsky\user\models\LoginForm;
use achertovsky\user\models\SignupForm;
use achertovsky\traits\AjaxValidationTrait;
use frontend\models\ResendVerificationEmailForm;
use achertovsky\user\handlers\RegistrationHandler;

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
                'successCallback' => [$this, 'authenticate']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['signup', 'auth', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
            Yii::$app->session->setFlash('error', Yii::t('site', "Unexpected issue occured. Signup/login was not successfull, sorry"));
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
                Yii::$app->session->setFlash('error', 'User was not created. Please, contact support');
                return $this->goBack();
            }
            RegistrationHandler::signupEmail($user);
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
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
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
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
            Yii::$app->session->setFlash('success', 'New password saved.');

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
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
