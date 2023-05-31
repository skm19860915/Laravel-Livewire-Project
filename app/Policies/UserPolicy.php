<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    protected $roles = [
      '1' => 'Super Admin',
      '2' => 'Admin',
      '3' => 'Manager',
      '4' => 'Sales/Front Desk',
      '5' => 'Call Center',
      '6' => 'Marketing ONLY',
      '7' => 'Traveling Salesman',
      '8' => 'Traveling Manager'
    ];

    public function manageLocations(User $user)
    {
      return $user->role->id == User::ROLE_SUPER_ADMIN; //Only super admin can manage locations
    }

    public function export(User $user)
    {
        return in_array($user->role_id,[User::ROLE_SUPER_ADMIN,User::ROLE_ADMIN,User::ROLE_MANAGER]) ;
    }

    public function superadmin(User $user)
    {
      return $user->role->id == User::ROLE_SUPER_ADMIN; 
    }

    public function manageUsers(User $user)
    {
        return $user->role->id == User::ROLE_SUPER_ADMIN || $user->role->id == User::ROLE_ADMIN; //Only superadmin and admin can manage users
        //return $user->role->id == User::ROLE_SUPER_ADMIN; //Only superadmin can manage users
    }

    public function manageRoles(User $user)
    {
        return $user->role->id == User::ROLE_SUPER_ADMIN;
    }

    public function manageScheduleTypes(User $user)
    {
        return $user->role->id == User::ROLE_SUPER_ADMIN;
    }

    public function managePricing(User $user)
    {
      return $user->role->id == User::ROLE_SUPER_ADMIN || $user->role->id == User::ROLE_ADMIN; //Only superadmin and admin can manage locations
    }

    public function superadminORadminORmanager(User $user)
    {
        return $user->role->id == User::ROLE_SUPER_ADMIN || $user->role->id == User::ROLE_ADMIN || $user->role->id == User::ROLE_MANAGER;
    }

    public function superadminORadminORmanagerORmarketingOnly(User $user)
    {
        return $user->role->id == User::ROLE_SUPER_ADMIN || $user->role->id == User::ROLE_ADMIN || $user->role->id == User::ROLE_MANAGER || $user->role->id == User::ROLE_MARKETING_ONLY;

    }

    public function patinetCheckIn(User $user)
    {
        // if is superadmin 1 or admin 2  or manager 3 or Sales/Front Desk 4
        return in_array($user->role_id,[User::ROLE_SUPER_ADMIN,User::ROLE_ADMIN,User::ROLE_MANAGER,User::ROLE_SALES_FRONT_DESK]) ;
    }

    public function converBackToAppt(User $user)
    {
        // if is superadmin 1 or admin 2  or manager 3 or Sales/Front Desk 4
        return in_array($user->role_id,[User::ROLE_SUPER_ADMIN,User::ROLE_ADMIN,User::ROLE_MANAGER,User::ROLE_SALES_FRONT_DESK]) ;
    }

    public function marketingOnly(User $user)
    {
        return $user->role->id == User::ROLE_MARKETING_ONLY;
    }

    public function allExceptMarketingOnly(User $user)
    {
        return $user->role->id !== User::ROLE_MARKETING_ONLY;
    }

    public function manageZingleIntegration(User $user)
    {
        return $user->role->id == User::ROLE_SUPER_ADMIN || $user->role->id == User::ROLE_ADMIN;
    }
}
