<?php
namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Permissions_Model;
use App\Models\Roles_Model;
use App\Models\User_Model;
use App\Models\Auth_Model;

use App\Libraries\Password;

class Auth extends BaseController
{
    private $status;
    private $roles;
    private $rolesModel;
    private $permissionModel;
    private $rolePermissionModel;
    private $userModel;
    private $password_lib;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->auth_model = new Auth_Model;
        $this->data = ['session' => $this->session];

        $this->rolePermissionModel = new RolePermission_Model();
        $this->permissionModel = new Permissions_Model();
        $this->userModel = new User_Model();
      //  $this->rolesModel = new Roles_Model();

        $this->password_lib = new Password();

        helper(['form']);
    }

    public function index()
    {
        $data = ['islogin' => false, 'password' => $this->createPPassword()];
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
        return view('Auth/registration');
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
    public function _login()
    {
        return view('Auth/login');
    }

    public function allUsers()
    {
        // Your allUsers method logic goes here
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
        return redirect()->to('/login');
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

        $password = 'PPP@i99292';//$this->uri->segment(3);
        $hashed = $this->password_lib->create_hash($password);
        echo $hashed;
//        $data = ['password' => $hashed];
//        return view('temp/password', $data);
    }
}
