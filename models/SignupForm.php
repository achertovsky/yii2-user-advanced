<?php

namespace achertovsky\user\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    /** @var string */
    public $email;
    /** @var string */
    public $password;
    /** @var string */
    public $repeatPassword;
    /** @var string */
    public $reCaptcha;
    

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Your email address'),
            'password' => Yii::t('app', 'Password'),
            'repeatPassword' => Yii::t('app', 'Repeat Password'),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\achertovsky\user\models\User', 'message' => Yii::t('app', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['repeatPassword', 'required'],
            ['repeatPassword', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => "password"],
        ];
        if (Yii::$app->has('reCaptcha')) {
            $rules[] = [
                ['reCaptcha'],
                \himiklab\yii2\recaptcha\ReCaptchaValidator2::class,
                'uncheckedMessage' => Yii::t('app', 'Please confirm that you are not a bot.'),
            ];
        }
        return $rules;
    }
}
