<?php

namespace achertovsky\user\models;

use Yii;
use frontend\models\SignupForm as ModelsSignupForm;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class SignupForm extends ModelsSignupForm
{
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
     * Remove the username validation
     * Override user targetClass
     *
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $key => $rule) {
            if (in_array('username', $rule)) {
                unset($rules[$key]);
                continue;
            }
            if (array_key_exists('targetClass', $rule)) {
                switch ($rule['targetClass']) {
                    case User::class:
                        $rules[$key]['targetClass'] = '\achertovsky\user\models\User';
                        break;
                }
            }
        }
        $rules = ArrayHelper::merge(
            $rules,
            [
                ['email', 'unique', 'targetClass' => '\achertovsky\user\models\User', 'message' => Yii::t('app', 'This email address has already been taken.')],

                ['repeatPassword', 'required'],
                ['repeatPassword', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
                ['repeatPassword', 'compare', 'compareAttribute' => "password"],
            ]
        );
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
