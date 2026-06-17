<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KostLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class KostLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KostLog');
    }

    public function view(AuthUser $authUser, KostLog $kostLog): bool
    {
        return $authUser->can('View:KostLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KostLog');
    }

    public function update(AuthUser $authUser, KostLog $kostLog): bool
    {
        return $authUser->can('Update:KostLog');
    }

    public function delete(AuthUser $authUser, KostLog $kostLog): bool
    {
        return $authUser->can('Delete:KostLog');
    }

}