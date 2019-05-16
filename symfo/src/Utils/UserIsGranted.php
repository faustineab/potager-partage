<?php

namespace App\Utils;

use App\Entity\Garden;
use App\Entity\User;

class UserIsGranted 
{
    public function UserIsGranted(Garden $garden) 
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        
        $gardenMembers = $garden->getUsers();
        $userRoles = $currentUser->getRoles();
        foreach ($userRoles as $key => $value) {
            if ($value == 'ROLE_ADMIN') {
                $admin = $currentUser;
            }
        }
        
        foreach ($gardenMembers as $gardenMember) {
            
            if ($currentUser == $gardenMember || $admin)
            {
                return true;
            }

            return false;
        }
    }
}