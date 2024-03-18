<?php namespace App\Libraries;

class UserSession
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        $userId = $this->session->get('user_id');
        return !empty($userId);
    }
}
