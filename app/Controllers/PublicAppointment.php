<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Appointment_Model;
use App\Libraries\UserSession;

use App\Models\GetData_Model;
use App\Models\UserModel;
use App\Models\Patient_Model;
use App\Models\LabTest_Model;
use App\Models\Common_Model;

class PublicAppointment extends BaseController
{
    private $appointmentModel;
    private $rolePermissionModel;
    private $userSession;
    private $getData;

    private $session;
    private $userModel;
    private $patientModel;
    private $labTestModel;
    private $doctorRoleId;
    private $patientRoleId;
    protected $common_model;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();


        $this->rolePermissionModel = new RolePermission_Model();
        $this->appointmentModel = new Appointment_Model();
        $this->userSession = new UserSession();

        $this->getData = new GetData_Model();
        $this->userModel = new UserModel();
        $this->patientModel = new Patient_Model();
        $this->labTestModel = new LabTest_Model();
        $this->common_model = new Common_Model();
        $this->doctorRoleId = 7;
        $this->patientRoleId = 6;

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allAppointment');
            if ($hasPermission['hasPermission']) {
                $data = ['breadcrumb' => 'All Appointment', 'islogin' => $this->userSession->isLoggedIn(), 'title' => 'All Appointment', 'status' => APPOINTMENT_STATUS, 'patients' => $this->patientModel->getDataAll(), 'tests' => $this->labTestModel->getLabTestsSelection(), 'doctors' => $this->userModel->getUsersByRole($this->doctorRoleId)];
                echo view('Patients/public_all_appointment', $data);
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('dashboard'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('login'));
        }
    }

    public function publicCreateAppointment(){
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('AppointmentsPatientCreate');
            if ($hasPermission['hasPermission']) {
                $userId = (session('user_id'))?:false; $patient_id='';
                if($userId){
                    $patient_id = $this->patientModel->getPatientByUserId($userId);
                }
                if($patient_id){
                    $data = [
                        'breadcrumb' => 'All Appointment',
                        'islogin' => $this->userSession->isLoggedIn(),
                        'title' => 'All Appointment',
                        'status' => APPOINTMENT_STATUS,
                        'patients' => $this->patientModel->getDataAll(),
                        'tests' => $this->labTestModel->getLabTestsSelection(),
                        'doctors' => $this->userModel->getUsersByRole($this->doctorRoleId)
                    ];
                    echo view('Patients/public_create_appointment', $data);
                }else{
                    return redirect()->to(base_url('/create-profile'));
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('/'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('login'));
        }
    }

    public function createAppointmentAPI(){
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('AppointmentsPatientCreate');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'test_id' => 'required',
                        'note' => 'max_length[400]',
                        'amount' => 'required',
                        'paid' => 'required',
                        'due' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $userId = (session('user_id'))?:false; $patient_id='';
                        if($userId){
                            $patient_id = $this->patientModel->getPatientByUserId($userId);
                        }
                        if($patient_id){
                            $cleanData['patient_id'] = $patient_id;
                            $appointmentId = $this->appointmentModel->createAppointmentByPatient($cleanData);
                            if ($appointmentId) {
                                $decodeAppointmentId = $this->common_model->encode_url($appointmentId);
                                $url_e = 'payment/'.$decodeAppointmentId;
                                return $this->response->setJSON($this->_arrayFormat(true, 'Successfully created!.', ['action' => 'redirect', 'url' => $url_e]));
                            } else {
                                return $this->response->setJSON($this->_arrayFormat(false, 'Created error', ''));
                            }
                        }else{
                            return $this->response->setJSON($this->_arrayFormat(false, 'Patient not found', ''));
                        }
                    }
                } else {
                    return $this->response->setJSON($this->_arrayFormat(false, 'Request Error', ''));
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return $this->response->setJSON($this->_arrayFormat(false, 'Session expired', ['action' => 'redirect', 'url' => 'admin/dashboard']));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return $this->response->setJSON($this->_arrayFormat(false, 'Session expired', ['action' => 'redirect', 'url' => 'admin/login']));
        }
    }


    public function getAppointmentDataTableByPatientID(){
        $postData = $this->request->getPost();
        $dataForTable = $this->appointmentModel->getAllAppointments($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function getChartData(){
        $dataForTable = $this->appointmentModel->getChartData();
        return $this->response->setJSON($dataForTable);
    }


    private function _arrayFormat($status, $message, $data)
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }


}