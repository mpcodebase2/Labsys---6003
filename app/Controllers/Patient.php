<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Patient_Model;
use App\Libraries\UserSession;

use App\Models\GetData_Model;

class Patient extends BaseController
{
    private $patientModel;
    private $rolePermissionModel;
    private $userSession;
    private $getData;

    private $session;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();


        $this->rolePermissionModel = new RolePermission_Model();
        $this->patientModel = new Patient_Model();
        $this->userSession = new UserSession();

        $this->getData = new GetData_Model();

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allPatient');
            if ($hasPermission['hasPermission']) {
                $data = [
                    'breadcrumb' => 'All Patient',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'title' => 'All Patient',
                    'city' => $this->getData->getCity(),
                    'district' => $this->getData->getDistrict(),
                    'province' => $this->getData->getProvince(),
                    'country' => $this->getData->getCountry(),
                ];
                echo view('Patients/all_patient', $data);
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function allPatientData()
    {
        $postData = $this->request->getPost();
        $dataForTable = $this->patientModel->getAllPatients($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function getDataById()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('getPatientData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $cleanData = $this->request->getPost();
                    $fetchData = $this->patientModel->getDataById($cleanData['id']);
                    if ($fetchData !== false) {
                        return $this->response->setJSON($this->_arrayFormat(true, 'Successfully fetched data.', $fetchData));
                    } else {
                        return $this->response->setJSON($this->_arrayFormat(false, 'Patient data not found', ''));
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('createPatient');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'email' => 'required|is_unique[users.email]',
                        'nic' => 'required|max_length[15]|is_unique[users.nic]',
                        'first_name' => 'required|max_length[100]',
                        'last_name' => 'required|max_length[100]',
                        'gender' => 'required',
                        'dob' => 'required',
                        'address_ln1' => 'required|max_length[255]',
                        'address_ln2' => 'max_length[255]',
                        'city' => 'required',
                        'district' => 'required',
                        'province' => 'required',
                        'country' => 'required',
                        'telephone' => 'required|max_length[12]',
                        'mobile' => 'max_length[12]',
                        'occupation' => 'max_length[100]',
                        'is_active' => 'required',
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $create = $this->patientModel->createPatients($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('updatePatient');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'email' => 'required|is_unique[users.email]',
                        'nic' => 'required|max_length[15]|is_unique[users.nic]',
                        'first_name' => 'required|max_length[100]',
                        'last_name' => 'required|max_length[100]',
                        'gender' => 'required',
                        'dob' => 'required',
                        'address_ln1' => 'required|max_length[255]',
                        'address_ln2' => 'max_length[255]',
                        'city' => 'required',
                        'district' => 'required',
                        'province' => 'required',
                        'country' => 'required',
                        'telephone' => 'required|max_length[12]',
                        'mobile' => 'max_length[12]',
                        'occupation' => 'max_length[100]',
                        'is_active' => 'required',
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $update = $this->patientModel->updatePatients($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('deletePatient');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'id' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $this->validator->setError('id', 'The Patient Id is missing.');
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'The Patient Id is missing.', ''));
                    } else {
                        $cleanData = $this->request->getPost();
                        $delete = $this->patientModel->deletePatients($cleanData['id']);
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