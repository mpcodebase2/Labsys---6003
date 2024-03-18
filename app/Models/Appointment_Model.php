<?php
namespace App\Models;

use App\Libraries\Password;
use CodeIgniter\Model;
use Exception;


class Appointment_Model extends Model
{
    protected $common_model;
    private $userModel;
    private $fileUploadModel;

    public function __construct()
    {
        parent::__construct();
        $this->session = session();
        $this->common_model = new Common_Model();
        $this->password_lib = new Password();
        $this->userModel = new UserModel();
        $this->fileUploadModel = new FileUpload_Model();
    }

    public function getAllAppointments($postData = null)
    {


        // Check if the current session's role ID is ROLE_ID_PATIENT
        if ($this->session->get('role_id') == ROLE_ID_PATIENT) {
            // Get the current user's ID
            $currentUserId = $this->session->get('user_id');
        } else {
            // If the current session's role ID is not ROLE_ID_PATIENT, set $currentUserId to null
            $currentUserId = null;
        }


        $columns = [0 => 'appointments.id', 1 => 'users.first_name' . 'users.last_name', 2 => 'appointments.appointment_date', 3 => 'test_list.name', 4 => 'appointments.expected_date', 5 => 'appointments.delivered_date', 6 => 'appointments.status', 7 => 'test_list.amount', 8 => 'appointments.paid', 9 => 'appointments.cost'];

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length'];
        $columnIndex = $postData['order'][0]['column'];
        $columnName = $columns[$columnIndex];
        $columnSortOrder = $postData['order'][0]['dir'];
        $searchValue = $postData['search']['value'];

        $searchableColumns = ['appointments.id', 'users.first_name', 'users.last_name', 'appointments.appointment_date', 'test_list.name', 'appointments.status', 'test_list.cost'];


        // Total number of records without filtering
        $builder = $this->db->table('appointments');
        $builder->selectCount('appointments.id', 'allcount')->join('patients', 'appointments.patient_id = patients.id')->join('users', 'patients.user_id = users.id')->join('test_list', 'appointments.test_id = test_list.id');
        if ($currentUserId !== null) {
            $builder->where('users.id', $currentUserId);
        }
        $totalRecords = $builder->get()->getRow()->allcount;

// Total number of records with filtering
        $builder = $this->db->table('appointments');
        $builder->selectCount('appointments.id', 'allcount')->join('patients', 'appointments.patient_id = patients.id')->join('users', 'patients.user_id = users.id')->join('test_list', 'appointments.test_id = test_list.id');
        if ($currentUserId !== null) {
            $builder->where('users.id', $currentUserId);
        }
        // Apply search filter
        if (!empty($searchValue)) {
            $builder->groupStart();
            foreach ($searchableColumns as $column) {
                $builder->orLike($column, $searchValue);
            }
            $builder->groupEnd();
        }

        $totalRecordwithFilter = $builder->get()->getRow()->allcount;

        $builder = $this->db->table('appointments');
        $builder->select('appointments.id as appointments_id, appointments.patient_id, appointments.test_id, appointments.doctor_id, appointments.appointment_date, appointments.expected_date, appointments.delivered_date, appointments.note, appointments.status, appointments.amount, appointments.paid, appointments.due, appointments.paid_via, appointments.created_at,appointments.active as ap_active,
    
    users.id AS user_id, users.username, users.password, users.nic, users.first_name, users.last_name, users.gender, users.phone, users.email, users.dob, users.join_date, users.address, users.district AS user_district, users.active AS user_active, users.created_by, users.last_login, users.created_at, users.updated_at,
   
    test_list.id, test_list.name as TestName, test_list.description, test_list.cost, test_list.active, test_list.delete_flag, test_list.date_created, test_list.date_updated')->join('patients', 'appointments.patient_id = patients.id')->join('users', 'patients.user_id = users.id')->join('test_list', 'appointments.test_id = test_list.id');
        if ($currentUserId !== null) {
            $builder->where('users.id', $currentUserId);
        }
        // Apply search filter
        if (!empty($searchValue)) {
            $builder->groupStart();
            foreach ($searchableColumns as $column) {
                $builder->orLike($column, $searchValue);
            }
            $builder->groupEnd();
        }

        $builder->orderBy($columnName, $columnSortOrder)->limit($rowperpage, $start);


        $records = $builder->get()->getResultArray();

        $data = [];
        foreach ($records as $row) {
            $isActive = $row['ap_active'] ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Inactive</span>';

            $appointmentId = 'AP-' . $row['appointments_id'];

            $name = $row['first_name'] . ' ' . $row['last_name'];
            $appointment_date = $this->common_model->formatDateWithEnglishLetter($row['appointment_date']);
            $expected_date = $this->common_model->formatDateWithEnglishLetter($row['expected_date']);
            $delivered_date = ($row['delivered_date']) ? $this->common_model->formatDateWithEnglishLetter($row['delivered_date']) : '-';

            $date = '<div class="app_date">A: ' . $appointment_date . '</div><div class="expected_date">E: ' . $expected_date . '</div><div class="delivered_date">F: ' . $delivered_date . '</div>';

            $doctor_name = $this->userModel->getUserDataById($row['doctor_id']);

            $nestedData = [$appointmentId, $row['TestName'], $name, ($doctor_name) ? $doctor_name['first_name'] . ' ' . $doctor_name['last_name'] : 'Not Set', $date, $row['amount'], $row['paid'], $row['due'], $row['status'], $isActive, '<ul class="action">
                        <li class="edit"><a href="javascript:void(0);" data-roles="' . $row['appointments_id'] . '" onClick="viewAppointments(' . $row['appointments_id'] . ')"><i class="icon-pencil-alt"></i></a></li>
                        <li class="delete"><a href="javascript:void(0);" data-roles="' . $row['appointments_id'] . '" onClick="deleteAppointments(' . $row['appointments_id'] . ')"><i class="icon-trash"></i></a></li>
                    </ul>'];

            $data[] = $nestedData;
        }

        $json_data = ["draw" => intval($draw), "recordsTotal" => intval($totalRecords), "recordsFiltered" => intval($totalRecordwithFilter), "data" => $data];

        return $json_data;
    }


    // Creates a new role record
    public function createAppointments($data)
    {
        if (!empty($data)) {
            $appointmentsTable = ['patient_id' => (int)$data['patient_id'], 'test_id' => (int)$data['test_id'], 'doctor_id' => (int)$data['doctor_id'], 'appointment_date' => $data['appointment_date'], 'expected_date' => $data['expected_date'], 'note' => $data['note'], 'amount' => $data['amount'], 'paid' => $data['paid'], 'status' => APPOINTMENT_STATUS[0], 'due' => $data['due'], 'paid_via' => $data['paid_via'], 'active' => true];

            $this->db->table('appointments')->insert($appointmentsTable);
            $appointmentId = $this->db->insertID();

            if ($appointmentId) {
                //Add Appointment History
                $this->addAppointmentHistory($appointmentId, 'Created');
                return $appointmentId;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function createAppointmentByPatient($data)
    {
        if (!empty($data)) {
            $appointmentsTable = ['patient_id' => (int)$data['patient_id'], 'test_id' => (int)$data['test_id'], 'appointment_date' => $this->common_model->currentColomboTime(), 'note' => $data['note'], 'amount' => $data['amount'], 'paid' => $data['paid'], 'status' => APPOINTMENT_STATUS[0], 'due' => $data['due'], 'paid_via' => $data['paid_via'], 'active' => true];

            $this->db->table('appointments')->insert($appointmentsTable);
            $appointmentId = $this->db->insertID();

            if ($appointmentId) {
                //Add Appointment History
                $this->addAppointmentHistory($appointmentId, 'Created');
                return $appointmentId;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Update
    public function updateAppointments($data, $files, $ipAddress)
    {
        try {
            $appointment_id = $data['appointment_id'];
            if (isset($appointment_id)) {
                $dataReturn['appointment'] = false;
                $appointmentsTable = ['patient_id' => $data['patient_id'], 'test_id' => $data['test_id'], 'doctor_id' => $data['doctor_id'], 'appointment_date' => $data['appointment_date'], 'expected_date' => $data['expected_date'], 'note' => $data['note'], 'amount' => $data['amount'], 'status' => $data['status'], 'paid' => $data['paid'], 'due' => $data['due'], 'paid_via' => $data['paid_via'], 'active' => true];

                // Begin transaction
                $this->db->transBegin();

                // Update user record
                $builder = $this->db->table('appointments');
                $builder->where('id', $appointment_id);
                $builder->update($appointmentsTable);

                // Commit transaction
                $this->db->transCommit();
                // Check for transaction status and affected rows
                $affectedRows = $this->db->affectedRows();
                if ($this->db->transStatus() === false) {
                    $dataReturn['appointment'] = false;
                } else {
                    if ($affectedRows > 0) {
                        $this->addAppointmentHistory($appointment_id, $data['status']);
                        $dataReturn['appointment'] = true;
                    } else {
                        $dataReturn['appointment'] = true;
                    }
                }

                if (!empty($files)) {
                    $timeStamp = $this->common_model->getCurrentTimeStamp();
                    foreach ($files['name'] as $key => $file_name) {
                        $filename = 'test_result' . '_' . $timeStamp;
                        $folderNamePre = 'test_result';
                        $fieldName = 'media_files';
                        $dataFile = $this->fileUploadModel->uploadMultipleImages($fieldName, $key, $folderNamePre, $filename, 'appointment', $ipAddress);
                        if ($dataFile['status']) {
                            //insert media table entry
                            $media_id = $this->fileUploadModel->updateDatabaseEntry($dataFile['data'], 'media');

                            //insert appointment_media table entry
                            $pm = ['appointment_id' => $appointment_id, 'media_id' => $media_id, 'active' => true];
                            $this->fileUploadModel->updateDatabaseEntry($pm, 'appointment_media');

                            $media[] = $media_id;
                        }
                    }
                }
                return $dataReturn['appointment'];
            } else {
                return false;
            }
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            $this->db->transRollback();
            return false;
        }
    }

    // Deletes a Appointment record by ID
    public function deleteAppointments($id)
    {
        // Update appointment record
        $appointmentData = ['active' => false];

        $this->db->table('appointments')->where('id', $id)->update($appointmentData);

        // Commit transaction
        $this->db->transCommit();

        // Check for transaction status and affected rows
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
    }

    // Retrieves all appointments
    public function getDataAll()
    {
        $builder = $this->db->table('appointments');
        $builder->select('appointments.*, users.email, users.nic, users.first_name, users.last_name, users.gender, users.phone, users.dob, users.join_date, users.active as user_active');
        $builder->join('users', 'appointments.patient_id = users.id', 'left');
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
        $builder = $this->db->table('appointments');
        $builder->select('appointments.*, users.email, users.nic, users.first_name, users.last_name, users.gender, users.phone, users.dob, users.join_date, users.active as user_active, test_list.name as TestName, test_list.description, test_list.cost, test_list.active, test_list.delete_flag, test_list.date_created, test_list.date_updated');
        $builder->join('users', 'appointments.patient_id = users.id', 'left');
        $builder->join('test_list', 'appointments.test_id = test_list.id');
        $builder->where('appointments.id', $id);
        $query = $builder->get();
        if ($row = $query->getRowArray()) {
            $doctor_name = $this->userModel->getUserDataById($row['doctor_id']);
            $mediaArray = $this->getAppointmentMedia($id);
            $row['media_files'] = $mediaArray;
            $row['doctor'] = ($doctor_name) ? 'Dr.' . $doctor_name['first_name'] . ' ' . $doctor_name['last_name'] : 'Not Set';
            return $row;
        } else {
            return false;
        }
    }

    public function getAppointmentDataById($id)
    {
        $builder = $this->db->table('appointments');
        $builder->select('appointments.*, users.email, users.nic, users.first_name, users.last_name, users.gender, users.phone, users.dob, users.join_date, users.active as user_active, test_list.name as test_name');
        $builder->join('users', 'appointments.patient_id = users.id', 'left');
        $builder->join('test_list', 'appointments.test_id = test_list.id', 'left');
        $builder->where('appointments.id', $id);
        $query = $builder->get();
        if ($row = $query->getRowArray()) {
            return $row;
        } else {
            return false;
        }
    }


    private function getAppointmentMedia($appointmentId)
    {
        $builder = $this->db->table('appointment_media');
        $builder->select('appointment_media.id, appointment_media.appointment_id, appointment_media.media_id, appointment_media.active as am_active, media.image_for, media.file_name, media.file_type, media.file_size, media.is_image, media.image_width, media.image_height, media.image_type, media.path, media.active, media.ip_address, media.updated_at');
        $builder->join('media', 'media.id = appointment_media.media_id');
        $builder->where('appointment_media.appointment_id', $appointmentId);
        $query = $builder->get();

        $data = [];
        foreach ($query->getResult() as $row) {
            $media = ['id' => $row->id, 'media_id' => $row->media_id, 'am_active' => $row->am_active, 'image_for' => $row->image_for, 'file_name' => $row->file_name, 'file_type' => $row->file_type, 'file_size' => $row->file_size, 'is_image' => $row->is_image, 'image_width' => $row->image_width, 'image_height' => $row->image_height, 'image_type' => $row->image_type, 'path' => base_url('uploads/' . $row->path)];
            $data[] = $media;
        }
        return $data;
    }


    public function addAppointmentHistory($appointmentId, $status)
    {
        $user_role_Table = ['appointment_id' => $appointmentId, 'status' => $status, 'update_by' => session('user_id'), 'created_at' => $this->common_model->currentColomboTime()];
        $this->db->table('appointment_history')->insert($user_role_Table);
    }


    public function getChartData()
    {
        // Define all statuses
        $statuses = ['Pending', 'In progress', 'Completed', 'Follow-up required', 'On hold', 'Rescheduled', 'Cancelled'];

        // Get current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Calculate start and end dates of the current month
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        // Initialize arrays to hold data
        $statusData = [];
        $dates = [];

        // Loop through each status
        foreach ($statuses as $status) {
            // Fetch appointments for each status and date within the current month
            $builder = $this->db->table('appointments');
            $appointments = $builder->select('DATE(appointment_date) as date, COUNT(*) as count')
                ->where('status', $status)
                ->where('YEAR(appointment_date)', $currentYear)
                ->where('MONTH(appointment_date)', $currentMonth)
                ->groupBy('date')
                ->get()
                ->getResultArray();

            // Initialize array to hold count of appointments for each date
            $appointmentsCount = [];

            // Loop through appointments to count appointments for each date
            foreach ($appointments as $appointment) {
                $date = $appointment['date'];
                $appointmentsCount[$date] = $appointment['count'];
                // Collect dates
                if (!in_array($date, $dates)) {
                    $dates[] = $date;
                }
            }

            // Add data for each status to the statusData array
            $statusData[] = ['name' => $status, 'data' => array_values($appointmentsCount)];
        }

        // Sort dates
        sort($dates);

        return [
            'statuses' => $statusData,
            'dates' => $dates
        ];
    }


}
