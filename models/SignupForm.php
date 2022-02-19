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
    

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('ach-user', 'Your email address'),
            'password' => Yii::t('ach-user', 'Password'),
            'repeatPassword' => Yii::t('ach-user', 'Repeat Password'),
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
            if (array_key_exists('targetClass', $rule) && $rule['targetClass'] == User::class) {
                $rules[$key]['targetClass'] = '\achertovsky\user\models\User';
            }
            // for i18n purpose
            if (in_array('email', $rule) && in_array('unique', $rule)) {
                unset($rules[$key]);
            }
        }
        $rules = ArrayHelper::merge(
            $rules,
            [
                ['email', 'unique', 'targetClass' => '\achertovsky\user\models\User', 'message' => Yii::t('ach-user', 'This email address has already been taken').'.'],

                ['repeatPassword', 'required'],
                ['repeatPassword', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
                ['repeatPassword', 'compare', 'compareAttribute' => "password"],
            ]
        );
        return $rules;
    }
}
