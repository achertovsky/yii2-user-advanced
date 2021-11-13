<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/reset-password', 'token' => $token]);
?>
<div class="password-reset">
    <p><?= Yii::t("ach-user", "Hello")?>,</p>

    <p><?=Yii::t("ach-user", "Follow the link below to reset your password")?>:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
