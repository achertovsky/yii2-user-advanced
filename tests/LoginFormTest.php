<?php

namespace achertovsky\user\tests;

use Yii;
use achertovsky\user\models\User;
use achertovsky\user\tests\TestCase;
use achertovsky\user\models\LoginForm;

class LoginFormTest extends TestCase
{
    /**
     * @var LoginForm
     */
    protected $loginFormStub = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();
        $this->loginFormStub = $this->getMockBuilder(LoginForm::class)
            ->setMethods(['getUser'])
            ->getMock();
    }

    public function testValidate()
    {
        $userFixtures = require_once(Yii::getAlias('@unit/user/data/UserFixture.php'));
        $userModel = new User($userFixtures['user1']);
        $this->loginFormStub->method('getUser')->will($this->returnValue($userModel));
        $this->loginFormStub->setAttributes(
            [
                'username' => $userFixtures['user1']['username'],
                'password' => $userFixtures['user1']['password'],
            ]
        );
        $this->assertTrue($this->loginFormStub->validate(), 'Password had to be correct');
        $this->loginFormStub->setAttributes(
            [
                'username' => $userFixtures['user1']['username'],
                'password' => 'wrongpassword',
            ]
        );
        $this->assertFalse($this->loginFormStub->validate(), 'Password had to be wrong');
        $this->loginFormStub->setAttributes(
            [
                'username' => 'wrongusername',
                'password' => 'wrongpassword',
            ]
        );
        $this->assertFalse($this->loginFormStub->validate(), 'Password and username had to be wrong');
        $this->loginFormStub->setAttributes(
            [
                'username' => 'wrongusername',
                'password' => $userFixtures['user1']['password'],
            ]
        );
        $this->assertFalse($this->loginFormStub->validate(), 'Username had to be wrong');
    }
}