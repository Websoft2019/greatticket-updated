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
}
