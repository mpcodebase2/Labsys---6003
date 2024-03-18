<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;

class Permissions_Model extends Model
{
    protected $common_model;
    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
    }

    // Retrieves all permissions with optional filtering and pagination for DataTables
    public function getAllPermission($postData = null)
    {
        $builder = $this->db->table('permissions');

        $columns = [
            0 => 'permissions.id',
            1 => 'permissions.permission_group_name',
            2 => 'permissions.name',
            3 => 'permissions.description',
            4 => 'permissions.active'
        ];

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $columns[$columnIndex];
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        if (!empty($searchValue)) {
            $builder->like('permissions.id', $searchValue)
                ->orLike('permissions.name', $searchValue)
                ->orLike('permissions.description', $searchValue);
        }

        $totalRecords = $builder->countAllResults(false);

        if (!empty($searchValue)) {
            $builder->like('permissions.id', $searchValue)
                ->orLike('permissions.name', $searchValue)
                ->orLike('permissions.description', $searchValue);
        }

        $totalRecordwithFilter = $builder->countAllResults(false);

        $builder->select('permissions.id, permissions.permission_group_name, permissions.name, permissions.description, permissions.active')
            ->orderBy($columnName, $columnSortOrder)
            ->limit($rowperpage, $start);

        $records = $builder->get()->getResultArray();

        $data = [];
        foreach ($records as $row) {
            $active = $row['active'] ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Deactivate</span>';

            $nestedData = [
                $row['id'],
                $row['permission_group_name'],
                $row['name'],
                $row['description'],
                $active,
                '<ul class="action">
                    <li class="edit"><a href="javascript:void(0);" data-permissions="'.$row['id'].'" onClick="viewPermissions('.$row['id'].')"><i class="icon-pencil-alt"></i></a></li>
                    <li class="delete"><a href="javascript:void(0);" data-permissions="'.$row['id'].'" onClick="deletePermissions('.$row['id'].')"><i class="icon-trash"></i></a></li>
                </ul>'
            ];

            $data[] = $nestedData;
        }

        $json_data = [
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalRecordwithFilter),
            "data"            => $data
        ];

        return $json_data;
    }

    // Creates a new permission record
    public function createPermission($data)
    {
        if (!empty($data)) {
            $permissionsTable = [
                'permission_group_name' => $data['permission_group_name'],
                'name' => $data['name'],
                'description' => $data['description'],
                'active' => $data['active']
            ];

            $this->db->table('permissions')->insert($permissionsTable);
            $id = $this->db->insertID();

            return $id ? true : false;
        } else {
            return false;
        }
    }

    // Retrieves all permissions
    public function getDataAll()
    {
        $query = $this->db->table('permissions')->get();

        return $query->getResultArray();
    }

    // Retrieves a permission by ID
    public function getDataById($id)
    {
        $query = $this->db->table('permissions')
            ->where('permissions.id', $id)
            ->get();

        return $query->getRowArray();
    }

    // Updates a permission record
    public function updatePermission($formData)
    {
        try {
            $data = [
                'permission_group_name' => $formData['permission_group_name'],
                'description' => $formData['description'],
                'active' => $formData['active']
            ];

            // Load Query Builder module
            $builder = $this->db->table('permissions');

            // Update the user record
            $this->db->transBegin();
            $builder->where('id', $formData['id']);
            $builder->update($data);
            $this->db->transCommit();

            $affectedRows = $this->db->affectedRows();
            if($this->db->transStatus() === FALSE){
                return false;
            }else {
                if ($affectedRows > 0) {
                    return true;
                } else {
                    return true;
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Deletes a permission record by ID
    public function deletePermission($id)
    {
        $this->db->table('role_permissions')
            ->where('permission_id', $id)
            ->delete();

        $this->db->table('permissions')
            ->where('id', $id)
            ->delete();

        $affectedRows = $this->db->affectedRows();
        if($this->db->transStatus() === FALSE){
            return false;
        }else {
            if ($affectedRows > 0) {
                return true;
            } else {
                return true;
            }
        }
    }
}
