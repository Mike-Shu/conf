<?php

return [
    'data' => [
        'folder' => env('EXPORT_DATA_FOLDER', "exports"),
        // The "chunk_size" parameter is configured in the "excel" configuration file.
    ],
    'files' => [
        'folder' => env('EXPORT_FILES_FOLDER', "exports"),
        'chunk_size' => env('EXPORT_FILES_CHUNK_SIZE', 20),
    ],
];
