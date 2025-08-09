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
            Log::info("getSafeImagePath: Empty relative path provided");
            return null;
        }

        // Try multiple possible paths
        $possiblePaths = [
            storage_path('app/public/' . $relativePath),
            storage_path('app/' . $relativePath),
            public_path('storage/' . $relativePath),
            public_path($relativePath)
        ];

        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath) && is_file($fullPath)) {
                // Check file size to prevent memory issues
                $fileSize = filesize($fullPath);
                if ($fileSize > 2 * 1024 * 1024) { // 2MB limit for PDF images
                    Log::warning("getSafeImagePath: File too large for PDF", [
                        'path' => $fullPath,
                        'size' => $fileSize,
                        'limit' => '2MB'
                    ]);
                    continue;
                }
                
                Log::info("getSafeImagePath: Found valid file", [
                    'requested_path' => $relativePath,
                    'found_path' => $fullPath,
                    'size' => $fileSize
                ]);
                return $fullPath;
            }
        }

        // Try default path if provided
        if (!empty($defaultPath) && file_exists($defaultPath) && is_file($defaultPath)) {
            $fileSize = filesize($defaultPath);
            if ($fileSize <= 2 * 1024 * 1024) { // 2MB limit
                Log::info("getSafeImagePath: Using default path", [
                    'default_path' => $defaultPath,
                    'size' => $fileSize
                ]);
                return $defaultPath;
            }
        }

        Log::warning("getSafeImagePath: File not found in any location or too large", [
            'requested_path' => $relativePath,
            'tried_paths' => $possiblePaths,
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
        // Try multiple possible locations for public assets
        $possiblePaths = [
            public_path($publicPath),
            public_path('assets/' . $publicPath),
            public_path('site/' . $publicPath),
            base_path('public/' . $publicPath)
        ];

        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath) && is_file($fullPath)) {
                Log::info("getSafePublicPath: Found valid file", [
                    'requested_path' => $publicPath,
                    'found_path' => $fullPath
                ]);
                return $fullPath;
            }
        }

        Log::warning("getSafePublicPath: Public file not found in any location", [
            'requested_path' => $publicPath,
            'tried_paths' => $possiblePaths
        ]);

        return null;
    }

    /**
     * Debug helper to check image availability for PDFs
     *
     * @param string|null $relativePath
     * @return array Debug information
     */
    public static function debugImagePath(?string $relativePath): array
    {
        $debug = [
            'input' => $relativePath,
            'is_empty' => empty($relativePath),
            'paths_checked' => [],
            'found' => false,
            'final_path' => null
        ];

        if (empty($relativePath)) {
            return $debug;
        }

        $possiblePaths = [
            'storage_app_public' => storage_path('app/public/' . $relativePath),
            'storage_app' => storage_path('app/' . $relativePath),
            'public_storage' => public_path('storage/' . $relativePath),
            'public_direct' => public_path($relativePath)
        ];

        foreach ($possiblePaths as $key => $path) {
            $exists = file_exists($path);
            $isFile = $exists ? is_file($path) : false;
            
            $debug['paths_checked'][$key] = [
                'path' => $path,
                'exists' => $exists,
                'is_file' => $isFile,
                'valid' => $exists && $isFile
            ];

            if ($exists && $isFile && !$debug['found']) {
                $debug['found'] = true;
                $debug['final_path'] = $path;
            }
        }

        return $debug;
    }

    /**
     * Temporarily increase memory limit for PDF operations
     *
     * @param string $limit Memory limit (e.g., '256M', '512M')
     * @return string Previous memory limit
     */
    public static function increaseMemoryForPdf(string $limit = '256M'): string
    {
        $previousLimit = ini_get('memory_limit');
        
        if (ini_set('memory_limit', $limit) === false) {
            Log::warning("Failed to increase memory limit", [
                'requested' => $limit,
                'current' => $previousLimit
            ]);
        } else {
            Log::info("Memory limit increased for PDF generation", [
                'previous' => $previousLimit,
                'new' => $limit
            ]);
        }
        
        return $previousLimit;
    }

    /**
     * Reset memory limit to previous value
     *
     * @param string $previousLimit The previous memory limit to restore
     */
    public static function resetMemoryLimit(string $previousLimit): void
    {
        if (ini_set('memory_limit', $previousLimit) === false) {
            Log::warning("Failed to reset memory limit", [
                'target' => $previousLimit,
                'current' => ini_get('memory_limit')
            ]);
        } else {
            Log::info("Memory limit reset", [
                'restored_to' => $previousLimit
            ]);
        }
    }
}
