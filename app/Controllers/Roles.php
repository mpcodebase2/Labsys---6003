<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Roles_Model;
use App\Libraries\UserSession;


class Roles extends BaseController
{
    private $rolesModel;
    private $rolePermissionModel;
    private $userSession;

    private $session;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();


        $this->rolePermissionModel = new RolePermission_Model();
        $this->rolesModel = new Roles_Model();
        $this->userSession = new UserSession();

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allRoles');
            if ($hasPermission['hasPermission']) {
                $data = [
                    'breadcrumb' => 'All Roles',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'title' => 'All Roles'
                ];
                echo view('Admin/all_roles', $data);
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function allRolesData()
    {
        $postData = $this->request->getPost();
        $dataForTable = $this->rolesModel->getAllRoles($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function getDataById()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('getRolesData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $cleanData = $this->request->getPost();
                    $fetchData = $this->rolesModel->getDataById($cleanData['id']);
                    if ($fetchData !== false) {
                        return $this->response->setJSON($this->_arrayFormat(true, 'Successfully fetched data.', $fetchData));
                    } else {
                        return $this->response->setJSON($this->_arrayFormat(false, 'Roles data not found', ''));
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('createRoles');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'name' => 'required|is_unique[roles.name]',
                        'active' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $create = $this->rolesModel->createRoles($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('updateRoles');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'active' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $update = $this->rolesModel->updateRoles($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('deleteRoles');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'id' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $this->validator->setError('id', 'The Roles Id is missing.');
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'The Roles Id is missing.', ''));
                    } else {
                        $cleanData = $this->request->getPost();
                        $delete = $this->rolesModel->deleteRoles($cleanData['id']);
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