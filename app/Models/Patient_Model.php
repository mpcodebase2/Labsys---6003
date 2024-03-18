<?php
namespace App\Models;

use App\Libraries\Password;
use CodeIgniter\Model;
use Exception;
class Patient_Model extends Model
{
    protected $common_model;
    protected $table = 'patients';
    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
        $this->password_lib = new Password();
    }

    public function getAllPatients($postData = null)
    {
        $builder = $this->db->table('patients'); // Specify the table 'patients'

        $columns = [
            0 => 'patients.id',
            1 => 'users.first_name'.'users.last_name',
            2 => 'patients.registered_date',
            3 => 'patients.address_ln1'.'patients.address_ln2'.'patients.city'.'districts.name_en AS district_name_en'.'provinces.name_en AS province_name_en'.'country.name AS country_name',
            4 => 'patients.telephone'.'patients.mobile',
            5 => 'users.nic',
            6 => 'users.gender',
            7 => 'users.dob',
            8 => 'patients.is_active'
        ];

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $columns[$columnIndex];
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        $searchableColumns = [
            'patients.id',
            'patients.user_id',
            'patients.registered_date',
            'patients.address_ln1',
            'patients.address_ln2',
            'patients.city',
            'patients.telephone',
            'patients.mobile',
            'patients.occupation',
            'patients.religion',
            'patients.nationality',
            'users.nic',
            'users.first_name',
            'users.last_name',
            'users.gender',
            'users.phone',
            'users.email',
            'users.dob',
            'country.name',
            'districts.name_en',
            'provinces.name_en'
        ];


        // Total number of records without filtering
        $builder = $this->db->table('patients');
        $builder->selectCount('patients.id', 'allcount')
            ->join('users', 'patients.user_id = users.id')
            ->join('country', 'patients.country = country.id')
            ->join('districts', 'patients.district = districts.id')
            ->join('provinces', 'patients.province = provinces.id')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id');
        $builder->whereIn('roles.name', ['patient']);

        $totalRecords = $builder->get()->getRow()->allcount;

// Total number of records with filtering
        $builder = $this->db->table('patients');
        $builder->selectCount('patients.id', 'allcount')
            ->join('users', 'patients.user_id = users.id')
            ->join('country', 'patients.country = country.id')
            ->join('districts', 'patients.district = districts.id')
            ->join('provinces', 'patients.province = provinces.id')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id');
        $builder->whereIn('roles.name', ['patient']);

        // Apply search filter
        if (!empty($searchValue)) {
            $builder->groupStart();
            foreach ($searchableColumns as $column) {
                $builder->orLike($column, $searchValue);
            }
            $builder->groupEnd();
        }

        $totalRecordwithFilter = $builder->get()->getRow()->allcount;

        $builder->select('patients.id, patients.user_id, patients.registered_date, patients.address_ln1, patients.address_ln2, patients.city, patients.district, patients.province, patients.country, patients.telephone, patients.mobile, patients.occupation, patients.religion, patients.nationality, patients.is_active, patients.is_staff,
    users.id AS user_id, users.username, users.password, users.nic, users.first_name, users.last_name, users.gender, users.phone, users.email, users.dob, users.join_date, users.address, users.district AS user_district, users.active AS user_active, users.created_by, users.last_login, users.created_at, users.updated_at,
   
    country.id AS country_id, country.name AS country_name, country.active AS country_active,
 
    districts.id AS district_id, districts.province_id, districts.name_en AS district_name_en, districts.name_si AS district_name_si, districts.name_ta AS district_name_ta, districts.active AS district_active,
    
    provinces.id AS province_id, provinces.name_en AS province_name_en, provinces.name_si AS province_name_si, provinces.name_ta AS province_name_ta, provinces.active AS province_active')
            ->join('users', 'patients.user_id = users.id', 'left')
            ->join('country', 'patients.country = country.id', 'left')
            ->join('districts', 'patients.district = districts.id', 'left')
            ->join('provinces', 'patients.province = provinces.id', 'left')
            ->join('user_roles', 'user_roles.user_id = users.id')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->whereIn('roles.name', ['patient'])
            ->orderBy($columnName, $columnSortOrder)
            ->limit($rowperpage, $start);

        $records = $builder->get()->getResultArray();

        $data = [];
        foreach ($records as $row) {
            $isActive = $row['is_active'] ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Inactive</span>';
            $isStaff = $row['is_staff'] ? 'Yes' : 'No';

            $patientId = 'P-'.$row['id'];
            $name = $row['first_name'].' '.$row['last_name'];
            $address = $row['address_ln1'] .
                ($row['address_ln2'] ? ', ' . $row['address_ln2'] : '') .
                ($row['city'] ? ', ' . $row['city'] : '') .
                ($row['district_name_en'] ? ', ' . $row['district_name_en'] : '') .
                ($row['province_name_en'] ? ', ' . $row['province_name_en'] : '') .
                ($row['country_name'] ? ', ' . $row['country_name'] : '');
            $phone = ($row['telephone'] ?: '') .
                ($row['telephone'] ? ' / ': '').($row['mobile'] ? : '');
            $registeredDate = $this->common_model->formatDateWithEnglishLetter($row['registered_date']);

            $nestedData = [
                $patientId,
                $name,
                $registeredDate,
                $address,
                $phone,
                // Additional columns from 'users' table
                $row['nic'],
                $row['gender'],
                $row['dob'],
                $isActive,
                '<ul class="action">
                        <li class="edit"><a href="javascript:void(0);" data-roles="'.$row['id'].'" onClick="viewPatients('.$row['id'].')"><i class="icon-pencil-alt"></i></a></li>
                        <li class="delete"><a href="javascript:void(0);" data-roles="'.$row['id'].'" onClick="deletePatients('.$row['id'].')"><i class="icon-trash"></i></a></li>
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
    public function createPatients($data)
    {
        if (!empty($data)) {
            $userTable = [
                'email' => $data['email'],
                'nic' => $data['nic'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'phone' => $data['telephone'],
                'dob' => $data['dob'],
                'join_date' => $this->common_model->currentColomboTime(),
                'active' => $data['is_active']
            ];
            //Only add password if set in patient create form.
            if (!empty($data['password'])) {
                $hashed = $this->password_lib->create_hash($data["password"]);
                $userTable['password'] = $hashed;
            }

            $this->db->table('users')->insert($userTable);
            $user_id = $this->db->insertID();

            if($user_id){
                //Add Patient Role to this user
                $user_role_Table = [
                    'user_id' => $user_id,
                    'role_id' => ROLE_ID_PATIENT
                    ];
                $this->db->table('user_roles')->insert($user_role_Table);

                //Add in Patient table
                $patientsTable = [
                    'user_id' => $user_id,
                    'registered_date' => $this->common_model->currentColomboTime(),
                    'address_ln1' => $data['address_ln1'],
                    'address_ln2' => $data['address_ln2'],
                    'city' => $data['city'],
                    'district' => $data['district'],
                    'province' => $data['province'],
                    'country' => $data['country'],
                    'telephone' => $data['telephone'],
                    'mobile' => $data['mobile'],
                    'occupation' => $data['occupation'],
                    'religion' => $data['religion'],
                    'nationality' => $data['nationality'],
                    'is_active' => $data['is_active'],
                    'is_staff' => $data['is_staff']
                ];

                $this->db->table('patients')->insert($patientsTable);
                $id = $this->db->insertID();
                return (bool)$id;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    public function createPatientsProfile($data, $userId)
    {
        if (!empty($data)) {
            if($userId){
                $patient_id = $this->getPatientByUserId($userId);
                //Add in Patient table
                $usersTable = [
                    'nic' => $data['nic'],
                    'gender' => $data['gender'],
                    'dob' => $data['dob'],
                    'join_date' => $this->common_model->currentColomboTime(),
                    'address' => $data['address_ln1'].$data['address_ln1'],
                    'district' => $data['district'],
                    'phone' => $data['telephone'],
                    'active' => true
                ];

                $patientsTable = [
                    'user_id' => $userId,
                    'registered_date' => $this->common_model->currentColomboTime(),
                    'address_ln1' => $data['address_ln1'],
                    'address_ln2' => $data['address_ln2'],
                    'city' => $data['city'],
                    'district' => $data['district'],
                    'province' => $data['province'],
                    'country' => $data['country'],
                    'telephone' => $data['telephone'],
                    'mobile' => $data['mobile'],
                    'occupation' => $data['occupation'],
                    'religion' => $data['religion'],
                    'nationality' => $data['nationality'],
                    'is_active' => true,
                    'is_staff' => false
                ];

                //update user table
                $this->db->table('users')
                    ->where('id', $userId)
                    ->update($usersTable);
                $this->db->transCommit();
                $affectedRowsUsers = $this->db->affectedRows();


                if(!$patient_id){
                    //No Patient profile so create
                    $this->db->table('patients')->insert($patientsTable);
                    $id = $this->db->insertID();
                    return (bool)$id;
                }else{
                    //Has Profile so update
                    $this->db->table('patients')
                        ->where('id', $patient_id)
                        ->update($patientsTable);
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
                }
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    // Retrieves all patients
    public function getDataAll(){
        $builder = $this->db->table('patients');
        $builder->select('patients.*, users.email, users.nic, CONCAT(users.first_name, " ", users.last_name) AS name, users.gender, users.phone, users.dob, users.join_date, users.active as user_active');
        $builder->join('users', 'patients.user_id = users.id', 'left');
        $query = $builder->get();
        if ($query->getResultArray()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function getDataAllTest(){
        $builder = $this->db->table('patients');
        $builder->select('patients.*, users.email, users.nic, CONCAT(users.first_name, " ", users.last_name) AS name, users.gender, users.phone, users.dob, users.join_date, users.active as user_active');
        $builder->join('users', 'patients.user_id = users.id', 'left');
        $query = $builder->get();
        if ($query->getResultArray()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    // Retrieves a role by ID
    public function getDataById($id)
    {
        $builder = $this->db->table('patients');
        $builder->select('patients.*, users.email, users.nic, users.first_name, users.last_name, users.gender, users.phone, users.dob, users.join_date, users.active as user_active');
        $builder->join('users', 'patients.user_id = users.id', 'left');
        $builder->where('patients.id', $id);
        $query = $builder->get();
        if ($query->getRowArray()) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function getPatientByUserId($user_id)
    {
        $builder = $this->db->table('patients');
        $builder->select('patients.id');
        $builder->where('patients.user_id', $user_id);
        $query = $builder->get();
        if ($query->getRowArray()) {
            $row = $query->getRowArray();
            return $row['id'];
        } else {
            return false;
        }
    }
    public function updatePatients($data)
    {
        try {
            // Load Query Builder module
            $builder = $this->db->table('patients');

            // Get user_id from patients table
            $patient = $builder->select('user_id')->where('id', $data['id'])->get()->getRow();
            $user_id = $patient->user_id;

            // Prepare data for user update
            $userData = [
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'phone' => $data['telephone'] ?? null,
                'email' => $data['email'] ?? null,
                'active' => $data['is_active']
            ];

            //Only update password if set in patient update form.
            if (!empty($data['password'])) {
                $hashed = $this->password_lib->create_hash($data["password"]);
                $userData['password'] = $hashed;
            }

            // Begin transaction
            $this->db->transBegin();

            // Update user record
            $builder = $this->db->table('users');
            $builder->where('id', $user_id);
            $builder->update($userData);

            // Update patient record
            $patientData = [
                'address_ln1' => $data['address_ln1'],
                'address_ln2' => $data['address_ln2'],
                'city' => $data['city'],
                'district' => $data['district'],
                'province' => $data['province'],
                'country' => $data['country'],
                'telephone' => $data['telephone'],
                'mobile' => $data['mobile'],
                'occupation' => $data['occupation'],
                'religion' => $data['religion'],
                'nationality' => $data['nationality'],
                'is_active' => $data['is_active'],
                'is_staff' => $data['is_staff']
            ];

            $this->db->table('patients')->where('id', $data['id'])->update($patientData);

            // Commit transaction
            $this->db->transCommit();

            // Check for transaction status and affected rows
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
            // Rollback transaction in case of error
            $this->db->transRollback();
            return false;
        }
    }


    // Deletes a role record by ID
    public function deletePatients($id)
    {
        // Get user_id from patients table
        $builder = $this->db->table('patients');
        $patient = $builder->select('user_id')->where('id', $id)->get()->getRow();
        $user_id = $patient->user_id;

        //Permanant delete
//        $this->db->table('users')
//            ->where('id', $user_id)
//            ->delete();
//
//        $this->db->table('patients')
//            ->where('id', $id)
//            ->delete();
        $userData = [
            'active' => false
        ];

        // Begin transaction
        $this->db->transBegin();

        // Update user record
        $builder = $this->db->table('users');
        $builder->where('id', $user_id);
        $builder->update($userData);

        // Update patient record
        $patientData = [
            'is_active' => false
        ];

        $this->db->table('patients')->where('id', $id)->update($patientData);

        // Commit transaction
        $this->db->transCommit();

        // Check for transaction status and affected rows
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
