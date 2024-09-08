<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class EncryptionController extends Controller
{

    public function decryptData(Request $request)
    {
        try {
            $encryptedAesKey = base64_decode($request->input('encrypted_aes_key'));
            $encryptedData = base64_decode($request->input('encrypted_data'));
            $iv = base64_decode($request->input('iv'));

            // Load the private key
            $privateKey = file_get_contents(storage_path('keys/private_key.pem'));

            // Check if private key is valid and available
            if (!$privateKey) {
                throw new Exception('Private key not found or invalid.');
            }

            // Decrypt the AES key using the private key
            if (!openssl_private_decrypt($encryptedAesKey, $aesKey, $privateKey)) {
                throw new Exception('Failed to decrypt AES key. Invalid private key.');
            }

            // Decrypt the data using AES
            $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $aesKey, OPENSSL_RAW_DATA, $iv);

            if ($decryptedData === false) {
                throw new Exception('Decryption failed. The data might be corrupted.');
            }

            // Convert the JSON string back to an array
            $dataArray = json_decode($decryptedData, true);

            return response()->json(['status' => 'success', 'data' => $dataArray]);

        } catch (Exception $e) {
            // Return a JSON response with the error
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

    }

}
