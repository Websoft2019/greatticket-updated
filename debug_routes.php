<?php

// Debug routes for PDF image troubleshooting
// Add these temporarily to routes/web.php for debugging

use App\Helpers\QRCodeHelper;
use App\Models\TicketUser;

Route::get('/debug-pdf-images', function () {
    // Get a sample ticket user with QR image
    $ticketUser = TicketUser::whereNotNull('qr_image')->first();
    
    if (!$ticketUser) {
        return response()->json(['error' => 'No ticket user with QR image found']);
    }

    $debug = [
        'ticket_user_id' => $ticketUser->id,
        'qr_image_field' => $ticketUser->qr_image,
        'qr_debug' => QRCodeHelper::debugImagePath($ticketUser->qr_image),
        'storage_path_info' => [
            'storage_path' => storage_path('app/public'),
            'storage_exists' => is_dir(storage_path('app/public')),
            'public_storage_link' => public_path('storage'),
            'public_storage_exists' => is_dir(public_path('storage')),
        ],
        'suggested_paths' => [
            'primary' => storage_path('app/public/' . $ticketUser->qr_image),
            'alternative1' => public_path('storage/' . $ticketUser->qr_image),
            'alternative2' => storage_path('app/' . $ticketUser->qr_image),
        ]
    ];

    // Test logo path too
    $debug['logo_debug'] = QRCodeHelper::debugImagePath('site/images/logo.png');
    
    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/debug-storage-link', function () {
    $info = [
        'storage_link_exists' => is_link(public_path('storage')),
        'storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
        'storage_directory_exists' => is_dir(storage_path('app/public')),
        'public_directory_writable' => is_writable(public_path()),
        'storage_permissions' => substr(sprintf('%o', fileperms(storage_path('app/public'))), -4),
        'qr_codes_directory' => [
            'exists' => is_dir(storage_path('app/public/qr_codes')),
            'writable' => is_writable(storage_path('app/public/qr_codes')),
            'files_count' => is_dir(storage_path('app/public/qr_codes')) ? count(glob(storage_path('app/public/qr_codes/*'))) : 0
        ]
    ];
    
    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});
