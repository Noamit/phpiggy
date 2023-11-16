<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Config\Paths;

class ReceiptService {
    public function __construct(private Database $db) {

    }

    public function validateFile(?array $file) {

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException(['receipt' => ['Failed to upload file.']]);
        }
        
        $maxFileSizeMB = 3 * 1024 * 1024;
        
        if ($file['size'] > $maxFileSizeMB) {
            throw new ValidationException(['receipt' => ['File upload is too large.']]);
        }

        $originalFilename = $file['name'];

        // if (!preg_match('/^[A-Za-z0-9._-\s]+$/', $originalFilename)) {
        //     throw new ValidationException(['receipt' => ['Invalid filename']]);
        // }

        $clientMimeType = $file['type'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            throw new ValidationException(['receipt' => ['Invalid file type.']]);
        }
        // dd($file);
    }

    public function upload(?array $file, $transaction) {
        $originalFilename = $file['name'];
        $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $newFilename = bin2hex(random_bytes(16)) . "." . $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFilename;

        // move_uploaded_file is built in function of php. file is saved temporary, we need to save on the server.
        if (!move_uploaded_file($file["tmp_name"], $uploadPath)) {
            throw new ValidationException(['receipt' => ['Failed to upload file']]);
        }

        $sql = "INSERT INTO receipts(original_filename, storage_filename, media_type, transaction_id)
                VALUES(:original_filename, :storage_filename, :media_type, :transaction_id)";
        $params = [
            "original_filename" => $originalFilename,
            "storage_filename" => $newFilename,
            "media_type" => $file['type'],
            "transaction_id" => $transaction
        ];

        $this->db->query($sql, $params);
    }

    public function getReceipt(string $id) {
        $sql = "SELECT * FROM receipts WHERE id = :id";
        $data = ['id' => $id];
        
        $receipt = $this->db->query($sql, $data)->find();

        return $receipt;
    }

    public function read(array $receipt) {
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt['storage_filename'];

        if(!file_exists($filePath)) {
            redirectTo("/");
        }

        // tell the browser the file beacuse it is not html
        header("Content-Disposition: inline; filename={$receipt['original_filename']}");
        header("Content-Type: {$receipt['media_type']}");
        readfile($filePath);
    }

}