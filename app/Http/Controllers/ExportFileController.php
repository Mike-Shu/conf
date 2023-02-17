<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportFileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param string $filename
     *
     * @return StreamedResponse
     */
    public function __invoke(string $filename): StreamedResponse
    {
        $filePath = config('export.files.folder') . '/' . $filename;

        abort_unless(Storage::exists($filePath), 404);

        return Storage::download($filePath, Str::after($filename, "_"));
    }
}
