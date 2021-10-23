<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use himiklab\yii2\recaptcha\ReCaptcha2;

$this->title = Yii::t('app', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-8 col-sm-offset-2 col-md-6 offset-md-3">
        <h1 class='text-center form-group'><?= Html::encode($this->title) ?></h1>
        <?= $this->render('_social_networks', ['actionName' => 'Sign up'])?>
        <?php if (isset(Yii::$app->authClientCollection) && count(Yii::$app->authClientCollection) > 0) {?>
            <div class='text-center form-group'><h2><?=Yii::t('app', 'OR')?></h2></div>
        <?php }?>
        <div class='clearfix'></div>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'form-signup',
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
            ]
        ); ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'repeatPassword')->passwordInput() ?>

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
                <?= Html::submitButton(Yii::t('app', 'Sign up'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>