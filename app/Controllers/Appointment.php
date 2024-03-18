<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Appointment_Model;
use App\Libraries\UserSession;

use App\Models\GetData_Model;
use App\Models\UserModel;
use App\Models\Patient_Model;
use App\Models\LabTest_Model;

class Appointment extends BaseController
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
        $this->doctorRoleId = 7;
        $this->patientRoleId = 6;

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allAppointment');
            if ($hasPermission['hasPermission']) {
                $data = [
                    'breadcrumb' => 'All Appointment',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'title' => 'All Appointment',
                    'status' => APPOINTMENT_STATUS,
                    'patients' => $this->patientModel->getDataAll(),
                    'tests' => $this->labTestModel->getLabTestsSelection(),
                    'doctors' => $this->userModel->getUsersByRole($this->doctorRoleId)
                ];
                echo view('Patients/all_appointment', $data);
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function allAppointmentData()
    {
        $postData = $this->request->getPost();
        $dataForTable = $this->appointmentModel->getAllAppointments($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function getDataById()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('getAppointmentData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $cleanData = $this->request->getPost();
                    $fetchData = $this->appointmentModel->getDataById($cleanData['id']);
                    if ($fetchData !== false) {
                        return $this->response->setJSON($this->_arrayFormat(true, 'Successfully fetched data.', $fetchData));
                    } else {
                        return $this->response->setJSON($this->_arrayFormat(false, 'Appointment data not found', ''));
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

    public function create()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('createAppointment');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'patient_id' => 'required',
                        'test_id' => 'required',
                        'doctor_id' => 'required',
                        'appointment_date' => 'required',
                        'expected_date' => 'required',
                        'note' => 'max_length[400]',
                        'amount' => 'required',
                        'paid' => 'required',
                        'due' => 'required',
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $create = $this->appointmentModel->createAppointments($cleanData);
                        if ($create) {
                            return $this->response->setJSON($this->_arrayFormat(true, 'Successfully created!.', ['action' => 'success']));
                        } else {
                            return $this->response->setJSON($this->_arrayFormat(false, 'Created error', ''));
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

    public function update()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('updateAppointment');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'patient_id' => 'required',
                        'test_id' => 'required',
                        'doctor_id' => 'required',
                        'appointment_date' => 'required',
                        'expected_date' => 'required',
                        'note' => 'max_length[400]',
                        'amount' => 'required',
                        'status' => 'required',
                        'paid' => 'required',
                        'due' => 'required',
                        'appointment_id' => 'required'
                    ];
                    $files = $_FILES['media_files'];
                    $userIp = $this->request->getIPAddress();
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $update = $this->appointmentModel->updateAppointments($cleanData, $files, $userIp);
                        if ($update) {
                            return $this->response->setJSON($this->_arrayFormat(true, 'Update success!', ['action' => 'success']));
                        } else {
                            return $this->response->setJSON($this->_arrayFormat(false, 'Updated error', $update));
                        }
                    }
                } else {
                    return $this->response->setJSON($this->_arrayFormat(false, 'Request Error', ''));
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return $this->response->setJSON($this->_arrayFormat(false, 'Permission denied', ['action' => 'redirect', 'url' => 'admin/dashboard']));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return $this->response->setJSON($this->_arrayFormat(false, 'Session expired', ['action' => 'redirect', 'url' => 'admin/login']));
        }
    }

    public function delete()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('deleteAppointment');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'id' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $this->validator->setError('id', 'The Appointment Id is missing.');
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'The Appointment Id is missing.', ''));
                    } else {
                        $cleanData = $this->request->getPost();
                        $delete = $this->appointmentModel->deleteAppointments($cleanData['id']);
                        if ($delete) {
                            $data['action'] = 'success';
                            return $this->response->setJSON($this->_arrayFormat(true, 'Successfully deleted!', $data));
                        } else {
                            return $this->response->setJSON($this->_arrayFormat(false, 'Delete error', ''));
                        }
                    }
                } else {
                    return $this->response->setJSON($this->_arrayFormat(false, 'Request Error', ''));
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return $this->response->setJSON($this->_arrayFormat(false, 'Permission denied', ['action' => 'redirect', 'url' => 'admin/dashboard']));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return $this->response->setJSON($this->_arrayFormat(false, 'Session expired', ['action' => 'redirect', 'url' => 'admin/login']));
        }
    }

    private function _arrayFormat($status, $message, $data)
    {
        return ['status' => $status, 'message' => $message, 'data' => $data];
    }

}