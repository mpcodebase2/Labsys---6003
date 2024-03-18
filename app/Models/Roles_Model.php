<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;
class Roles_Model extends Model
{
    protected $common_model;
    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
    }

    // Retrieves all roles with optional filtering and pagination for DataTables
    public function getAllRoles($postData = null)
    {
        $builder = $this->db->table('roles');

        $columns = [
            0 => 'roles.id',
            1 => 'roles.name',
            2 => 'roles.description',
            3 => 'roles.active'
        ];

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $columns[$columnIndex];
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        if (!empty($searchValue)) {
            $builder->like('roles.id', $searchValue)
                ->orLike('roles.name', $searchValue)
                ->orLike('roles.description', $searchValue);
        }

        $totalRecords = $builder->countAllResults(false);

        if (!empty($searchValue)) {
            $builder->like('roles.id', $searchValue)
                ->orLike('roles.name', $searchValue)
                ->orLike('roles.description', $searchValue);
        }

        $totalRecordwithFilter = $builder->countAllResults(false);

        $builder->select('roles.id, roles.name, roles.description, roles.active')
            ->orderBy($columnName, $columnSortOrder)
            ->limit($rowperpage, $start);

        $records = $builder->get()->getResultArray();

        $data = [];
        foreach ($records as $row) {
            $active = $row['active'] ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Deactivate</span>';

            $nestedData = [
                $row['id'],
                $row['name'],
                $row['description'],
                $active,
                '<ul class="action">
                    <li class="edit"><a href="javascript:void(0);" data-roles="'.$row['id'].'" onClick="viewRoles('.$row['id'].')"><i class="icon-pencil-alt"></i></a></li>
                    <li class="delete"><a href="javascript:void(0);" data-roles="'.$row['id'].'" onClick="deleteRoles('.$row['id'].')"><i class="icon-trash"></i></a></li>
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

    // Creates a new role record
    public function createRoles($data)
    {
        if (!empty($data)) {
            $rolesTable = [
                'name' => $data['name'],
                'description' => $data['description'],
                'active' => $data['active']
            ];

            $this->db->table('roles')->insert($rolesTable);
            $id = $this->db->insertID();

            return $id ? true : false;
        } else {
            return false;
        }
    }

    // Retrieves all roles
    public function getDataAll()
    {
        $query = $this->db->table('roles')->get();

        return $query->getResultArray();
    }

    // Retrieves a role by ID
    public function getDataById($id)
    {
        $query = $this->db->table('roles')
            ->where('roles.id', $id)
            ->get();

        return $query->getRowArray();
    }

    // Updates a role record
    public function updateRoles($formData)
    {
        try {
            $data = [
                'description' => $formData['description'],
                'active' => $formData['active']
            ];

            // Load Query Builder module
            $builder = $this->db->table('roles');

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
        } catch (\Exception $e) {
            return false;
        }
    }

    // Deletes a role record by ID
    public function deleteRoles($id)
    {
        $this->db->table('user_roles')
            ->where('role_id', $id)
            ->delete();

        $this->db->table('roles')
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
