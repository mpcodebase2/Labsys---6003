<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Permissions_Model;
use App\Models\Roles_Model;
use App\Libraries\UserSession;

class Permission extends BaseController
{
    private $permissionModel;
    private $rolesModel;
    private $rolePermissionModel;
    private $userSession;
    private $session;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();


        $this->rolePermissionModel = new RolePermission_Model();
        $this->permissionModel = new Permissions_Model();
        $this->rolesModel = new Roles_Model();
        $this->userSession = new UserSession();

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allPermission');
            if ($hasPermission['hasPermission']) {
                $data = [
                    'breadcrumb' => 'All Permission',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'title' => 'All Permission'
                ];
                return view('Admin/all_permission', $data);
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function allPermissionData()
    {
        $postData = $this->request->getPost();
        $dataForTable = $this->permissionModel->getAllPermission($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function getDataById()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('getPermissionData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $cleanData = $this->request->getPost();
                    $fetchData = $this->permissionModel->getDataById($cleanData['id']);
                    if ($fetchData !== false) {
                        return $this->response->setJSON($this->_arrayFormat(true, 'Successfully fetched data.', $fetchData));
                    } else {
                        return $this->response->setJSON($this->_arrayFormat(false, 'Permission data not found', ''));
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('createPermission');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'name' => 'required|is_unique[permissions.name]',
                        'active' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $create = $this->permissionModel->createPermission($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('updatePermission');
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
                        $update = $this->permissionModel->updatePermission($cleanData);
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
            $hasPermission = $this->rolePermissionModel->userHasPermission('deletePermission');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'id' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $this->validator->setError('id', 'The Permission Id is missing.');
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'The Permission Id is missing.', ''));
                    } else {
                        $cleanData = $this->request->getPost();
                        $delete = $this->permissionModel->deletePermission($cleanData['id']);
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


    public function assignPermissionToRole() {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allPermission');

            if ($hasPermission['hasPermission']) {
                $data = [
                    'breadcrumb' => 'Assign Permissions',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'roles' => $this->rolesModel->getDataAll(),
                    'permissions' => $this->permissionModel->getDataAll()
                ];

                return view('admin/assign_permissions', $data);
            } else {
                return redirect()->to(base_url('dashboard'))->with('error', $hasPermission['error']);
            }
        } else {
            return redirect()->to(base_url('login'))->with('error', 'Session expired!');
        }
    }

    public function getRolePermissions() {
        $role_id = $this->request->getGet('role_id');
        $permissions = $this->rolePermissionModel->getRolePermissions($role_id);
        return $this->response->setJSON($permissions);
    }

    public function assignRolePermissions() {
        $role_id = $this->request->getPost('role');
        $permission_ids = $this->request->getPost('permission_id');

        if ($role_id && isset($permission_ids) && count($permission_ids) > 0) {
            $this->rolePermissionModel->deleteRolePermissions($role_id);
            $update = $this->rolePermissionModel->assignRolePermissions($permission_ids, $role_id);
            $response = $this->_arrayFormat($update, 'Role permissions saved successfully', '');
        } else {
            $response = $this->_arrayFormat(false, 'Select at least one permission', '');
        }

        return $this->response->setJSON($response);
    }


    private function _arrayFormat($status, $message, $data)
    {
        return ['status' => $status, 'message' => $message, 'data' => $data];
    }

}