<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Suspended</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 24px;
            color: #f8fafc;
        }
        .suspension-card {
            background: rgba(30, 41, 59, 0.85);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
            max-width: 540px;
            width: 100%;
            padding: 48px 36px;
            text-align: center;
        }
        .icon-box {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.15);
            border: 2px solid rgba(239, 68, 68, 0.4);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 38px;
        }
    </style>
</head>
<body>
    <div class="suspension-card">
        <div class="icon-box">
            <i class="fa-solid fa-ban"></i>
        </div>
        <h2 class="fw-bold mb-2 text-white">This Website is Suspended</h2>
        <p class="text-secondary mb-4" style="font-size: 15px; line-height: 1.6;">
            This website has been temporarily suspended by administration.
        </p>
        <div class="p-3 rounded-3 mb-4" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 13.5px; color: #94a3b8;">
            If you are the owner of this website, please check your registered email or contact administration support to reactivate your workspace.
        </div>
    </div>
</body>
</html>