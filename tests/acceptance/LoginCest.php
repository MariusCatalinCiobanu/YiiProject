<?php

use yii\helpers\Url as Url;

class LoginCest
{

    public function ensureThatLoginWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/login/login/index'));
        $I->wait(1);
        $I->fillField('#loginform-email', 'admin@mail.com');
        $I->fillField('#loginform-password', '123');
        $I->click('login-button');
        $I->wait(1);
        $I->see('Admin Panel', 'h1');
    }

}
