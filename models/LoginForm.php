<?php

namespace achertovsky\user\models;

use Yii;
use achertovsky\user\models\User;
use common\models\LoginForm as ModelsLoginForm;

/**
 * Login form
 */
class LoginForm extends ModelsLoginForm
{
    /** @var string */
    public $email;
    /**
     * @var User
     */
    private $_user;

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('ach-user', 'Your email address'),
            'password' => Yii::t('ach-user', 'Password'),
            'rememberMe' => Yii::t('ach-user', 'Remember Me')
        ];
    }


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
        return $rules;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
