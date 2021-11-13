<?php

namespace achertovsky\user\models;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\ResetPasswordForm as ModelsResetPasswordForm;

/**
 * Password reset form
 */
class ResetPasswordForm extends ModelsResetPasswordForm
{
    /**
     * @var string
     */
    public $repeatPassword;

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('ach-user', 'Password'),
            'repeatPassword' => Yii::t('ach-user', 'Repeat Password'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['repeatPassword', 'required'],
                ['repeatPassword', 'compare', 'compareAttribute' => "password"],
            ]
        );
    }
}
