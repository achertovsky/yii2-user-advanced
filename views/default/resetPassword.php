<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('ach-user', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 offset-md-3">
        <h1 class='text-center form-group'><?= Html::encode($this->title) ?></h1>

        <p><?=Yii::t('ach-user', 'Please choose your new password').':'?></p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'repeatPassword')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('ach-user', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
        
    </div>
</div>
