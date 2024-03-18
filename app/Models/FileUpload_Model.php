<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;
use Exception;


class FileUpload_Model extends Model
{

    protected $db;
    protected $commonModel;

    function __construct()
    {
        parent::__construct();
        $this->request = \Config\Services::request();
        $this->session = session();
        $this->commonModel = new Common_Model();
    }

    public function uploadMultipleImages($fieldName, $key, $folderNamePre, $newName, $imageFor, $ipAddress)
    {
        $data = [];
        $uploadedFiles = $this->request->getFileMultiple($fieldName);

        $upload_path = $this->createUploadPath($folderNamePre);

        try {
            if (!empty($uploadedFiles[$key])) {
                $uploadedFile = $uploadedFiles[$key];

                // Generate a unique filename with the original file extension
                $newFileName = $newName . '.' . $uploadedFile->getClientExtension();
                $uploadedFile->move($upload_path['full'], $newFileName);

                $currentTime = $this->commonModel->currentColomboTime();
                $fileType = $uploadedFile->getClientMimeType();
                $isImage = preg_match('/image\/(jpeg|jpg|png|gif)/', $fileType);

                $dataImage = [
                    'image_for'     => $imageFor,
                    'file_name'     => $newFileName, // Use the generated filename
                    'file_type'     => $uploadedFile->getClientMimeType(),
                    'file_size'     => $uploadedFile->getSize(),
                    'is_image'      => $isImage, // Check if it's an image
                    // Image dimensions (if needed):
                    'image_width'   => null, // Requires image processing library
                    'image_height'  => null, // Requires image processing library
                    'image_type'    => null, // May not be reliable from client-reported data
                    'path'          => $upload_path['path_for_db'] . '/' . $newFileName, // Use the generated filename
                    'active'        => true,
                    'ip_address'    => $ipAddress,
                    'updated_at'    => $currentTime
                ];

                return $this->dataReturn(true, 'Successfully uploaded', $dataImage);
            } else {
                return $this->dataReturn(false, 'Uploaded image not found', $data);
            }
        } catch (Exception $e) {
            return $this->dataReturn(false, 'Error: Exception!', $data);
        }
    }

    public function uploadMultipleImagesOLd($fieldName, $key, $folderNamePre, $newName, $imageFor, $ipAddress)
    {

        $data = [];
        $upload = \Config\Services::upload();

        $uploadPath = $this->createUploadPath($folderNamePre);
        $config = [
            'upload_path' => $uploadPath['full'],
            'allowed_types' => 'gif|jpg|png|jpeg|pdf|docx|doc|txt',
            'max_size' => 5000,
            'overwrite' => false
        ];

        try {
            if (!empty($_FILES[$fieldName]['name'][$key])) {
                $_FILES['file']['name'] = $_FILES[$fieldName]['name'][$key];
                $_FILES['file']['type'] = $_FILES[$fieldName]['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES[$fieldName]['tmp_name'][$key];
                $_FILES['file']['error'] = $_FILES[$fieldName]['error'][$key];
                $_FILES['file']['size'] = $_FILES[$fieldName]['size'][$key];
                $config['file_name'] = $newName;

                $upload->initialize($config);
                $_isSuccessUpload = $upload->doUpload('file');
                $dataInfo[] = $upload->getData();
                $currentTime = $this->common_model->currentColomboTime();

                if ($_isSuccessUpload) {
                    $dataImage = [
                        'image_for' => $imageFor,
                        'file_name' => $dataInfo[0]['file_name'],
                        'file_type' => $dataInfo[0]['file_type'],
                        'file_size' => $dataInfo[0]['file_size'],
                        'is_image' => $dataInfo[0]['is_image'],
                        'image_width' => $dataInfo[0]['image_width'],
                        'image_height' => $dataInfo[0]['image_height'],
                        'image_type' => $dataInfo[0]['image_type'],
                        'path' =>  $uploadPath['path_for_db'] . '/' . $dataInfo[0]['file_name'],
                        'active' => true,
                        'ip_address' => $ipAddress,
                        'updated_at' => $currentTime
                    ];
                    return $this->dataReturn(true, 'Successfully uploaded', $dataImage);
                } else {
                    $message = $upload->getErrorString();
                    return $this->dataReturn(false, $message, $data);
                }
            } else {
                return $this->dataReturn(false, 'Upload image not found', $data);
            }
        } catch (Exception $e) {
            return $this->dataReturn(false, 'Error: Exception!', $data);
        }
    }

    public function updateDatabaseEntry($dataForStore, $tableForUpdate)
    {
        $this->db->table($tableForUpdate)->insert($dataForStore);
        $mediaId =  $this->db->insertID();
        if ($mediaId) {
            return $mediaId;
        } else {
            return false;
        }
    }

    private function createUploadPath($folderName)
    {
        $storeFolder = $folderName;
        $folderPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $storeFolder;
       // $folderPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $storeFolder;
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        return ['full' => $folderPath, 'path_for_db' => $storeFolder];
    }


    private function dataReturn($status, $message, $data)
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }
}
