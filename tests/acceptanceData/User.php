<?php

namespace app\tests\acceptanceData;

/*
 * User class is used to store data related to the user table to use in the 
 * acceptance tests
 */
class User
{
    
    /*
     *The method is used to get the data related with the User table
     * @return array 
     */
    public function getData()
    {
        return [
            'admin' => [
                'email' => 'admin@mail.com',
                'password' => '123',
                'type' => 'admin',
                'auth_key' => '1JvxT2EeBBXXmJCSPFjM9PPG9qfurAPf',
                'access_token' => 'FKnhcYFMFXGTtNSFVdqXnuNgsIgMWAaz'
            ],
            'regular' => [
                'email' => 'regular@mail.com',
                'password' => '123',
                'type' => 'regular',
                'auth_key' => 'cXGp1ceLwHyOQRbfPQXEskiooJuUODeO',
            ]
        ];
    }

}
