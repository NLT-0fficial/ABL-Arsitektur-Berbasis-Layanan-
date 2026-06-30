<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CheckInLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckInLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CheckInLog');
    }

    public function view(AuthUser $authUser, CheckInLog $checkInLog): bool
    {
        return $authUser->can('View:CheckInLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CheckInLog');
    }

    public function update(AuthUser $authUser, CheckInLog $checkInLog): bool
    {
        return $authUser->can('Update:CheckInLog');
    }

    public function delete(AuthUser $authUser, CheckInLog $checkInLog): bool
    {
        return $authUser->can('Delete:CheckInLog');
    }

}