<?php
namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Permissions_Model;
use App\Models\Patient_Model;
use App\Models\UserModel;
use App\Models\Auth_Model;

use App\Libraries\Password;
use App\Libraries\UserSession;
use App\Models\GetData_Model;

class PublicAuthentication extends BaseController
{
    private $status;
    private $roles;
    private $rolesModel;
    private $permissionModel;
    private $rolePermissionModel;
    private $userModel;
    private $password_lib;
    private $getData;
    private $userSession;
    private $patientModel;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->auth_model = new Auth_Model;
        $this->data = ['session' => $this->session];

        $this->rolePermissionModel = new RolePermission_Model();
        $this->permissionModel = new Permissions_Model();
        $this->userModel = new UserModel();
        $this->password_lib = new Password();
        $this->userSession = new UserSession();
        $this->patientModel = new Patient_Model();

        $this->getData = new GetData_Model();

        helper(['form']);
    }

    /*
     * Login for Public
     */
    public function index()
    {
        if (!$this->userSession->isLoggedIn()) {
            $data = [
                'islogin' => $this->userSession->isLoggedIn(),
            ];
            return view('Auth/public_login', $data);
        } else {
            return redirect()->to('/');
        }
    }

    public function register()
    {
        if (!$this->userSession->isLoggedIn()) {
            $data = [
                'islogin' => $this->userSession->isLoggedIn(),
            ];
            return view('Auth/public_register', $data);
        } else {
            return redirect()->to('/');
        }
    }

    public function forget(){
        if (!$this->userSession->isLoggedIn()) {
            $data = [
                'islogin' => $this->userSession->isLoggedIn(),
            ];
            return view('Auth/public_forget', $data);
        } else {
            return redirect()->to('/');
        }
    }


    public function loginRequest()
    {
        $validationRules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $validationMessages = [
            'email' => [
                'required' => 'The email field is required.'
            ],
            'password' => [
                'required' => 'The password field is required.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            $this->session->setFlashdata("error", $this->validator->listErrors());
            return redirect()->to(site_url('login'));
        } else {
            $loginCredintials = $this->request->getPost();
            $user = $this->userModel->userExistByEmail($loginCredintials['email']);

            if ($user) {
                $userInfo = $this->userModel->checkLoginByEmail($loginCredintials);

                if ($userInfo) {
                    $this->userModel->resetLoginAttempts($user->id);
                    unset($userInfo->password);
                    $this->setSession($userInfo);
                    return redirect()->to(site_url('/'));
                } else {
                    $loginAttempt = $this->userModel->checkLoginAttempt($user->id);

                    if ($loginAttempt) {
                        if ($loginAttempt->attempt_count >= MAX_LOGIN_ATTEMPTS) {
                            $this->session->setFlashdata('error', 'Your account has been locked. Please contact admin.');
                            return redirect()->to(site_url('login'));
                        } else {
                            $this->userModel->updateLoginAttempts($user->id, $loginAttempt->attempt_count);
                        }
                    } else {
                        $this->userModel->createLoginAttempts($user->id);
                    }

                    $this->session->setFlashdata('error', 'Invalid email or password');
                    return redirect()->to(site_url('login'));
                }
            } else {
                $this->session->setFlashdata('error', 'Invalid email or password');
                return redirect()->to(site_url('login'));
            }
        }
    }

    public function registerRequest()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|is_unique[users.email]',
            'phone' => 'required',
            'password' => 'required'
        ];
        $messages = [
            'is_unique' => 'The {field} field must be unique.'
        ];
        if (!$this->validate($rules, $messages)) {
            $this->session->setFlashdata("error", $this->validator->listErrors());
            return redirect()->to(site_url('sign-up'));
        } else {
            $cleanData = $this->request->getPost();
            $hashed = $this->password_lib->create_hash($cleanData["password"]);
            $cleanData["password"] = $hashed;
            $createUser = $this->userModel->userRegistration($cleanData);
            $roleUpdate = false;
            if ($createUser['success']) {
                $createdUserId = $createUser['data']['user_id'];
                $roleUpdate = $this->rolePermissionModel->createUserRoles($createdUserId, ROLE_ID_PATIENT);
            }
            if ($createUser['success'] && $roleUpdate) {
                return redirect()->to(site_url('/'));
            } else {
                $this->session->setFlashdata("error", 'Something went wrong!');
                return redirect()->to(site_url('sign-up'));
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

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('login');
    }

    public function createPatientProfile(){
        if ($this->userSession->isLoggedIn()) {
            $data = [
                'islogin' => $this->userSession->isLoggedIn(),
                'city' => $this->getData->getCity(),
                'district' => $this->getData->getDistrict(),
                'province' => $this->getData->getProvince(),
                'country' => $this->getData->getCountry(),
            ];
            return view('Auth/public_create_patient_profile', $data);
        } else {
            return redirect()->to('/');
        }
    }

    public function createPatientProfileAPI(){
        if ($this->userSession->isLoggedIn()) {
            $userId = (session('user_id'))?:false;
            $hasPermission = $this->rolePermissionModel->userHasPermission('createPatient');
            if ($hasPermission['hasPermission'] && $userId) {
                if ($this->request->isAJAX()) {
                    $rules = [
                        'nic' => 'required|max_length[15]|is_unique[users.nic]',
                        'gender' => 'required',
                        'dob' => 'required',
                        'address_ln1' => 'required|max_length[255]',
                        'address_ln2' => 'max_length[255]',
                        'city' => 'required',
                        'district' => 'required',
                        'province' => 'required',
                        'country' => 'required',
                        'telephone' => 'required|max_length[12]',
                        'mobile' => 'required|max_length[12]',
                        'occupation' => 'max_length[100]',
                        'religion' => 'required',
                        'nationality' => 'required'
                    ];
                    if (!$this->validate($rules)) {
                        $errors = $this->validator->getErrors();
                        return $this->response->setJSON($this->_arrayFormat(false, 'Some field has error.', ['action' => 'error', 'error_data' => $errors]));
                    } else {
                        $cleanData = $this->request->getPost();
                        $create = $this->patientModel->createPatientsProfile($cleanData, $userId);
                        if ($create) {
                            return $this->response->setJSON($this->_arrayFormat(true, 'Successfully created!.', ['action' => 'redirect','url'=> 'create-appointment' ]));
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

    private function _arrayFormat($status, $message, $data)
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}