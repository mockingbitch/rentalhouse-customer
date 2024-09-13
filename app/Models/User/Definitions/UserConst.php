<?php

namespace App\Models\User\Definitions;

/**
 * UserConst
 *
 * @package App\Models\User\Definitions
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author Phong <jarvis.phongtran@gmail.com>
 */
interface UserConst
{
    public const ROLE_ROOT_CODE     = -1;
    public const ROLE_ADMIN_CODE    = 0;
    public const ROLE_MANAGER_CODE  = 2;
    public const ROLE_LESSOR_CODE   = 4;
    public const ROLE_LESSEE_CODE   = 10;

    public const ROLE_ROOT      = 'root';
    public const ROLE_ADMIN     = 'admin';
    public const ROLE_MANAGER   = 'manager';
    public const ROLE_LESSOR    = 'lessor';
    public const ROLE_LESSEE    = 'lessee';

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE   = 1;


}
