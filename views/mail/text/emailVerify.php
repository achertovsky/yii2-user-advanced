<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/verify-email', 'token' => $user->verification_token]);
?>
<?= Yii::t("ach-user", "Hello")?>,

<?= Yii::t("ach-user", "Follow the link below to verify your email")?>:

<?= $verifyLink ?>
