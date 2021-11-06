<?php

use yii\helpers\Url;
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use rmrevin\yii\fontawesome\cdn\AssetBundle;

AssetBundle::register($this);

$exist = isset(Yii::$app->authClientCollection);
if ($exist) {
    $clientsCount = count(Yii::$app->authClientCollection->clients);
    $pushLength = 6-$clientsCount;
    foreach (Yii::$app->authClientCollection->clients as $name => $client) {
?>
        <div class="form-group">
            <?= Html::a(
                FA::icon($name).Yii::t("app", "Via ").$name,
                Url::toRoute(
                    [
                        '/user/default/auth',
                        'authclient' => $client->getId()
                    ]
                ),
                [
                    'class' => 'btn btn-block btn-default text-center',
                ]
            );?>
        </div>
        <div class='clearfix'></div>
<?php
    }
}
?>