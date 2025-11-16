<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ResponseController extends Controller
{

    public static function upladIndex()
    {
        return view('upload');
    }

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

        $file = $request->file('image');

        $mime = $file->getClientMimeType();

        $base64 = Helper::imageToBase64($file);

        $result = $this->analyzeImage($mime, $base64);

        return redirect()
            ->route('result.index')
            ->with('result', $result);
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
