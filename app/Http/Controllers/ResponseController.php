<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ResponseController extends Controller
{

    public static function uploadIndex()
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
        $json_ex = 
        <<<EOT
        {
        "analysis_status": "success",
        "dish_name": "Nasi Goreng Ayam Spesial",
        "estimation_side_dish": ["Ayam Bumbu Merah", "Telur Rebus", "Kerupuk"],
        "nutritional_summary": {
            "total_calories_est": 550,
            "estimated_serving_size_g": 350,
            "macros_g": {
            "protein": 25,
            "fat": 30,
            "carbohydrates": 45
            },
            "fat_breakdown_g": {
            "saturated_fat": 15,
            "trans_fat": 0
            },
            "sodium_mg": 850,
            "sugar_g": 5
        },
        "risk_assessment": {
            "is_high_risk": true,
            "primary_risk_factor": "Gula dan Garam Tinggi",
            "riskiest_ingredient": "Kecap Manis dan Minyak Goreng",
            "risk_score_overall_100": 75,
            "risk_details": "Kandungan natrium (garam) melebihi batas konsumsi harian yang disarankan. Tingginya lemak jenuh dari minyak sawit juga berisiko.",
            
            "disease_risk_scores": {
            "cardiovascular_disease": {
                "contributing_factor": "Tinggi Lemak Jenuh & Kolesterol",
                "risk_score_100": 85,
                "advice": "Batasi konsumsi makanan ini. Ganti minyak goreng dengan minyak yang lebih sehat (misalnya minyak zaitun) atau kurangi porsi lemak hewani."
            },
            "type_2_diabetes": {
                "contributing_factor": "Tinggi Indeks Glikemik dan Gula Tambahan",
                "risk_score_100": 60,
                "advice": "Konsumsi dengan porsi kecil. Ganti sumber karbohidrat (nasi) dengan karbohidrat kompleks (nasi merah)."
            },
            "hypertension": {
                "contributing_factor": "Tinggi Natrium (Garam)",
                "risk_score_100": 92,
                "advice": "Hentikan penambahan kecap asin\/kecap manis yang berlebihan. Cari alternatif rendah natrium."
            },
            "obesity": {
                "contributing_factor": "Kepadatan Kalori dan Lemak Tinggi",
                "risk_score_100": 70,
                "advice": "Sangat disarankan mengurangi ukuran porsi. Tambahkan lebih banyak sayuran."
            }
            }
        },
        "healthy_alternatives": [
        {
            "name": "Nasi Goreng Merah (Nasi Merah)",
            "reason": "Mengganti nasi putih dengan nasi merah (brown rice) untuk meningkatkan serat dan mengurangi indeks glikemik.",
            "estimated_calories": 400
            },
            {
            "name": "Ayam Panggang Tanpa Kulit",
            "reason": "Mengganti ayam goreng dengan ayam panggang (tanpa kulit) untuk mengurangi lemak trans dan jenuh.",
            "estimated_calories": 500
            }
        ]
        }
        EOT;

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
                            "text" => "Analisa makanan dalam foto ini dan uraikan tanpa tambahan teks apapun dan jangan pakai code block dan format tambahan, serta analisis juga keseluruhan lauknya sebagaimana bentuk contoh json seperti ini {$json_ex}"
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
