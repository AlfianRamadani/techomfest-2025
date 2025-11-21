<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Highlight.js (untuk JSON viewer) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
</head>

<body class="bg-light">

    <div class="container py-5">
        <h2 class="text-center mb-4">Hasil Analisa Gambar</h2>
        @php
            $image_info = session('image_info');
        @endphp

        @if ($image_info)
            <div class="d-flex justify-content-center align-items-center mb-4">
                <img class="img-fluid" src="{{ $image_info['original_path'] }}" alt="Optimized Image">
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <strong>File Size</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <h5 class="text-muted">Size</h5>
                            <h3 class="text-primary">{{ $image_info['original_size'] }} byte</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @php
            $timings = session('timings');
        @endphp

        @if ($timings)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <strong>Performance Metrics</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <h5 class="text-muted">Validation</h5>
                            <h3 class="text-primary">{{ $timings['validation_time'] }} ms</h3>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="text-muted">AI Analysis</h5>
                            <h3 class="text-primary">{{ $timings['ai_analysis_time'] }} ms</h3>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h5 class="text-muted">Total Time</h5>
                            <h3 class="text-success">{{ $timings['total_time'] }} ms</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        @php
            $result = session('result');
        @endphp

        @if ($result)
            <!-- Hasil Analisa Text -->
            @if (isset($result['candidates'][0]['content']['parts'][0]['text']))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <strong>Hasil Analisa</strong>
                    </div>
                    <div class="card-body">
                        <div style="white-space: pre-line; line-height: 1.8;">
                            {{ $result['candidates'][0]['content']['parts'][0]['text'] }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- JSON Response (Raw) -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <strong>JSON Response</strong>
                </div>
                <div class="card-body">
                    <pre><code class="json">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                <p class="text-muted mb-0">Tidak ada data analisa.</p>
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('upload.index') }}" class="btn btn-primary">Upload Gambar Lagi</a>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
