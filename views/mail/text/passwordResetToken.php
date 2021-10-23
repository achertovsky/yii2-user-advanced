<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/reset-password', 'token' => $user->password_reset_token]);
?>
<?= Yii::t("site", "Hello")?>,

<?=Yii::t("site", "Follow the link below to reset your password")?>:

<?= $resetLink ?>
