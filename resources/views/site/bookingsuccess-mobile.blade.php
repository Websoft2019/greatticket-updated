<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Success</title>
</head>
<body>
    <p style=" width:100%; text-align: center">
        <h3 style="color: red">Thank You for your payment</h3>
        <span style="color: green">Go back to view your ticket information</span>
    </p>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function sendToFlutter(message) {
  if (window.Toaster) {
    window.Toaster.postMessage(message);
    alert('1');
  } else {
    alert('2');
    console.log("Toaster channel is not available.");
  }
}
    </script>
</body>
</html>
