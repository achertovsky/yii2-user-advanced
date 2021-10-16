<?php

namespace achertovsky\user\handlers;

use Yii;
use yii\base\BaseObject;
use achertovsky\user\models\User;
use achertovsky\user\handlers\UserMailHandler;

/**
 * Single point to signup the user
 */
class RegistrationHandler extends BaseObject
{
    /**
     * Creates user
     *
     * @param string $email
     * @param string $password
     * @param integer $status
     * @param \yii\web\User $user
     * In debug/proceed purpose, to get user object
     * @return void
     */
    public static function signup($email, $password, $status = User::STATUS_INACTIVE, User &$user = null)
    {
        $password = Yii::$app->security->generatePasswordHash($password);
        $user = new User(
            [
                'email' => $email,
                'password_hash' => $password,
                'status' => $status,
                'verification_token' => md5(time().$email.$password),
            ]
        );
        return $user->save();
    }

    /**
     * Sends email confirmation email
     *
     * @param User $user
     * @return void
     */
    public static function signupEmail(User $user)
    {
        return UserMailHandler::sendEmailConfirm($user);
    }
}
