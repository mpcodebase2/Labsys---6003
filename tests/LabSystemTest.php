<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\Common_Model;
use App\Controllers\Payment;
use App\Controllers\User;
use App\Controllers\Home;

class LabSystemTest extends CIUnitTestCase
{
    //Test CommonModel - encode_url function is working correctly
    public function testEncode_url(){
        $common = new Common_Model();
        $result = $common->encode_url('yogger');
        $this->assertEquals('eW9nZ2Vy', $result);
    }

    public function testGetCurrentTimeStamp(){
        $common = new Common_Model();
        $result = $common->getCurrentTimeStamp();
        $this->assertNotEmpty($result);
    }

    public function testPaymentViews(){
        $payment = new Payment();
        $result = $payment->index('eW9nZ2Vy');
        $this->assertNotEmpty($result);
    }

    public function testPaymentThankyouViews(){
        $payment = new Payment();
        $result = $payment->thankyou();
        $this->assertNotEmpty($result);
    }

    public function testUser(){
        $payment = new User();
        $result = $payment->index();
        $this->assertNotEmpty($result);

    }

    public function testDecode_url(){
        $common = new Common_Model();
        $result = $common->decode_url('eW9nZ2Vy');
        $this->assertEquals('yogger', $result);
    }

    public function testFormatDateWithEnglishLetter(){
        $common = new Common_Model();
        $result = $common->formatDateWithEnglishLetter('2024-05-01');
        $this->assertEquals('1st May 2024', $result); // expected is '1st May 2024'
    }

    public function testDashboard(){
        $home = new Home();
        $result = $home->default();
        $this->assertNotEmpty($result);
    }

    public function testPublicDashboard(){
        $home = new Home();
        $result = $home->publicDashboard();
        $this->assertNotEmpty($result);
    }

    public function testUserHistorySave(){
        $common = new Common_Model();
        $result = $common->userHistorySave('true', 'Saved success', '');
        $this->assertNotEmpty($result);
    }
}
