<?php

namespace achertovsky\user\models;

use Yii;
use yii\base\Model;
use achertovsky\user\models\User;

class UserEmailInteractionForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var integer
     */
    public $status = User::STATUS_ACTIVE;

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('ach-user', 'Your email address'),
            'status' => Yii::t('ach-user', 'Status'),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => $this->status],
                'message' => '', // empty message to not let someone know if email exist in system or not
            ],
        ];
    }
}