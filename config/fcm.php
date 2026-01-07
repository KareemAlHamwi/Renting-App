<?php

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'service_account_json' => storage_path('app/' . env('FIREBASE_SERVICE_ACCOUNT_JSON')),
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
];
