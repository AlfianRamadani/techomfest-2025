<?php

return [
    'api_key' => env('GEMINI_API_KEY', ''),
    'model'   => env('GEMINI_MODEL', 'gemini-2.5-flash-pro'),
    'url'     => env('GEMINI_ENDPOINT_URL', '"https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-pro:generateContent"')
];
