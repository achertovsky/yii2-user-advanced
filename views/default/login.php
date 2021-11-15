<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use himiklab\yii2\recaptcha\ReCaptcha2;

$this->title = Yii::t('ach-user', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 offset-md-3">
        <h1 class='text-center form-group'><?= Html::encode($this->title) ?></h1>
        <?= $this->render('_social_networks', ['actionName' => 'Login'])?>
        <?php if (isset(Yii::$app->authClientCollection) && count(Yii::$app->authClientCollection) > 0) {?>
            <div class='text-center form-group'><h2><?=Yii::t('ach-user', 'OR')?></h2></div>
        <?php }?>
        <div class='clearfix'></div>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'login-form',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
            ]
        ); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                <?=Yii::t('ach-user', 'If you forgot your password you can').' '.Html::a(Yii::t('ach-user', 'reset it'), ['/user/default/request-password-reset']) ?>.
                <br>
                <?Html::a(Yii::t('ach-user', 'Resend verification email'), ['/user/default/resend-verification-email']) ?>
            </div>

            <?php
            if (Yii::$app->has('reCaptcha')) {
                echo $form->field($model, 'reCaptcha', ['options' => ['class' => 'text-center']])->label(false)->widget(
                    \himiklab\yii2\recaptcha\ReCaptcha2::class,
                    [
                        'widgetOptions' => ['class' => 'inline-block'],
                        'size' => ReCaptcha2::SIZE_COMPACT,
                    ]
                );
            }
            ?>

            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('ach-user', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
