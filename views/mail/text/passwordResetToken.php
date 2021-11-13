<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/reset-password', 'token' => $user->password_reset_token]);
?>
<?= Yii::t("ach-user", "Hello")?>,

<?=Yii::t("ach-user", "Follow the link below to reset your password")?>:

<?= $resetLink ?>
