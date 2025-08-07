<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChurchImageController extends Controller
{
    public function show(string $path): \Illuminate\Http\Response
    {
        $disk = Storage::disk('wasabi');

        if (!$disk->exists('church-images/' . $path)) {
            \abort(404);
        }
        $file = $disk->get('church-images/' . $path);
        // Guess MIME type from file extension
        $extension = \pathinfo($path, \PATHINFO_EXTENSION);
        $mime = Str::startsWith($extension, 'jp') ? 'image/jpeg' : ($extension === 'png' ? 'image/png' : 'application/octet-stream');

        $headers = [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=' . (60 * 60 * 24 * 30), // 30 days
            'Expires' => \gmdate('D, d M Y H:i:s', \time() + (60 * 60 * 24 * 30)) . ' GMT',
        ];

        return Response::make($file, 200, $headers);
    }
}
