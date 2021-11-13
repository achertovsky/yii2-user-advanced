<?php

namespace achertovsky\user\actions;

use Yii;
use yii\base\Action;
use achertovsky\user\models\User;
use achertovsky\user\handlers\UserMailHandler;
use achertovsky\user\models\UserEmailInteractionForm;

class EmailInteractionAction extends Action
{
    /**
     * @var string
     */
    public $viewName;
    /**
     * @var string
     */
    public $emailFunctionName;
    /**
     * @var integer
     */
    public $userStatus = User::STATUS_ACTIVE;

    /**
     * @var bookean
     */
    public $type;

    /**
     * Fullfills password restore/email verify resend request
     *
     * @return mixed
     */
    public function run()
    {
        $model = new UserEmailInteractionForm(
            [
                'status' => $this->userStatus,
            ]
        );
        $token = null;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($this->type == 'reset') {
                $user = User::findByEmail($model->email);
                if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
                    if (!$user->save()) {
                        Yii::$app->session->setFlash('error', Yii::t('ach-user', 'Sorry, we are unable to process request for the provided email address').'.');
                        return $this->controller->goHome();
                    }
                }
                $token = $user->password_reset_token;
            }
            if (call_user_func([UserMailHandler::class, $this->emailFunctionName], $model->email, $token)) {
                Yii::$app->session->setFlash('success', Yii::t('ach-user', 'Check your email for further instructions').'.');
                return $this->controller->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('ach-user', 'Sorry, we are unable to process request for the provided email address').'.');
            }
        }

        /**
         * If user didnt get into if above - he got validation issue
         */
        if (Yii::$app->request->isPost) {
            $error = $model->getFirstError('email');
            /**
             * only in one case error is empty - when no user exist in system.
             * show success anyway, to not let define email existance in the system
             */
            if ($model->hasErrors() && empty($error)) {
                Yii::$app->session->setFlash('success', Yii::t('ach-user', 'Check your email for further instructions').'.');
            }
        }

        return $this->controller->render($this->viewName, [
            'model' => $model
        ]);
    }
}