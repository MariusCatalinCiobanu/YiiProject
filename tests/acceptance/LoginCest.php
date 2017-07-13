<?php

use yii\helpers\Url as Url;

//use yii\codeception\DbTestCase;

require_once(Yii::getAlias('@app') . '\tests\fixtures\data\user.php');

use app\tests\acceptanceData;

//The class is used to test actions in the Login controller
class LoginCest
{

    //checks the logout action
    public function ensureLogoutWorks(AcceptanceTester $I)
    {
        $users = (new \app\tests\acceptanceData\User())->getData();
        $admin = $users['admin'];
        $I->amOnPage(URL::toRoute('/login/login/index'));
        $I->wait(2);
        $I->fillField('#loginform-email', $admin['email']);
        $I->fillField('#loginform-password', $admin['password']);
        $I->click('login-button');
        $I->wait(1);
        $I->click('.logout');
        $I->wait(1);
        $I->see('Login', 'h1');
    }

    //checks the index(login) action
    public function ensureThatLoginWorks(AcceptanceTester $I)
    {
        $users = (new \app\tests\acceptanceData\User())->getData();
        $admin = $users['admin'];

        $I->amOnPage(Url::toRoute('/login/login/index'));
        $I->wait(1);

        //checks a admin user login
        $I->fillField('#loginform-email', $admin['email']);
        $I->fillField('#loginform-password', $admin['password']);
        $I->click('login-button');
        $I->wait(1);
        $I->see('Admin Panel', 'h1');
        $I->wait(1);

        $I->click('.logout');
//        $I->haveFixtures(['users' => UserFixture::className()]);
//        $I->grabFixture('users');
        //checks a regular user login

        $regular = $users['regular'];
        $I->fillField('#loginform-email', $regular['email']);
        $I->fillField('#loginform-password', $regular['password']);
        $I->click('login-button');
        $I->wait(1);
        $I->see('Trips', 'h1');
    }

}
