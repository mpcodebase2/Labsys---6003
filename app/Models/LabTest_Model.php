<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;
class LabTest_Model extends Model
{
    protected $common_model;
    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
    }

    // Retrieves all test_list with optional filtering and pagination for DataTables
    public function getAllLabTest($postData = null)
    {
        $builder = $this->db->table('test_list');

        $columns = [
            0 => 'test_list.id',
            1 => 'test_list.name',
            2 => 'test_list.description',
            3 => 'test_list.cost',
            4 => 'test_list.active'
        ];

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $columns[$columnIndex];
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        if (!empty($searchValue)) {
            $builder->like('test_list.id', $searchValue)
                ->orLike('test_list.name', $searchValue)
                ->orLike('test_list.description', $searchValue)
                ->orLike('test_list.cost', $searchValue);
        }

        $totalRecords = $builder->countAllResults(false);

        if (!empty($searchValue)) {
            $builder->like('test_list.id', $searchValue)
                ->orLike('test_list.name', $searchValue)
                ->orLike('test_list.description', $searchValue)
                ->orLike('test_list.cost', $searchValue);
        }

        $totalRecordwithFilter = $builder->countAllResults(false);

        $builder->select('test_list.id, test_list.name, test_list.description,test_list.cost, test_list.active')
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
                $row['cost'],
                $active,
                '<ul class="action">
                    <li class="edit"><a href="javascript:void(0);" data-test_list="'.$row['id'].'" onClick="viewTest('.$row['id'].')"><i class="icon-pencil-alt"></i></a></li>
                    <li class="delete"><a href="javascript:void(0);" data-test_list="'.$row['id'].'" onClick="deleteTest('.$row['id'].')"><i class="icon-trash"></i></a></li>
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
    public function createLabTest($data)
    {
        if (!empty($data)) {
            $test_listTable = [
                'name' => $data['name'],
                'description' => $data['description'],
                'cost' => $data['cost'],
                'active' => $data['active']
            ];

            $this->db->table('test_list')->insert($test_listTable);
            $id = $this->db->insertID();

            return $id ? true : false;
        } else {
            return false;
        }
    }

    // Retrieves all test_list
    public function getDataAll()
    {
        $query = $this->db->table('test_list')->get();

        return $query->getResultArray();
    }

    public function getLabTestsSelection()
    {
        $builder = $this->db->table('test_list');
        $builder->select('test_list.*')
                ->where('test_list.active', true);
        return $builder->get()->getResultArray();
    }


    // Retrieves a role by ID
    public function getDataById($id)
    {
        $query = $this->db->table('test_list')
            ->where('test_list.id', $id)
            ->get();

        return $query->getRowArray();
    }

    // Updates a role record
    public function updateLabTest($formData)
    {
        try {
            $data = [
                'name' => $formData['name'],
                'description' => $formData['description'],
                'cost' => $formData['cost'],
                'active' => $formData['active']
            ];

            // Load Query Builder module
            $builder = $this->db->table('test_list');

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
    public function deleteLabTest($id)
    {

        $this->db->table('test_list')
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
