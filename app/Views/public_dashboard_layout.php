<?php

namespace App\Controllers;

use App\Models\RolePermission_Model;
use App\Models\Appointment_Model;
use App\Libraries\UserSession;

use App\Models\GetData_Model;
use App\Models\UserModel;

class PublicAppointment extends BaseController
{
    private $appointmentModel;
    private $rolePermissionModel;
    private $userSession;
    private $getData;



    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();


        $this->rolePermissionModel = new RolePermission_Model();
        $this->appointmentModel = new Appointment_Model();
        $this->userSession = new UserSession();

        helper(['form']);
    }

    public function index()
    {
        if ($this->userSession->isLoggedIn()) {
            $hasPermission = $this->rolePermissionModel->userHasPermission('allAppointment');
            if ($hasPermission['hasPermission']) {}
            
        } else {
           
        }
    }
}