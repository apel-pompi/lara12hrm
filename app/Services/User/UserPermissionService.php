<?php

namespace App\Services\User;

use App\Filters\User\UserPermissionFillter;
use App\Models\User;

class UserPermissionService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = User::with('roles')->orderBy('id', 'desc');

        $users = resolve(UserPermissionFillter::class)->getResults([

            'builder' => $queryBuilder,

            'params' => $queryParams

        ]);

        $users->getCollection()->transform(function ($user) {
            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'username'  => $user->username,
                'email'     => $user->email,
                'banned_at' => $user->banned_at,
                'roles'     => $user->roles->map(fn ($role) => [
                    'name' => $role->name
                ]),
            ];
        });
        return $users;
    }
}
