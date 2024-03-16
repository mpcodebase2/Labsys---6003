<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//Public
$routes->get('/', 'Home::default');
$routes->get('/dashboard', 'Home::publicDashboard');
$routes->get('/login', 'PublicAuthentication::index');
$routes->get('/sign-up', 'PublicAuthentication::register');
$routes->get('/forget-password', 'PublicAuthentication::forget');
$routes->get('/sign-out', 'PublicAuthentication::logout');

$routes->post('/api/login', 'PublicAuthentication::loginRequest');
$routes->post('/api/regsiter', 'PublicAuthentication::registerRequest');

$routes->get('/create-profile', 'PublicAuthentication::createPatientProfile');
$routes->post('/api/patient-profile/create', 'PublicAuthentication::createPatientProfileAPI');

$routes->get('/appointment/all', 'PublicAppointment::index');
$routes->get('/create-appointment', 'PublicAppointment::publicCreateAppointment');
$routes->post('/api/appointment/create', 'PublicAppointment::createAppointmentAPI');
$routes->post('/api/appointment/all', 'PublicAppointment::getAppointmentDataTableByPatientID');


$routes->get('/payment/(:segment)', 'Payment::index/$1');
$routes->get('/thankyou', 'Payment::thankyou');

//Dashboard
$routes->get('appointments/get-chart-cata', 'PublicAppointment::getChartData');


//Admin
$routes->get('/admin', 'Home::index');

$routes->get('/admin/dashboard', 'Home::dashboard');

$routes->get('/admin/login', 'Auth::login');
$routes->get('/admin/sign-up', 'Auth::register');
$routes->get('/admin/sign-out', 'Auth::logout');

$routes->post('/admin/api/login', 'Auth::loginRequest');
$routes->post('/admin/api/regsiter', 'Auth::_regsiter');

//Temp
$routes->get('/admin/passwrd', 'Auth::createPPassword');

//Admin
$routes->get('/admin/user/all', 'User::index');
$routes->post('/admin/api/user/all', 'User::allUsersData');
$routes->post('/admin/api/user/add', 'User::createUserData');
$routes->post('/admin/api/user/get/data', 'User::getUserDataById');
$routes->post('/admin/api/user/update', 'User::updateUserData');
$routes->post('/admin/api/user/delete', 'User::deleteUser');

//Roles
$routes->get('/admin/roles/all', 'Roles::index');
$routes->post('/admin/api/roles/all','Roles::allRolesData');
$routes->post('/admin/api/roles/get/data','Roles::getDataById');
$routes->post('/admin/api/roles/create','Roles::create');
$routes->post('/admin/api/roles/update','Roles::update');
$routes->post('/admin/api/roles/delete','Roles::delete');

//permissions
$routes->get('/admin/permissions/all', 'Permission::index');
$routes->post('/admin/api/permissions/all','Permission::allPermissionData');
$routes->post('/admin/api/permissions/get/data','Permission::getDataById');
$routes->post('/admin/api/permissions/create','Permission::create');
$routes->post('/admin/api/permissions/update','Permission::update');
$routes->post('/admin/api/permissions/delete','Permission::delete');

//Assign permission
$routes->get('/admin/permissions/assign', 'Permission::assignPermissionToRole');
$routes->get('/admin/api/get/permissions/by_role', 'Permission::getRolePermissions');
$routes->post('/admin/api/role-permissions/assign', 'Permission::assignRolePermissions');

//Tests
$routes->get('/admin/labtest/all', 'LabTest::index');
$routes->post('/admin/api/labtest/all','LabTest::allLabTestData');
$routes->post('/admin/api/labtest/get/data','LabTest::getDataById');
$routes->post('/admin/api/labtest/create','LabTest::create');
$routes->post('/admin/api/labtest/update','LabTest::update');
$routes->post('/admin/api/labtest/delete','LabTest::delete');


//Patient
$routes->get('/admin/patient/all', 'Patient::index');
$routes->post('/admin/api/patient/all','Patient::allPatientData');
$routes->post('/admin/api/patient/get/data','Patient::getDataById');
$routes->post('/admin/api/patient/create','Patient::create');
$routes->post('/admin/api/patient/update','Patient::update');
$routes->post('/admin/api/patient/delete','Patient::delete');

//Appointment
$routes->get('/admin/appointment/all', 'Appointment::index');
$routes->post('/admin/api/appointment/all','Appointment::allAppointmentData');
$routes->post('/admin/api/appointment/get/data','Appointment::getDataById');
$routes->post('/admin/api/appointment/create','Appointment::create');
$routes->post('/admin/api/appointment/update','Appointment::update');
$routes->post('/admin/api/appointment/delete','Appointment::delete');

//
////permissions
//$route['permissions/all'] = 'Permission/index';
//$route['api/permissions/all'] = 'Permission/allPermissionsData';
//$route['api/permissions/get/data'] = 'Permission/getDataById';
//$route['api/permissions/create'] = 'Permission/create';
//$route['api/permissions/update'] = 'Permission/update';
//$route['api/permissions/delete'] = 'Permission/delete';
//
////Assign permission
//$route['permissions/assign'] = 'Permission/assignPermissionToRole';
//$route['api/get/permissions/by_role'] = 'Permission/get_role_permissions';
//$route['api/role-permissions/assign'] = 'Permission/assignRolePermissions';