<?php

namespace achertovsky\user;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * Current email will be used as sender in any email of this module
     *
     * @var string
     */
    public $senderEmail = '';

    /**
     * Will define pretty urls for current module routes
     * Disable if you want another ones
     *
     * @var boolean
     */
    public $configureUrlManagerRules = true;
}
