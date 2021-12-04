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
    public $replaceDefaultRoutes = true;

    /**
     * Ability to translate by yourself
     *
     * @var boolean
     */
    public $enablei18n = true;

    /**
     * @param int $cost Cost parameter used by the Blowfish hash algorithm.
     * The higher the value of cost,
     * the longer it takes to generate the hash and to verify a password against it. Higher cost
     * therefore slows down a brute-force attack. For best protection against brute-force attacks,
     * set it to the highest value that is tolerable on production servers. The time taken to
     * compute the hash doubles for every increment by one of $cost.
     */
    public $cost = 6;
}
