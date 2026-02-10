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
     * Show the download gate page (signed URL). User must enter receipt ID to proceed.
     */
    public function showDownloadGate(OrderItem $orderItem)
    {
        $orderItem->load(['order', 'digitalProduct']);

        if ($orderItem->digital_product_id === null || ! $orderItem->digitalProduct) {
            abort(404, 'Digital product not found for this order item.');
        }

        $order = $orderItem->order;
        $allowedStatuses = ['processing', 'delivered'];
        if ($order->payment_status !== 'paid' || ! in_array($order->status, $allowedStatuses, true)) {
            abort(403, 'This download is not available for the current order status.');
        }

        $product = $orderItem->digitalProduct;
        if (! $product->is_active) {
            abort(404, 'File not available.');
        }

        return view('digital-download-gate', [
            'orderItem' => $orderItem,
            'productTitle' => $product->title,
        ]);
    }

    /**
     * Verify receipt ID and stream the file. Rate limited to prevent abuse.
     */
    public function verifyAndDownload(Request $request, OrderItem $orderItem): Response
    {
        $orderItem->load(['order', 'digitalProduct']);

        if ($orderItem->digital_product_id === null || ! $orderItem->digitalProduct) {
            abort(404, 'Digital product not found for this order item.');
        }

        $order = $orderItem->order;
        $allowedStatuses = ['processing', 'delivered'];
        if ($order->payment_status !== 'paid' || ! in_array($order->status, $allowedStatuses, true)) {
            abort(403, 'This download is not available for the current order status.');
        }

        $receiptId = $request->validate(['receipt_id' => 'required|string|max:64'])['receipt_id'];
        $receiptId = trim($receiptId);

        if (! $orderItem->receipt_id || $receiptId !== $orderItem->receipt_id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid receipt ID. Please check the receipt number from your email.');
        }

        $product = $orderItem->digitalProduct;
        if (! $product->is_active || ! $product->file_path) {
            abort(404, 'File not available.');
        }

        $path = Storage::disk('public')->path($product->file_path);
        if (! file_exists($path)) {
            abort(404, 'File not found.');
        }

        // When stream=1 (e.g. from iframe form), return file. Otherwise return HTML that triggers download then redirects to home.
        if ($request->boolean('stream')) {
            $filename = $product->title . '.' . ($product->file_type === 'pdf' ? 'pdf' : pathinfo($product->file_path, PATHINFO_EXTENSION));
            $mime = $product->file_type === 'pdf' ? 'application/pdf' : 'audio/mpeg';

            return response()->file($path, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'attachment; filename="' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename) . '"',
            ]);
        }

        return response()->view('digital-download-start', [
            'orderItem' => $orderItem,
            'receiptId' => $receiptId,
            'verifyUrl' => route('digital-product.download.verify', ['orderItem' => $orderItem->id]),
            'homeUrl' => route('home'),
        ]);
    }
}
