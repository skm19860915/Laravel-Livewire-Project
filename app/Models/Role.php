<?php

namespace App\Models;

use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role'
    ];

    public $createRoleRoles = [
        'role' => 'required'
    ];
    public $editRoleRoles = [
        'role' => 'required'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function storeRole($request)
    {
        try {
            $saved = self::create($request);
            return ['status' => 1, 'msg' => 'Role created successfully.', 'data' => $saved];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }
    public function updateRole(self $self, $request)
    {
        try {
            $self->update($request);
            return ['status' => 1, 'msg' => 'Role updated successfully.', 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }
}
