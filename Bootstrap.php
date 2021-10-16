<?php

namespace achertovsky\user;

use Yii;
use Exception;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class Bootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@ach-user', '@vendor/achertovsky/yii2-user');
        /**
         * @todo
         * add here i18n
         */

        /**
         * Adds migration path of current module to map
         */
        if (php_sapi_name() == 'cli') {
            $map = Yii::$app->controllerMap;
            $map = [
                'migrate' => [
                    'class' => 'yii\console\controllers\MigrateController',
                    'migrationPath' => [
                        '@app/migrations', //default
                        '@ach-user/migrations',
                    ],
                ],
            ];
            Yii::$app->controllerMap = $map;
        }

        if (php_sapi_name() != 'cli' && Yii::$app->getModule('user')->configureUrlManagerRules) {
            $urlManager = Yii::$app->urlManager;
            $urlManager->addRules(
                [
                    'signup' => 'user/default/signup',
                    'login' => 'user/default/login',
                    'logout' => 'user/default/logout',
                    'verify-email/<token:.+>' => 'user/default/verify-email',
                    'reset-password/<token:.+>' => 'user/default/reset-password',
                    'request-password-reset' => 'user/default/request-password-reset',
                ]
            );
        }
    }
}
