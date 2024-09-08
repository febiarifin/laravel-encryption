<?php

namespace App\Services;

use Exception;

class EncryptionService
{
    public static function encryptData(array $data)
    {
        try {
            // Convert the array to a JSON string
            $dataString = json_encode($data);

            $aesKey = openssl_random_pseudo_bytes(32);
            $iv = openssl_random_pseudo_bytes(16);
            $encryptedData = openssl_encrypt($dataString, 'aes-256-cbc', $aesKey, OPENSSL_RAW_DATA, $iv);

            // Load the public key
            $publicKey = file_get_contents(storage_path('keys/public_key.pem'));

            // Check if public key is valid and available
            if (!$publicKey) {
                throw new Exception('Public key not found or invalid.');
            }

            // Encrypt the AES key using the public key
            if (!openssl_public_encrypt($aesKey, $encryptedAesKey, $publicKey)) {
                throw new Exception('Failed to encrypt AES key. Invalid public key.');
            }

            return [
                'encrypted_data' => base64_encode($encryptedData),
                'encrypted_aes_key' => base64_encode($encryptedAesKey),
                'iv' => base64_encode($iv),
            ];
        } catch (Exception $e) {
            // Log the error or return it for handling
            return ['error' => $e->getMessage()];
        }
    }
}
