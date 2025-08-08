<!DOCTYPE html>
<html>
<body>
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/qr_codes/qr_code_9f77e5b4-25b7-4ee7-bf1b-3dd9369f7d11.png'))) }}" style="width:150px;">


<img src="file://{{ storage_path('app/public/qr_codes/qr_code_9f77e5b4-25b7-4ee7-bf1b-3dd9369f7d11.png') }}" style="width:150px;" alt="QR Code">

</body>
</html>
