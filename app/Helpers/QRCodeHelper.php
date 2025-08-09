<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QRCodeHelper
{
    /**
     * Generate and save a QR code with the provided code.
     *
     * @param string $code The data to encode in the QR code.
     * @return string The path of the saved QR code image.
     */
    public static function generateQrCode(string $code): string
    {
        // Add a small delay to ensure file system is ready

        // Create a new QR code with the provided data
        $qrCode = new QrCode($code);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Generate a unique file name for the QR code image
        $fileName = 'qr_code_' . $code . '.png';
        $filePath = 'public/qr_codes/' . $fileName;

        try {
            // Save the QR code image to the public storage folder
            Storage::put($filePath, $result->getString());

            // Verify file was created
            if (Storage::disk('public')->exists('qr_codes/' . $fileName)) {
                return 'qr_codes/' . $fileName;
                // return asset('storage/qr_codes/' . $fileName);
            }
        } catch (\Exception $e) {
            Log::error("QR Code Generation Failed", [
                'code' => $code,
                'error' => $e->getMessage(),
            ]);
        }
        return '';
    }

    /**
     * Safely get a valid file path for PDF generation, ensuring the path exists and is a file.
     *
     * @param string|null $relativePath The relative path from storage/app/public/
     * @param string $defaultPath Alternative path to try if the first fails
     * @return string|null Valid file path or null if no valid file found
     */
    public static function getSafeImagePath(?string $relativePath, string $defaultPath = ''): ?string
    {
        if (empty($relativePath)) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $relativePath);
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return $fullPath;
        }

        // Try default path if provided
        if (!empty($defaultPath) && file_exists($defaultPath) && is_file($defaultPath)) {
            return $defaultPath;
        }

        Log::warning("File not found or is directory", [
            'requested_path' => $relativePath,
            'full_path' => $fullPath,
            'default_path' => $defaultPath
        ]);

        return null;
    }

    /**
     * Get a safe public asset path for PDF generation.
     *
     * @param string $publicPath The path relative to public directory
     * @return string|null Valid file path or null if file doesn't exist
     */
    public static function getSafePublicPath(string $publicPath): ?string
    {
        $fullPath = public_path($publicPath);
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return $fullPath;
        }

        Log::warning("Public file not found", [
            'requested_path' => $publicPath,
            'full_path' => $fullPath
        ]);

        return null;
    }
}
