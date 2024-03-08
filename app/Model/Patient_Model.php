<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Model\Common_Model;
use Exception;
class Patient_Model extends Model
{
    protected $common_model;
    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
    }

    // Retrieves all patients with optional filtering and pagination for DataTables
    public function getAllPatients($postData = null)
    {

    }
}