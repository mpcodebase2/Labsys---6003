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
}