<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PdfAssetService
{
    protected string $productCachePath;

    public function __construct()
    {
        $this->productCachePath = public_path('cache/products');
    }

    /**
     * Get local image path for product (download & cache if needed)
     */
    public function productImage($product): ?string
    {
        if (!$product || empty($product->image_url)) {
            return null;
        }

        if (!File::exists($this->productCachePath)) {
            File::makeDirectory($this->productCachePath, 0755, true);
        }

        $extension = $this->guessExtension($product->image_url);
        $filename  = 'product_' . $product->id . '.' . $extension;
        $fullPath  = $this->productCachePath . DIRECTORY_SEPARATOR . $filename;

        // มีแล้ว ใช้เลย
        if (File::exists($fullPath)) {
            return $fullPath;
        }

        // ดาวน์โหลดครั้งแรก
        try {
            $response = Http::timeout(15)->get($product->image_url);

            if ($response->successful()) {
                File::put($fullPath, $response->body());
                return $fullPath;
            }
        } catch (\Throwable $e) {
            // log ได้ถ้าต้องการ
        }

        return null;
    }

    protected function guessExtension(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $ext  = pathinfo($path, PATHINFO_EXTENSION);

        return in_array(strtolower($ext), ['jpg','jpeg','png','webp'])
            ? strtolower($ext)
            : 'jpg';
    }
}
