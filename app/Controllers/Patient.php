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

    

}