<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\OrderItem;
use Illuminate\Http\Request;
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

    /**
     * Download a paid digital product via signed URL (order item).
     * Requires order to be paid and order item to have digital_product_id.
     */
    public function downloadPaid(Request $request, OrderItem $orderItem): Response
    {
        $orderItem->load(['order', 'digitalProduct']);

        if ($orderItem->digital_product_id === null || ! $orderItem->digitalProduct) {
            abort(404, 'Digital product not found for this order item.');
        }

        if ($orderItem->order->payment_status !== 'paid' || $orderItem->order->status !== 'processing') {
            abort(403, 'This download is not available for the current order status.');
        }

        if ($orderItem->receipt_id && $request->query('receipt_id') !== null && $request->query('receipt_id') !== $orderItem->receipt_id) {
            abort(403, 'Invalid receipt.');
        }

        $product = $orderItem->digitalProduct;

        if (! $product->is_active || ! $product->file_path) {
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
