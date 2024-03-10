<?php
namespace App\Models;

use App\Libraries\Password;
use CodeIgniter\Model;
use CodeIgniter\Model\Common_Model;
use Exception;


class Appointment_Model extends Model
{
    protected $common_model;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
        $this->password_lib = new Password();
        $this->userModel = new UserModel();
    }

    public function getAllAppointments($postData = null)
    {
    }
}