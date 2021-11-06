<?php

namespace achertovsky\user\models;

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
