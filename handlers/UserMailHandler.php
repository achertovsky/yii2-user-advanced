<?php

namespace achertovsky\user\handlers;

use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use achertovsky\user\models\User;

/**
 * Contains emails send for every user request
 */
class UserMailHandler extends BaseObject
{
    /**
     * Module views
     *
     * @return void
     */
    public function getViewPath()
    {
        return '@ach-user/views/mail';
    }
    
    /**
     * Password reset request
     *
     * @param string $email
     * @return void
     */
    public static function sendPasswordResetRequest($email, $token)
    {
        $user = User::findByEmail($email);
        if (empty($user)) {
            return false;
        }
        return static::sendMessage(
            $user->email,
            Yii::t("ach-user", "Password reset for")." ".Yii::$app->name,
            'passwordResetToken',
            [
                'user' => $user,
                'token' => $token,
            ]
        );
    }

    /**
     * Password reset request
     *
     * @param string $email
     * @param User $user
     * @return void
     */
    public static function sendEmailConfirm($email, User $user = null)
    {
        if (is_null($user)) {
            $user = User::findByEmail($email, User::STATUS_INACTIVE);
            if (empty($user)) {
                return false;
            }
        }
        return static::sendMessage(
            $user->email,
            Yii::t("ach-user", "Welcome").'!',
            'emailVerify',
            [
                'user' => $user,
            ]
        );
    }
    
    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array  $params
     *
     * @return boolean
     */
    protected static function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->setViewPath('@ach-user/views/mail');
        try {
            return $mailer->compose(["html" => $view, "text" => "text/$view"], $params)
                ->setTo($to)
                ->setFrom(Yii::$app->getModule('user')->senderEmail)
                ->setSubject($subject)
                ->send();
        } catch (\Exception $ex) {
            Yii::error($ex);
            return false;
        }
    }
}
