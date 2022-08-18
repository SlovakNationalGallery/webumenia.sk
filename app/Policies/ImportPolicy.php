<?php

namespace App\Policies;

use App\Import;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role === 'importer';
    }

    public function viewAll()
    {
        return null;
    }

    public function view(User $user, Import $import)
    {
        return $user->id === $import->user_id && $user->role === 'importer';
    }

    public function create()
    {
        return null;
    }

    public function update(User $user, Import $import)
    {
        return $user->id === $import->user_id && $user->role === 'importer';
    }

    public function updateFile(User $user, Import $import)
    {
        return $user->id === $import->user_id && $user->role === 'importer';
    }

    public function updateMetadata()
    {
        return null;
    }

    public function delete()
    {
        return null;
    }

    public function launch(User $user, Import $import)
    {
        return $user->id === $import->user_id && $user->role === 'importer';
    }
}
