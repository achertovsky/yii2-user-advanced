<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p><?= Yii::t("ach-user", "Hello")?>,</p>

    <p><?=Yii::t("ach-user", "Follow the link below to verify your email")?>:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
