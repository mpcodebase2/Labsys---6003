<?php

namespace App\Controllers;

use App\Libraries\UserSession;
use App\Models\GetData_Model;
class Home extends BaseController
{
    private $user;
    private $getData;

    public function __construct()
    {
        $this->user = new UserSession();
        $this->getData = new GetData_Model();
    }
    public function index()
    {
        $dashboardData = $this->getData->getDashboardData();
        $isLoggedIn = $this->user->isLoggedIn();
        if($isLoggedIn) {
            return view('Admin/dashboard', [
                'totalAppointments' => $dashboardData['totalAppointments'],
                'totalPatients' => $dashboardData['totalPatients'],
                'totalIncome' => $dashboardData['totalIncome'],
                'paidIncome' => $dashboardData['paidIncome'],
                'balanceIncome' => $dashboardData['balanceIncome']
            ]);
        }else
            return redirect()->to(site_url('admin/login'));
    }


    public function dashboard()
    {
        $dashboardData = $this->getData->getDashboardData();
        $isLoggedIn = $this->user->isLoggedIn();
        if($isLoggedIn) {
            return view('Admin/dashboard', [
                'totalAppointments' => $dashboardData['totalAppointments'],
                'totalPatients' => $dashboardData['totalPatients'],
                'totalIncome' => $dashboardData['totalIncome'],
                'paidIncome' => $dashboardData['paidIncome'],
                'balanceIncome' => $dashboardData['balanceIncome']
            ]);
        }else
            return redirect()->to(site_url('admin/login'));
    }

    public function default(){
        return view('Default/public_home');
    }

    public function publicDashboard(){
        $isLoggedIn = $this->user->isLoggedIn();
        if($isLoggedIn)
            return view('Default/public_dashboard');
        else
            return redirect()->to(site_url('login'));
    }


}
