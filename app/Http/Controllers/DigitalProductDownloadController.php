<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DigitalProductDownloadController extends Controller
{
    /**
     * Download a free digital product (no cart/checkout).
     * Only allowed when is_active and is_free are true.
     */
    public function __invoke(int $id): Response
    {
        $product = DigitalProduct::where('id', $id)
            ->where('is_active', true)
            ->where('is_free', true)
            ->firstOrFail();

        if (! $product->file_path) {
            abort(404, 'File not available.');
        }

        $path = Storage::disk('public')->path($product->file_path);
        if (! file_exists($path)) {
            abort(404, 'File not found.');
        }

        $filename = $product->title . '.' . ($product->file_type === 'pdf' ? 'pdf' : pathinfo($product->file_path, PATHINFO_EXTENSION));
        $mime = $product->file_type === 'pdf' ? 'application/pdf' : 'audio/mpeg';

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename) . '"',
        ]);
    }
}
