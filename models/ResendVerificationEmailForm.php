<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use achertovsky\user\handlers\UserMailHandler;
use frontend\models\ResendVerificationEmailForm as ModelsResendVerificationEmailForm;

class ResendVerificationEmailForm extends ModelsResendVerificationEmailForm
{
    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $user = User::findOne([
            'email' => $this->email,
            'status' => User::STATUS_INACTIVE
        ]);

        if ($user === null) {
            return false;
        }

        return UserMailHandler::sendEmailConfirm($user);
    }
}