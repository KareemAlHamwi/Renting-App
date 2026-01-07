<?php

return [
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'service_account_json' => env('FIREBASE_SERVICE_ACCOUNT_JSON'),

    // OAuth scope used by FCM HTTP v1
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
];
