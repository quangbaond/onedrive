<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/memory_limit.css') }}">
</head>
<body>

<h1>403 Bạn đã hết dung lượng để sử dụng! Vui lòng liên hệ admin</h1>
<section class="error-container">
    <span class="four"><span class="screen-reader-text">4</span></span>
    <span class="zero"><span class="screen-reader-text">0</span></span>
    <span class="four"><span class="screen-reader-text">3</span></span>
</section>
<div class="link-container">
    <a target="_blank" href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="more-link">Quay lại</a>
</div>
</body>
</html>
