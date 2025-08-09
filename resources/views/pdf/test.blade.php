@php
    use App\Helpers\QRCodeHelper;
@endphp
<!DOCTYPE html>
<html>
<body>
@php
    $testQrPath = QRCodeHelper::getSafeImagePath('qr_codes/qr_code_9f77e5b4-25b7-4ee7-bf1b-3dd9369f7d11.png');
@endphp
@if($testQrPath)
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($testQrPath)) }}" style="width:150px;">
    <img src="file://{{ $testQrPath }}" style="width:150px;" alt="QR Code">
@else
    <p>QR Code not found</p>
@endif

</body>
</html>
