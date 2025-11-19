<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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
            'image' => 'required|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'lauk_makanan' => 'nullable|string',
            'bumbu_tambahan' => 'nullable|string',
        ]);

        $file = $request->file('image');

        if (!Storage::disk('public')->exists('uploads/originals')) {
            Storage::disk('public')->makeDirectory('uploads/originals');
        }

        $originalName = time() . '_ORIGINAL_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        Storage::disk('public')->put('uploads/originals/' . $originalName, $file->get()); // ← simpan file original tanpa modifikasi

        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getRealPath());

        $image->orient();

        $image->scale(width: 1500);

        $optimizedBinary = $image->toJpeg(quality: 80)->toString();

        if (!Storage::disk('public')->exists('uploads/optimized')) {
            Storage::disk('public')->makeDirectory('uploads/optimized');
        }

        $optimizedName = time() . '_OPTIMIZED_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . ".jpg";

        Storage::disk('public')->put('uploads/optimized/' . $optimizedName, $optimizedBinary);

        $base64 = base64_encode($optimizedBinary);
        $mime = "image/jpeg";

        $lauk = $request->lauk_makanan;
        $bumbu = $request->bumbu_tambahan;

        $result = $this->analyzeImage($mime, $base64, $lauk, $bumbu);

        return redirect()
            ->route('result.index')
            ->with('result', $result);
    }

    protected function analyzeImage($mime, $base64, $lauk, $bumbu)
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

        $userInfo = "
        Informasi tambahan dari user:
        - Lauk makanan: {$lauk}
        - Bumbu tambahan: {$bumbu}

        Jika tidak terlihat di gambar namun disebutkan user, anggap informasi tersebut valid dan sertakan dalam analisis.
        ";

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
                            "text" => "
                                Analisa makanan dalam foto ini secara menyeluruh berdasarkan:
                                1. Informasi visual dari gambar (komposisi utama, lauk, bumbu, kuah, saus, minyak, rempah).
                                2. Informasi tambahan dari user berikut ini:
                                {$userInfo}

                                Gunakan informasi tambahan user hanya jika relevan dan konsisten dengan visual foto.
                                Jika ada perbedaan antara foto dan informasi user, dahulukan data dari foto dan sesuaikan penjelasannya.

                                Analisa makanan dalam foto ini dan uraikan tanpa tambahan teks apapun terutama TANPA markdown code block dan ```json, serta analisis juga keseluruhan lauknya (jika ada dalam gambar) sebagaimana bentuk contoh json seperti ini {$json_ex}
                            "
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
