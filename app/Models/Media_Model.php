<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;


class Media_Model extends Model
{
    protected $common_model;
    private $userModel;
    private $fileUploadModel;

    public function __construct()
    {
        parent::__construct();
        $this->common_model = new Common_Model();
        $this->password_lib = new Password();
        $this->userModel = new UserModel();
        $this->fileUploadModel = new FileUpload_Model();
    }



}