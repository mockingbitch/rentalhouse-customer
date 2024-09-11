<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css']) <!-- Để tích hợp với Vite nếu bạn sử dụng nó -->
</head>
<body>
@inertia <!-- Đây là chỗ mà các component Vue.js sẽ được render -->

<!-- Optional: Include additional scripts or inline scripts here -->
<script>
    // Example inline script
    console.log('Hello from app.blade.php');
</script>
</body>
</html>
