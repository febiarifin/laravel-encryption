<?php

namespace App\Http\Controllers;

use App\Services\EncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataController extends Controller
{

    public function sendData(Request $request)
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'age' => 30,
            'roles' => ['admin', 'editor']
        ];

        $encryptedPayload = EncryptionService::encryptData($data);

        // Check for client-side encryption errors
        if (isset($encryptedPayload['error'])) {
            return response()->json(['status' => 'error', 'message' => $encryptedPayload['error']], 400);
        }

        $response = Http::post(env('SERVER_BASE_URL', 'http://localhost:8000').'/api/decrypt', $encryptedPayload);

        if ($response->status() !== 200) {
            return response()->json(['status' => 'error', 'message' => $response->json('message')], $response->status());
        }

        return response()->json($response->json());
    }
}
