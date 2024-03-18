<?php

namespace App\Controllers;

use CodeIgniter\RESTful\Controller;

use App\Models\RolePermission_Model;
use App\Models\Permissions_Model;
use App\Models\Roles_Model;
use App\Models\UserModel;
use App\Models\Auth_Model;
use App\Libraries\UserSession;

use App\Libraries\Password;

class User extends BaseController
{
    private $rolesModel;
    private $permissionModel;
    private $rolePermissionModel;
    private $userModel;
    private $userSession;
    private $password_lib;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->auth_model = new Auth_Model;
        $this->data = ['session' => $this->session];

        $this->rolePermissionModel = new RolePermission_Model();
        $this->permissionModel = new Permissions_Model();
        $this->userModel = new UserModel();
        $this->rolesModel = new Roles_Model();
        $this->userSession = new UserSession();
        $this->password_lib = new Password();

        helper(['form']);
    }

    public function index(){
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allUsers');
            if ($hasPermission['hasPermission']) {
                $title = 'Add User' . ' | ' . WEBSITE_NAME;
                $data = [
                    'title' => $title,
                    'breadcrumb' => 'All Users',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'userRoles' => $this->rolePermissionModel->getAllRolesByCurrentUserId()
                ];
                return view('Admin/users', $data);
            } else {
                session()->setFlashdata('error', $hasPermission['error']);
                return redirect()->to(base_url('admin/dashboard'));
            }
        } else {
            session()->setFlashdata('error', 'Session expired!');
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function allUsersData()
    {
        $postData = $this->request->getPost();
        $dataForTable = $this->userModel->getAllAuthUsers($postData);
        return $this->response->setJSON($dataForTable);
    }

    public function createUserData()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('createUserData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {

                    $rules = [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'gender' => 'required',
                        'username' => 'required|is_unique[users.username]',
                        'password' => 'required',
                        'phone' => 'required',
                        'user_role' => 'required',
                        'active' => 'required'
                    ];
                    $messages = [
                        'is_unique' => 'The {field} field must be unique.'
                    ];
                    if (!$this->validate($rules, $messages)) {
                        return $this->response->setJSON(['error_data' => $this->validator->getErrors(), 'action' => 'error']);
                    } else {
                        $cleanData = $this->request->getPost();
                        $userRoleId = $cleanData['user_role'];
                        unset($cleanData["user_role"]);
                        unset($cleanData["passconf"]);
                        $hashed = $this->password_lib->create_hash($cleanData["password"]);
                        $cleanData["password"] = $hashed;
                        $createUser = $this->userModel->createAdminUser($cleanData);
                        $roleUpdate = false;
                        if ($createUser['success']) {
                            $createdUserId = $createUser['data']['user_id'];
                            $roleUpdate = $this->rolePermissionModel->createUserRoles($createdUserId, $userRoleId);
                        }
                        if ($createUser['success'] && $roleUpdate) {
                            $data['action'] = 'success';
                            $dataReturn = $this->_arrayFormat(true, 'Successfully created!.', $data);
                            return $this->response->setJSON($dataReturn);
                        } else {
                            $dataReturn = $this->_arrayFormat(false, 'User updated error', '');
                            return $this->response->setJSON($dataReturn);
                        }
                    }
                } else {
                    $dataReturn = $this->_arrayFormat(false, 'Request Error', '');
                    return $this->response->setJSON($dataReturn);
                }
            } else {
                session()->setFlashdata('error', $hasPermission['error']);
                $data['action'] = 'redirect'; $data['url'] = 'admin/dashboard';
                $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
                return $this->response->setJSON($dataReturn);
            }
        } else {
            session()->setFlashdata('error', 'Session expired!');
            $data['action'] = 'redirect'; $data['url'] = 'admin/login';
            $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
            return $this->response->setJSON($dataReturn);
        }
    }

    public function getUserDataById()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('createUserData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $cleanData = $this->request->getPost();
                    $userData = $this->userModel->getUserDataById($cleanData['user_id']);
                    if ($userData !== false) {
                        $dataReturn = $this->_arrayFormat(true, 'Successfully fetched data.', $userData);
                        return $this->response->setJSON($dataReturn);
                    } else {
                        $dataReturn = $this->_arrayFormat(false, 'User data not found', '');
                        return $this->response->setJSON($dataReturn);
                    }
                } else {
                    $dataReturn = $this->_arrayFormat(false, 'Request Error', '');
                    return $this->response->setJSON($dataReturn);
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                $data['action'] = 'redirect';
                $data['url'] = 'admin/dashboard';
                $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
                return $this->response->setJSON($dataReturn);
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            $data['action'] = 'redirect';
            $data['url'] = 'admin/login';
            $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
            return $this->response->setJSON($dataReturn);
        }
    }


    public function updateUserData()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('updateUserData');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'user_id' => 'required',
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'gender' => 'required',
                        'phone' => 'required',
                        'user_role' => 'required',
                        'active' => 'required'
                    ];

                    if (!$this->validate($rules)) {
                        $data['error_data'] = $this->validator->getErrors();
                        $data['action'] = 'error';
                        $dataReturn = $this->_arrayFormat(false, 'Some field has error.', $data);
                        return $this->response->setJSON($dataReturn);
                    }

                    $cleanData = $this->request->getPost();
                    $userRoleId = $cleanData['user_role'];
                    unset($cleanData["user_role"]);
                    unset($cleanData["passconf"]);

                    if (!empty($cleanData["password"])) {
                        $hashedPassword = password_hash($cleanData["password"], PASSWORD_DEFAULT);
                        $cleanData["password"] = $hashedPassword;
                    }

                    $updateUser = $this->userModel->updateAdminUser($cleanData);
                    $updateUserRole = $this->rolePermissionModel->updateUserRole($cleanData['user_id'], $userRoleId);

                    if ($updateUser) {// || $updateUserRole
                        $data['action'] = 'success';
                        $dataReturn = $this->_arrayFormat(true, 'Successfully updated!', $data);
                        return $this->response->setJSON($dataReturn);
                    } else {
                        $dataReturn = $this->_arrayFormat(false, 'User update error', $updateUser);
                        return $this->response->setJSON($dataReturn);
                    }
                } else {
                    $dataReturn = $this->_arrayFormat(false, 'Request Error', '');
                    return $this->response->setJSON($dataReturn);
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                $data['action'] = 'redirect';
                $data['url'] = 'admin/dashboard';
                $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
                return $this->response->setJSON($dataReturn);
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            $data['action'] = 'redirect';
            $data['url'] = 'admin/login';
            $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
            return $this->response->setJSON($dataReturn);
        }
    }


    public function deleteUser()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('deleteUser');
            if ($hasPermission['hasPermission']) {
                if ($this->request->isAJAX()) {
                    // Set validation rules
                    $rules = [
                        'user_id' => 'required'
                    ];

                    if (!$this->validate($rules)) {
                        $dataReturn = $this->_arrayFormat(false, $this->validator->getErrors(), '');
                        return $this->response->setJSON($dataReturn);
                    } else {
                        $cleanData = $this->request->getPost();
                        $deleteUser = $this->userModel->deleteAdminUser($cleanData['user_id']);
                        $deleteUserChangeRoleToNormal = $this->rolePermissionModel->updateUserRole($cleanData['user_id'], 4);
                        if ($deleteUser) {
                            $data['action'] = 'success';
                            $dataReturn = $this->_arrayFormat(true, 'Successfully deleted!', $data);
                            return $this->response->setJSON($dataReturn);
                        } else {
                            $dataReturn = $this->_arrayFormat(false, 'User delete error', '');
                            return $this->response->setJSON($dataReturn);
                        }
                    }
                } else {
                    $dataReturn = $this->_arrayFormat(false, 'Request Error', '');
                    return $this->response->setJSON($dataReturn);
                }
            } else {
                $this->session->setFlashdata("error", $hasPermission['error']);
                $data['action'] = 'redirect';
                $data['url'] = 'admin/dashboard';
                $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
                return $this->response->setJSON($dataReturn);
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            $data['action'] = 'redirect';
            $data['url'] = 'admin/login';
            $dataReturn = $this->_arrayFormat(false, 'Session expired', $data);
            return $this->response->setJSON($dataReturn);
        }
    }



//
//
//    public function save()
//    {
//        $validationRules = [
//            'first_name' => 'required',
//            'last_name' => 'required',
//            'gender' => 'required',
//            'username' => 'required|is_unique[users.username]',
//            'password' => 'required',
//            'email' => 'valid_email',
//            'phone' => 'required',
//            'user_role' => 'required',
//            'active' => 'required'
//        ];
//
//        if ($this->validate($validationRules)) {
//            $data = [
//                'first_name' => $this->request->getPost('first_name'),
//                'last_name' => $this->request->getPost('last_name'),
//                'gender' => $this->request->getPost('gender'),
//                'username' => $this->request->getPost('username'),
//                'password' => $this->request->getPost('password'),
//                'email' => $this->request->getPost('email'),
//                'phone' => $this->request->getPost('phone'),
//                'user_role' => $this->request->getPost('user_role'),
//                'active' => $this->request->getPost('active')
//            ];
//
//            $this->userModel->insert($data);
//
//            return $this->response->setJSON(['status' => 'success', 'message' => 'User created successfully']);
//        } else {
//            return $this->response->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
//        }
//    }

    public function update($id)
    {
        $validationRules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'username' => 'required',
            'password' => 'required',
            'email' => 'valid_email',
            'phone' => 'required',
            'user_role' => 'required',
            'active' => 'required'
        ];

        if ($this->validate($validationRules)) {
            $data = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'gender' => $this->request->getPost('gender'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'user_role' => $this->request->getPost('user_role'),
                'active' => $this->request->getPost('active')
            ];

            $this->userModel->update($id, $data);

            return $this->response->setJSON(['status' => 'success', 'message' => 'User updated successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'errors' => $this->validator->getErrors()]);
        }
    }

    public function delete($id)
    {
        $this->userModel->delete($id);

        return $this->response->setJSON(['status' => 'success', 'message' => 'User deleted successfully']);
    }


    private function _arrayFormat($status, $message, $data){
        return array('status' => $status, 'message' => $message, 'data' => $data);
    }




}