<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Builder;
use App\Libraries\Password;

class UserModel extends Model
{
    protected $commonModel;
    protected $table = 'users';
    protected $allowedFields = ['username', 'password', 'nic', 'first_name', 'last_name', 'gender', 'phone', 'email', 'dob', 'join_date', 'address', 'district', 'active', 'created_by', 'last_login'];

    function __construct()
    {
        parent::__construct();
        $this->commonModel = new Common_Model();
    }

    public function checkLogin($data){
        $passwordLib = new Password(); // Load the Password library manually
        $userInfo = $this->db->table('users')
            ->select('users.*, roles.id as role_id, roles.name as role_name')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('users.username', $data['username'])
            ->where('users.active', true)
            ->get()
            ->getRow();

        if (!empty($userInfo)) {
            if (!$passwordLib->validate_password($data['password'], $userInfo->password)) {
                return false;
            }
        } else {
            return false;
        }

        $this->updateLoginTime($userInfo->id);

        unset($userInfo->password);
        return $userInfo;
    }

    public function checkLoginByEmail($data){
        $passwordLib = new Password(); // Load the Password library manually
        $userInfo = $this->db->table('users')
            ->select('users.*, roles.id as role_id, roles.name as role_name')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->where('users.email', $data['email'])
            ->where('users.active', true)
            ->get()
            ->getRow();

        if (!empty($userInfo)) {
            if (!$passwordLib->validate_password($data['password'], $userInfo->password)) {
                return false;
            }
        } else {
            return false;
        }

        $this->updateLoginTime($userInfo->id);

        unset($userInfo->password);
        return $userInfo;
    }

    public function updateLoginTime($userId){
        $timeNow = $this->commonModel->currentColomboTime();
        $this->db->table('users')->where('id', $userId)->update(['last_login' => $timeNow]);
    }

    public function userExist($username){
        return $this->db->table('users')->where('username', $username)->get()->getRow();
    }

    public function userExistByEmail($email){
        return $this->db->table('users')->where('email', $email)->get()->getRow();
    }

    public function checkLoginAttempt($userId){
        return $this->db->table('login_attempts')->where('user_id', $userId)->get()->getRow();
    }

    public function createLoginAttempts($userId){
        $timeNow = $this->commonModel->currentColomboTime();
        $data = [
            'user_id' => $userId,
            'attempt_count' => 1,
            'last_attempt' => $timeNow
        ];
        $this->db->table('login_attempts')->insert($data);
    }

    public function updateLoginAttempts($userId, $attemptCountAlready){
        $timeNow = $this->commonModel->currentColomboTime();
        $data = [
            'attempt_count' => $attemptCountAlready + 1,
            'last_attempt' => $timeNow
        ];
        $this->db->table('login_attempts')->where('user_id', $userId)->update($data);
    }

    public function resetLoginAttempts($userId) {
        $timeNow = $this->commonModel->currentColomboTime();
        $data = [
            'attempt_count' => 0,
            'last_attempt' => $timeNow
        ];
        $this->db->table('login_attempts')->where('user_id', $userId)->update($data);
    }

    public function checkUserNameExist($username){
        $count = $this->db->table('users')->where('username', $username)->countAllResults();
        return ($count == 0);
    }

    public function createAdminUser($data)
    {
        if (!empty($data)) {
            $userTable = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'username' => $data['username'],
                'password' => $data['password'],
                'email' => $data['email'],
                'specialization' => $data['specialization'],
                'phone' => $data['phone'],
                'active' => $data['active'],
                'created_at' => $this->commonModel->currentColomboTime(),
                'created_by' => session()->get('user_id')
            ];

            $this->db->table('users')->insert($userTable);
            $id = $this->db->insertID();

            if ($id) {
                return ['success' => true, 'message' => 'Successfully created', 'data' => ['user_id' => $id]];
            } else {
                return ['success' => false, 'message' => 'Failed to create user'];
            }
        } else {
            return ['success' => false, 'message' => 'Empty data submitted'];
        }
    }

    public function userRegistration($data){
        if (!empty($data)) {
            $userTable = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'password' => $data['password'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'active' => true,
                'created_at' => $this->commonModel->currentColomboTime()
            ];

            $this->db->table('users')->insert($userTable);
            $id = $this->db->insertID();

            if ($id) {
                return ['success' => true, 'message' => 'Successfully created', 'data' => ['user_id' => $id]];
            } else {
                return ['success' => false, 'message' => 'Failed to create user'];
            }
        } else {
            return ['success' => false, 'message' => 'Empty data submitted'];
        }
    }


    public function getUserDataById($user_id)
    {
        $builder = $this->db->table('users');
        $builder->select('users.*, roles.id as role_id');
        $builder->join('user_roles', 'user_roles.user_id = users.id');
        $builder->join('roles', 'roles.id = user_roles.role_id');
        $builder->where('users.id', $user_id);
        $query = $builder->get();

        if ($query->getNumRows() == 1) {
            $row = $query->getRowArray();
            unset($row['password']);
            return $row;
        } else {
            return false;
        }
    }

    public function updateAdminUser($userData)
    {
        try {
            $data = [];
            if (!empty($userData['password'])) {
                $data['password'] = $userData['password'];
            }
            if (!empty($userData['first_name'])) {
                $data['first_name'] = $userData['first_name'];
            }
            if (!empty($userData['last_name'])) {
                $data['last_name'] = $userData['last_name'];
            }
            if (!empty($userData['gender'])) {
                $data['gender'] = $userData['gender'];
            }
            if (!empty($userData['phone'])) {
                $data['phone'] = $userData['phone'];
            }
            if (!empty($userData['email'])) {
                $data['email'] = $userData['email'];
            }
            if (!empty($userData['specialization'])) {
                $data['specialization'] = $userData['specialization'];
            }
            $data['active'] = $userData['active'];

            // Load Query Builder module
            $builder = $this->db->table('users');

            // Update the user record
            $this->db->transBegin();
            $builder->where('id', $userData['user_id']);
            $builder->update($data);
            $this->db->transCommit();

            // Get the number of affected rows
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
            $this->db->transRollback();
            return false;
        }
    }


    public function deleteAdminUser($user_id)
    {
        try {
            $user = $this->db->table('users')->where('id', $user_id)->get()->getRow();
            if ($user) {
                $data['active'] = false;
                $this->db->transStart();
                $this->db->table('users')->where('id', $user_id)->update($data);
                $this->db->transCommit();

                $affectedRows = $this->db->affectedRows();
                if ($this->db->transStatus() === false) {
                    return false;
                } else {
                    if ($affectedRows > 0) {
                        return true;
                    } else {
                        return true;
                    }
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            return false;
        }
    }



    public function getAllAuthUsers($postData = null)
    {
        $columns = [
            // datatable column index  => database column name
            0 => 'users.id',
            1 => 'CONCAT(users.first_name, " ", users.last_name) as full_name',
            2 => 'users.phone',
            3 => 'users.active',
            4 => 'roles.name as role_name'
        ];

        $draw = $postData['draw'] ?? null;
        $start = $postData['start'] ?? null;
        $rowperpage = $postData['length'] ?? null; // Rows display per page
        $columnIndex = $postData['order'][0]['column'] ?? null; // Column index
        $columnName = $columns[$columnIndex] ?? null; // Column name
        $columnSortOrder = $postData['order'][0]['dir'] ?? null; // asc or desc
        $searchValue = $postData['search']['value'] ?? null; // Search value

        ## Search
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = "users.id IS NOT NULL AND (users.id LIKE '%".$searchValue."%' OR users.first_name LIKE '%".$searchValue."%' OR users.last_name LIKE '%".$searchValue."%' OR users.phone LIKE '%".$searchValue."%' OR roles.name LIKE '%".$searchValue."%')";
        }

        ## Total number of records without filtering
        $totalRecords = $this->db->table('users')
            ->select('count(*) as allcount')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id')
           // ->where('roles.name IN ("admin", "manager")')
            ->countAllResults(false);

        ## Total number of records with filtering
        $totalRecordwithFilter = $this->db->table('users')
            ->select('count(*) as allcount')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id');
           // ->where('roles.name IN ("admin", "manager")');
        if ($searchQuery != '') {
            $totalRecordwithFilter->where($searchQuery);
        }
        $totalRecordwithFilter = $totalRecordwithFilter->countAllResults(false);

        ## Fetch records
        $records = $this->db->table('users')
            ->select('users.id, users.nic, users.username, users.first_name, users.last_name, users.gender, users.phone, users.last_login, users.active, roles.name as role_name')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id');
            //->where('roles.name IN ("admin", "manager")');
        if ($searchQuery != '') {
            $records->where($searchQuery);
        }
        $records = $records->orderBy($columnName, $columnSortOrder)
            ->limit($rowperpage, $start)
            ->get()->getResult();

        $data = [];
        foreach ($records as $row) {
            $active = $row->active ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Deactivate</span>';

            $nestedData = [
                $row->id,
                $row->first_name,
                $row->nic,
                $row->username,
                $row->phone,
                ucfirst($row->gender),
                $active,
                $row->role_name,
                '<ul class="action"> 
                <li class="edit"> <a href="javascript:void(0);" data-user="'.$row->id.'" onClick="viewMember('.$row->id.')"><i class="icon-pencil-alt"></i></a></li>
                <li class="delete"><a href="javascript:void(0);" data-user="'.$row->id.'" onClick="deleteMember('.$row->id.')"><i class="icon-trash"></i></a></li>
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

    public function getUsersByRole($roleId)
    {
        $builder = $this->db->table('users');
        $builder->select('users.id, users.email, CONCAT(users.first_name, " ", users.last_name) AS name, users.gender, users.phone, users.dob, users.join_date, users.address, users.district, users.active');
        $builder->join('user_roles', 'users.id = user_roles.user_id');
        $builder->where('users.active', true);
        $builder->where('user_roles.role_id', $roleId);

        return $builder->get()->getResultArray();
    }

    public function getUsersDataById($id)
    {
        $builder = $this->db->table('users');
        $builder->select('id, username, nic, first_name, last_name, gender, phone, email, specialization, dob, join_date, address, district, active, created_by, last_login, created_at, updated_at');
        $builder->where('users.active', true);
        $builder->where('users.id', $id);

        return $builder->get()->getResultArray();
    }


}
