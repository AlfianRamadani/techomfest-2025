<?php

namespace App\Http\Controllers;

use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ResponseController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
    }
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);
        $path = $request->file('image')["tmp_name"];
        $mime = mime_content_type($path);
        $base64 = \Helper::imageToBase64($request->file('image'));


        $result = $this->analyzeImage($mime, $base64);

        return response()->json($result);
    }
    protected function analyzeImage($mime, $base64)
    {
        $payload = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "inlineData" => [
                                "mimeType" => $mime,
                                "data" => $base64
                            ]
                        ],
                        [
                            "text" => "Analisa makanan dalam foto ini."
                        ]
                    ]
                ]
            ]
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('gemini.url') . "?key=$this->apiKey", $payload);

        return $response->json();
    }
}
