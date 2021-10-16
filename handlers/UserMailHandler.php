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
     * Undocumented function
     *
     * @param User $user
     * @param array $params
     * @return void
     */
    public static function sendPasswordResetRequest(User $user, $params = [])
    {
        return static::sendMessage(
            $user->email,
            Yii::t("site", "Password reset for ".Yii::$app->name),
            'passwordResetToken',
            ArrayHelper::merge(
                [
                    'user' => $user,
                ],
                $params
            )
        );
    }

    /**
     * Sends welcome message with email confirmation
     *
     * @param User $user
     * @param array $params
     * @return void
     */
    public static function sendEmailConfirm(User $user)
    {
        return static::sendMessage(
            $user->email,
            Yii::t("site", "Welcome!"),
            'signup',
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
     * @return bool
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
