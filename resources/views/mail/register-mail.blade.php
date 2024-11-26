<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to DocBook</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
            color: #333333;
        }
        .content h1 {
            color: #6a11cb;
            margin-bottom: 20px;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #666666;
        }
        .btn-primary {
            background-color: #6a11cb;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            color: white !important;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-primary:hover {
            background-color: #2575fc;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Welcome to DocBook!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Hi {{ $user->name }},</h1>
            <p>Thank you for registering on our platform! We're excited to have you join our community. Here's a quick overview of what you can do on our site:</p>
            <ul>
                <li>Explore a variety of features tailored to your needs.</li>
                <li>Stay updated with the latest updates and resources.</li>
                <li>Engage with a community of like-minded individuals.</li>
            </ul>
            <p>To get started, click the button below:</p>
            <a href="{{ url('http://appointment_system.test/') }}" class="btn btn-primary">Get Started</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>If you have any questions or need assistance, feel free to contact us at <a href="mailto:support@docbook.com">support@docbook.com</a>.</p>
            <p>&copy; {{ date('Y') }} Your Website Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
