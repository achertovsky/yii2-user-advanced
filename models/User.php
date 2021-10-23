<?php

namespace achertovsky\user\models;

use Yii;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use common\models\User as ModelsUser;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ModelsUser implements IdentityInterface
{
    /**
     * Timestamp behavior creates issue for GC
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['created_at', 'updated_at'], 'default', 'value' => time()],
                [['password_hash', 'email', 'status', 'created_at', 'updated_at'], 'required'],
                ['email', 'email'],
                ['username', 'string'],
                ['username', 'default', 'value' => function () {
                    return $this->email;
                }],
            ]
        );
    }

    /**
     * Timestamp behavior creates issue for GC
     * Will update updated_at explicitly
     *
     * @return void
     */
    public function afterValidate()
    {
        parent::afterValidate();
        $this->updated_at = time();
    }
}
