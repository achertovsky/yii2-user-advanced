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
    public static function sendPasswordResetRequest($email)
    {
        $user = User::findByEmail($email);
        if (empty($user)) {
            return false;
        }
        return static::sendMessage(
            $user->email,
            Yii::t("app", "Password reset for ").Yii::$app->name,
            'passwordResetToken',
            [
                'user' => $user,
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
            Yii::t("app", "Welcome!"),
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
        return $mailer->compose(["html" => $view, "text" => "text/$view"], $params)
            ->setTo($to)
            ->setFrom(Yii::$app->getModule('user')->senderEmail)
            ->setSubject($subject)
            ->send();
    }
}
