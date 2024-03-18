<?php
namespace App\Models;

use CodeIgniter\Model;
class RolePermission_Model extends Model
{
    protected $status;
    protected $roles;
    protected $common_model;

    public function __construct()
    {
        parent::__construct();

        // Load configuration file for roles
        $this->status = array('pending', 'active');
        $this->roles = array(4);
        $this->common_model = model(Common_Model::class);
    }

    // Retrieves all roles for the current user based on their role ID
    public function getAllRolesByCurrentUserId()
    {
        $roleId = (session('role_id'))?session('role_id'):1;
        return $this->db->table('roles')
            ->select('id, name')
            ->where('id >=', $roleId)
            ->get()
            ->getResultArray();
    }

    // Checks if user roles exist for a given user ID
    public function userRolesExist($userId)
    {
        return $this->db->table('user_roles')
            ->where('user_id', $userId)
            ->get()
            ->getRow();
    }

    // Creates user roles for a given user ID and role ID
    public function createUserRoles($userId, $roleId)
    {
        if ($userId) {
            if (!$this->userRolesExist($userId)) {
                $this->db->table('user_roles')
                    ->insert(['user_id' => $userId, 'role_id' => $roleId]);
                return $this->db->affectedRows() > 0;
            } else {
                return $this->updateUserRole($userId, $roleId);
            }
        } else {
            return false;
        }
    }


    // Updates user role for a given user ID and role ID
    public function updateUserRole($userId, $roleId)
    {
        $this->db->table('user_roles')
            ->where('user_id', $userId)
            ->update(['role_id' => $roleId]);
        return $this->db->affectedRows() > 0;
    }

    // Checks if a user has a specific permission
    public function userHasPermission($permissionName)
    {
        $userId = session('user_id');
        $roleName = session('role');

        try {
            $role = $this->db->table('roles')
                ->where('name', $roleName)
                ->get()
                ->getRow();
            if (!$role) {
                throw new \Exception("Role '{$roleName}' not found.");
            }

            $userRoles = $this->db->table('user_roles')
                ->select('role_id')
                ->where('user_id', $userId)
                ->get()
                ->getResultArray();
            $userRoleIds = array_column($userRoles, 'role_id');

            $permission = $this->db->table('permissions')
                ->where('name', $permissionName)
                ->get()
                ->getRow();
            if (!$permission) {
                throw new \Exception("Permission not found.");
            }

            $rolesWithPermission = $this->db->table('role_permissions')
                ->whereIn('role_id', $userRoleIds)
                ->where('permission_id', $permission->id)
                ->get()
                ->getResultArray();

            return [
                'hasPermission' => count($rolesWithPermission) > 0,
                'error' => ''
            ];
        } catch (\Exception $e) {
            return [
                'hasPermission' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    // Retrieves permissions associated with a role
    public function getRolePermissions($roleId)
    {
        $permissions = [];
        $query = $this->db->table('role_permissions')
            ->select('permission_id')
            ->where('role_id', $roleId)
            ->get();
        foreach ($query->getResult() as $row) {
            $permissions[] = ['permission_id' => $row->permission_id];
        }
        return $permissions;
    }

    // Deletes role permissions for a given role ID
    public function deleteRolePermissions($roleId)
    {
        if ($roleId) {
            $this->db->table('role_permissions')
                ->where('role_id', $roleId)
                ->delete();
        }
    }

    // Assigns permissions to a role
    public function assignRolePermissions($permissionIds, $roleId)
    {
        $data = [];
        foreach ($permissionIds as $permissionId) {
            if ($permissionId && $roleId) {
                $data[] = ['role_id' => $roleId, 'permission_id' => $permissionId];
            }
        }

        if (!empty($data)) {
            $this->db->table('role_permissions')
                ->insertBatch($data);
            return $this->db->affectedRows() > 0;
        } else {
            return false;
        }
    }
}
