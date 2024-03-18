<?php
namespace App\Models;

use CodeIgniter\Model;

class GetData_Model extends Model
{
    //protected $table = 'country';

    public function getCountry()
    {
        return $this->db->table('country')
            ->get()
            ->getResult();
    }
    public function getProvince()
    {
        return $this->db->table('provinces')
            ->select('id, name_en as name')
            ->where('active', true)
            ->get()
            ->getResult();
    }

    public function getDistrict()
    {
        return $this->db->table('districts')
            ->select('id, province_id, name_en AS name')
            ->get()
            ->getResult();
    }

    public function getCity()
    {
        return $this->db->table('cities')
            ->select('id, district_id, name_en as name')
            ->where('active', true)
            ->get()
            ->getResult();
    }

    public function getDashboardData()
    {
        // Get current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Total Appointments for this month
        $appointmentsBuilder = $this->db->table('appointments');
        $totalAppointments = $appointmentsBuilder->where('YEAR(appointment_date)', $currentYear)
            ->where('MONTH(appointment_date)', $currentMonth)
            ->countAll();

        // Total Patients
        $patientsBuilder = $this->db->table('patients');
        $totalPatients = $patientsBuilder->countAll();

        // Total Income for this month
        $appointmentsBuilder = $this->db->table('appointments');
        $totalIncome = $appointmentsBuilder->selectSum('amount')
            ->where('YEAR(appointment_date)', $currentYear)
            ->where('MONTH(appointment_date)', $currentMonth)
            ->get()
            ->getRow()
            ->amount;

        // Paid Income for this month
        $appointmentsBuilder = $this->db->table('appointments');
        $paidIncome = $appointmentsBuilder->selectSum('paid')
            ->where('YEAR(appointment_date)', $currentYear)
            ->where('MONTH(appointment_date)', $currentMonth)
            ->get()
            ->getRow()
            ->paid;

        // Balance Income for this month
        $appointmentsBuilder = $this->db->table('appointments');
        $balanceIncome = $appointmentsBuilder->selectSum('due')
            ->where('YEAR(appointment_date)', $currentYear)
            ->where('MONTH(appointment_date)', $currentMonth)
            ->get()
            ->getRow()
            ->due;

        return [
            'totalAppointments' => $totalAppointments,
            'totalPatients' => $totalPatients,
            'totalIncome' => $totalIncome,
            'paidIncome' => $paidIncome,
            'balanceIncome' => $balanceIncome
        ];
    }
//    public function getProvince()
//    {
//        return $this->select('id, name_en as name, name_ta')
//            ->table('provinces')
//            ->where('active', true)
//            ->findAll();
//    }
//
//    public function getDistrict()
//    {
//        return $this->select('id, province_id, CONCAT(name_en) AS name')
//            ->table('districts')
//            ->findAll();
//    }
//
//    public function getCity()
//    {
//        return $this->select('id, district_id, name_en as name')
//            ->table('cities')
//            ->where('active', true)
//            ->findAll();
//    }
}

