<?php

namespace achertovsky\user;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@ach-user/assets';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
