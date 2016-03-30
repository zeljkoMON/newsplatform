<?php
// src/AppBundle/Utils/CopyUser.php

namespace AppBundle\Utils;

use AppBundle\Entity\PendingUser;
use AppBundle\Entity\Users;

class CopyUser
{
    public function __construct(Users $user, PendingUser $pendingUser)
    {

        return $user;
    }
}