<?php

namespace achertovsky\user\models;

use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidArgumentException;
use frontend\models\VerifyEmailForm as ModelsVerifyEmailForm;

class VerifyEmailForm extends ModelsVerifyEmailForm
{
    /**
     * added translaction
     * 
     * {@inheritDoc}
     */
    public function __construct($token, array $config = [])
    {
        try {
            parent::__construct($token, $config);
        } catch (InvalidArgumentException $ex) {
            if ($ex->getMessage() == 'Verify email token cannot be blank.') {
                throw new InvalidCallException(Yii::t('app', 'Verify email token cannot be blank.'));
            }
            if ($ex->getMessage() == 'Wrong verify email token.') {
                throw new InvalidCallException(Yii::t('app', 'Wrong verify email token.'));
            }
        }
    }
}
