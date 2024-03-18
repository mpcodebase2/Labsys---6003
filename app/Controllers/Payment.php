<?php

namespace App\Controllers;
use App\Libraries\UserSession;
use App\Models\Common_Model;
use App\Models\Appointment_Model;


class Payment extends BaseController
{
    private $session;
    private $userSession;
    protected $common_model;
    private $appointmentModel;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->userSession = new UserSession();
        $this->common_model = new Common_Model();
        $this->appointmentModel = new Appointment_Model();
        helper(['form']);
    }

    public function index($variable)
    {
        if ($this->userSession->isLoggedIn()) {
            $decode_id = $this->common_model->decode_url($variable);
            $dataAppointment = $this->appointmentModel->getAppointmentDataById($decode_id);
            if(isset($dataAppointment['paid']) && $dataAppointment['paid'] <= 0){
                return redirect()->to(base_url('thankyou'));
            }else{
                $data = [
                    'breadcrumb' => 'Payment',
                    'islogin' => $this->userSession->isLoggedIn(),
                    'title' => 'Payment',
                    'testName' => $dataAppointment['test_name'],
                    'amount' => $dataAppointment['paid'],
                    'id' => $decode_id
                ];
                echo view('Payment/test_payment', $data);
            }
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('login'));
        }
    }

    public function thankyou()
    {
        if ($this->userSession->isLoggedIn()) {
            $data = [
                'breadcrumb' => 'Thank you',
                'islogin' => $this->userSession->isLoggedIn(),
                'title' => 'Thank you'
            ];
            echo view('Payment/thankyou', $data);
        } else {
            $this->session->setFlashdata("error", "Session expired!");
            return redirect()->to(base_url('login'));
        }
    }
}