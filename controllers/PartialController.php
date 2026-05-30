<?php

class PartialController
{

    public static function getHeaderData()
    {

        $isLoggedIn = isset($_SESSION['userauth']) && $_SESSION['userauth'] === true;
        
        $roleId = null;
        $firstName = null;
        
        if ($isLoggedIn && isset($_SESSION['userID'])) {

            $user = OneFetch('SELECT role_id, firstName FROM users WHERE id = ?', [$_SESSION['userID']]);
            if ($user) {
                $roleId = $user['role_id'];
                $firstName = $user['firstName'];
            }
        }

        if ($isLoggedIn) {
            $loginText = 'Личный кабинет';
            $loginHref = '/vetclinic/user/account';
            $logoutHref = '/vetclinic/auth/logout';
        } else {
            $loginText = 'Войти';
            $loginHref = '/vetclinic/auth/login';
            $logoutHref = null;
        }
        
        return [
            'isLoggedIn' => $isLoggedIn,
            'role_id' => $roleId,
            'firstName' => $firstName,
            'loginText' => $loginText,
            'loginHref' => $loginHref,
            'logoutHref' => $logoutHref,
            'userLoggedInJs' => $isLoggedIn ? 'true' : 'false'
        ];
    }
    
    public static function getFooterData()
    {
        return [
            'year' => date('Y'),
            'clinic_name' => 'VetClinic',
            'phone' => '+7 (999) 123-45-67',
            'email' => 'info@vetclinic.ru',
            'address' => 'г. Москва, ул. Ветеринарная, 15'
        ];
    }
}