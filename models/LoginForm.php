<?php

namespace achertovsky\user\models;

use Yii;
use common\models\LoginForm as ModelsLoginForm;

/**
 * Login form
 */
class LoginForm extends ModelsLoginForm
{
    /** @var string */
    public $email;
    /** @var string */
    public $reCaptcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            // username and password are both required
            [['email', 'password'], 'required'],
            ['email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            
        ];
        if (Yii::$app->has('reCaptcha')) {
            $rules[] = [
                ['reCaptcha'],
                \himiklab\yii2\recaptcha\ReCaptchaValidator2::class,
                'uncheckedMessage' => Yii::t('app', 'Please confirm that you are not a robot.'),
            ];
        }
        return $rules;
    }
}
