<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .brand {
            color: #1A1D6E;
            font-weight: 700;
        }
        .highlight {
            color: #41aef1;
        }
        .congrats-text {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .signature {
            text-align: right;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="brand">
                Decoding<span class="highlight">TheFuture</span>
            </h1>
        </div>
        <h2>Hello, {{ $name }}!</h2>
        <p class="congrats-text">
            Congratulations on successfully registering for the event! We are excited to have you join us at Decoding The Future.
        </p>
        <p class="congrats-text">
            Stay tuned for more information and updates about the event. If you have any questions, feel free to reach out to us.
        </p>
        <p class="signature">
            Best Regards,<br>
            Decoding The Future Team
        </p>
    </div>
</body>
</html>
