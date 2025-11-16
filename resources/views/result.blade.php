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
    <script>hljs.highlightAll();</script>
</head>

<body class="bg-light">

    <div class="container py-5">
        <h2 class="text-center mb-4">Hasil Analisa Gambar</h2>

        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>JSON Response</strong>
            </div>

            <div class="card-body">
                @php
                    $result = session('result');
                @endphp

                @if($result)
                    <pre><code class="json">{{ json_encode($result, JSON_PRETTY_PRINT) }}</code></pre>
                @else
                    <p class="text-muted">Tidak ada data analisa.</p>
                @endif
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('upload.index') }}" class="btn btn-primary">Upload Gambar Lagi</a>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
