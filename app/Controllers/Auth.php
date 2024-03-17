<?php
namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Permissions_Model;
use App\Models\Roles_Model;
use App\Models\UserModel;
use App\Models\Auth_Model;

use App\Libraries\Password;
use App\Libraries\UserSession;

class Auth extends BaseController
{
    private $status;
    private $roles;
    private $rolesModel;
    private $permissionModel;
    private $rolePermissionModel;
    private $userModel;
    private $password_lib;

    private $userSession;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->auth_model = new Auth_Model;
        $this->data = ['session' => $this->session];

        $this->rolePermissionModel = new RolePermission_Model();
        $this->permissionModel = new Permissions_Model();
        $this->userModel = new UserModel();
      //  $this->rolesModel = new Roles_Model();

        $this->password_lib = new Password();

        $this->userSession = new UserSession();

        helper(['form']);
    }

    public function index()
    {
        $data = ['islogin' => false];
        return view('Auth/login', $data);
//        if (!$this->user->isLoggedIn()) {
//            $data = [
//                'islogin' => $this->user->isLoggedIn(),
//            ];
//            return view('auth/login', $data);
//        } else {
//            return redirect()->to('/dashboard');
//        }
    }

    public function regsiter()
    {
        return view('Auth/public_register');
    }
    public function login()
    {
        return view('Auth/login');
    }
    // Function for regsiter user Post method
    public function _regsiter()
    {
        return view('Auth/registration');
    }
    // Function for login user Post method
    public function loginRequest()
    {
        $validationRules = [
            'username' => 'required',
            'password' => 'required'
        ];
        $validationMessages = [
            'username' => [
                'required' => 'The username field is required.'
            ],
            'password' => [
                'required' => 'The password field is required.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $this->session->setFlashdata("error", $this->validator->listErrors());
            return redirect()->to(site_url('admin/login'));
        } else {
            $loginCredintials = $this->request->getPost();
            $user = $this->userModel->userExist($loginCredintials['username']);

            if ($user) {
                $userInfo = $this->userModel->checkLogin($loginCredintials);

                if ($userInfo) {
                    $this->userModel->resetLoginAttempts($user->id);
                    unset($userInfo->password);
                    $this->setSession($userInfo);
                    return redirect()->to(site_url('admin/dashboard'));
                } else {
                    $loginAttempt = $this->userModel->checkLoginAttempt($user->id);

                    if ($loginAttempt) {
                        if ($loginAttempt->attempt_count >= MAX_LOGIN_ATTEMPTS) {
                            $this->session->setFlashdata('error', 'Your account has been locked. Please contact admin.');
                            return redirect()->to(site_url('admin/login'));
                        } else {
                            $this->userModel->updateLoginAttempts($user->id, $loginAttempt->attempt_count);
                        }
                    } else {
                        $this->userModel->createLoginAttempts($user->id);
                    }

                    $this->session->setFlashdata('error', 'Invalid username or password');
                    return redirect()->to(site_url('admin/login'));
                }
            } else {
                $this->session->setFlashdata('error', 'Invalid username or password');
                return redirect()->to(site_url('admin/login'));
            }
        }
    }

    private function setSession($userInfo)
    {
        $data = [
            "firstName" => $userInfo->first_name,
            "lastName" => $userInfo->last_name,
            "email" => $userInfo->email,
            "user_id" => $userInfo->id,
            "role" => $userInfo->role_name,
            "role_id" => $userInfo->role_id,
            "islogin" => $this->userSession->isLoggedIn()
        ];

        foreach ($data as $key => $val) {
            session()->set($key, $val);
        }
    }


    public function allUsersData()
    {
        // Your allUsersData method logic goes here
    }

    public function getUserDataById()
    {
        // Your getUserDataById method logic goes here
    }

    public function createUserData()
    {
        // Your createUserData method logic goes here
    }

    public function updateUserData()
    {
        // Your updateUserData method logic goes here
    }

    public function deleteUser()
    {
        // Your deleteUser method logic goes here
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('admin/login');
    }

    private function _arrayFormat($status, $message, $data)
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    public function createPPassword(){

        $password = 'Test@123';//$this->uri->segment(3);
        $hashed = $this->password_lib->create_hash($password);
        echo $hashed;
//        $data = ['password' => $hashed];
//        return view('temp/password', $data);
    }
}
