<?php

namespace app\components;

use yii\base\Component;

class AuthorizationConstants extends Component
{

    const ADMIN_ROLE = 'admin';
    const ADMIN_PERMISSION = 'adminPermission';
    const REGULAR_USER_ROLE = 'regular';
    const REGULAR_USER_PERMISSION = 'regularUserPermission';

}
