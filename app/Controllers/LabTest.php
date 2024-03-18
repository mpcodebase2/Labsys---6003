<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\LabTest_Model;
use App\Libraries\UserSession;


class LabTest extends BaseController
{
    private $labTestModel;
    private $rolePermissionModel;
    private $userSession;

    private $session;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();


        $this->rolePermissionModel = new RolePermission_Model();
        $this->labTestModel = new LabTest_Model();
        $this->userSession = new UserSession();

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allLabTest');
            if ($hasPermission['hasPermission']) {
                $data = [
                    'breadcrumb' => 'All LabTest',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'title' => 'All LabTest'
                ];
                echo view('Admin/all_labTest', $data);
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function allLabTestData()
    {
        $postData = $this->request->getPost();
        $dataForTable = $this->labTestModel->getAllLabTest($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function getDataById()
    {
//        if ($this->userSession->isLoggedIn()) {
//            $hasPermission = $this->rolePermissionModel->userHasPermission('getLabTestData');
//            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $cleanData = $this->request->getPost();
                    $fetchData = $this->labTestModel->getDataById($cleanData['id']);
                    if ($fetchData !== false) {
                        return $this->response->setJSON($this->_arrayFormat(true, 'Successfully fetched data.', $fetchData));
                    } else {
                        return $this->response->setJSON($this->_arrayFormat(false, 'LabTest data not found', ''));
                    }
                } else {
                    return $this->response->setJSON($this->_arrayFormat(false, 'Request Error', ''));
                }
//            } else {
//                $this->session->setFlashdata("error", $hasPermission['error']);
//                return $this->response->setJSON($this->_arrayFormat(false, 'Session expired', ['action' => 'redirect', 'url' => 'admin/dashboard']));
//            }
//        } else {
//            $this->session->setFlashdata("error", "Session expired!");
//            return $this->response->setJSON($this->_arrayFormat(false, 'Session expired', ['action' => 'redirect', 'url' => 'admin/login']));
//        }
    }

    public function create()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('createLabTest');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'name' => 'required',
                        'cost' => 'required',
                        'active' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $create = $this->labTestModel->createLabTest($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('updateLabTest');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'name' => 'required',
                        'cost' => 'required',
                        'active' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $update = $this->labTestModel->updateLabTest($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('deleteLabTest');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'id' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $this->validator->setError('id', 'The LabTest Id is missing.');
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'The LabTest Id is missing.', ''));
                    } else {
                        $cleanData = $this->request->getPost();
                        $delete = $this->labTestModel->deleteLabTest($cleanData['id']);
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