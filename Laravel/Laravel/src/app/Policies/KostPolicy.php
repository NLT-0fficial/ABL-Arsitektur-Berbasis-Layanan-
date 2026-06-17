<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kost;
use Illuminate\Auth\Access\HandlesAuthorization;

class KostPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Kost');
    }

    public function view(AuthUser $authUser, Kost $kost): bool
    {
        return $authUser->can('View:Kost');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Kost');
    }

    public function update(AuthUser $authUser, Kost $kost): bool
    {
        return $authUser->can('Update:Kost');
    }

    public function delete(AuthUser $authUser, Kost $kost): bool
    {
        return $authUser->can('Delete:Kost');
    }

}