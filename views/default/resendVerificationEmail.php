<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('ach-user', 'Resend verification email');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 offset-md-3">
        <h1 class='text-center form-group'><?= Html::encode($this->title) ?></h1>

        <p><?=Yii::t('ach-user', 'Please fill out your email. A verification email will be sent there').'.';?></p>
        <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

        <div class="form-group text-center">
            <?= Html::submitButton(Yii::t('ach-user', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
