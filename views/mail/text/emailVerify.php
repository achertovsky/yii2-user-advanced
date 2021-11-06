<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/verify-email', 'token' => $user->verification_token]);
?>
<?= Yii::t("app", "Hello")?>,

<?= Yii::t("app", "Follow the link below to verify your email")?>:

<?= $verifyLink ?>
