<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Model\Common_Model;
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

        

        $data = [];
       
        }

        $json_data = [
            "draw"            => intval($draw),
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalRecordwithFilter),
            "data"            => $data
        ];

        return $json_data;
    }
}