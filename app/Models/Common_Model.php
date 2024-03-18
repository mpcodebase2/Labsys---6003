<?php
namespace App\Models;

use CodeIgniter\Model;


class Common_Model extends Model
{
    public function currentColomboTime()
    {
        $date = new \DateTime(null, new \DateTimeZone("Asia/Colombo"));
        return $date->format("Y-m-d H:i:s");
    }

    public function formatDateWithEnglishLetter($mysqlDate) {
        // Check if the MySQL date is NULL or set to the Unix epoch
        if ($mysqlDate == '1970-01-01' || $mysqlDate == null) {
            // Return a default message or value indicating no valid date
            return "-";
        } else {
            // Convert MySQL date to Unix timestamp
            $timestamp = strtotime($mysqlDate);
            // Format the Unix timestamp to desired format
            return date('jS M Y', $timestamp);
        }
    }


    public function getCurrentTimeStamp(){
        $date = new \DateTime(null, new \DateTimeZone("Asia/Colombo"));
        $currentdt = $date->format("Y-m-d h:i:sa");
        return STRTOTIME($currentdt);
    }

    public function encode_url($url) {
        return base64_encode($url);
    }

    public function decode_url($encoded_url) {
        return base64_decode($encoded_url);
    }

    public function userHistorySave($status, $message, $data)
    {
        return ['status' => $status, 'message' => $message, 'data' => $data];
    }
}