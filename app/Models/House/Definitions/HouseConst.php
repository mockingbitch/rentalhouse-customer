<?php

namespace App\Models\House\Definitions;

/**
 * HouseConst
 *
 * @package App\Models\House\Definitions
 * @copyright Copyright (c) 2024, jarvis.phongtran
 * @author Phong <jarvis.phongtran@gmail.com>
 */
interface HouseConst
{
    public const FILE_PATH = 'public/houses';

    public const STATUS_INACTIVE_CODE = 0;
    public const STATUS_DRAFT_CODE    = 1;
    public const STATUS_PENDING_CODE  = 2;
    public const STATUS_APPROVED_CODE = 3;

    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_DRAFT    = 'draft';
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
}
