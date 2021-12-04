<?php

namespace achertovsky\user;

use achertovsky\user\models\User;
use Yii;
use Exception;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@ach-user', '@vendor/achertovsky/yii2-user-advanced');
        
        if (Yii::$app->getModule('user')->enablei18n) {
            /**
             * i18n part
             */
            Yii::$app->i18n->translations['ach-user'] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@ach-user/i18n',
                'fileMap' => [
                    'ach-user' => 'ach-user.php',
                ]
            ];
        }

        /**
         * Adds migration path of current module to map
         */
        if (php_sapi_name() == 'cli') {
            $map = Yii::$app->controllerMap;
            if (isset($map['migrate'])) {
                $map['migrate']['migrationPath'][] = '@ach-user/migrations';
            } else {
                $map['migrate'] = [
                    'class' => 'yii\console\controllers\MigrateController',
                    'migrationPath' => [
                        '@app/migrations', //default
                        '@ach-user/migrations',
                    ],
                ];
            }
            Yii::$app->controllerMap = $map;
        }

        /**
         * routing
         */
        if (php_sapi_name() != 'cli') {
            $urlManager = Yii::$app->urlManager;
            $urlManager->addRules(
                [
                    'login' => 'user/default/login',
                    'signup' => 'user/default/signup',
                    'logout' => 'user/default/logout',
                    'verify-email/<token:.+>' => 'user/default/verify-email',
                    'reset-password/<token:.+>' => 'user/default/reset-password',
                    'request-password-reset' => 'user/default/request-password-reset',
                    'resend-verification-email' => 'user/default/resend-verification-email',
                ]
            );
            if (Yii::$app->getModule('user')->replaceDefaultRoutes) {
                $urlManager->addRules(
                    [
                        'site/login' => 'user/default/login',
                        'site/signup' => 'user/default/signup',
                        'site/logout' => 'user/default/logout',
                    ]
                );
            }
        }

        /**
         * Identity class config
         */
        if (php_sapi_name() != 'cli') {
            Yii::$app->user->identityClass = User::class;
        }
    }
}
