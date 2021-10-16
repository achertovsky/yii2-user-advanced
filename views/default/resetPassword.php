<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <h1 class='text-center form-group'><?= Html::encode($this->title) ?></h1>

        <p>Please choose your new password:</p>

        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'repeatPassword')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>
        
    </div>
</div>
