<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agency->site_title ?? 'Custom Domain Status' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .status-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            max-width: 550px;
            width: 100%;
            padding: 45px 35px;
            text-align: center;
        }
        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 36px;
        }
        .icon-box.pending {
            background: #fff7ed;
            color: #f97316;
        }
        .icon-box.rejected {
            background: #fef2f2;
            color: #ef4444;
        }
        .agency-logo {
            max-height: 50px;
            margin-bottom: 20px;
        }
        .btn-custom {
            background: #4f46e5;
            color: white;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background: #4338ca;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="status-card">
        @if(!empty($agency->logo_image) && ($agency->logo_type ?? 'image') === 'image')
            <img src="{{ asset($agency->logo_image) }}" alt="Logo" class="agency-logo">
        @else
            <h2 class="fw-bold text-dark mb-4">{{ $agency->logo_text ?? $agency->site_title ?? 'DesignAGENCY' }}</h2>
        @endif

        @if((int)$agency->custom_domain_status === 2)
            <div class="icon-box rejected">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2">Custom Domain Connection Rejected</h3>
            <p class="text-muted mb-4">{{ $statusMsg }}</p>
        @else
            <div class="icon-box pending">
                <i class="fa-solid fa-clock"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2">Custom Domain Verification Pending</h3>
            <p class="text-muted mb-4">{{ $statusMsg }}</p>
        @endif

        <div class="p-3 bg-light rounded-3 mb-4 text-start small text-secondary">
            <i class="fa-solid fa-globe text-primary me-2"></i><strong>Domain Name:</strong> {{ $agency->custom_domain }}<br>
            <i class="fa-solid fa-link text-primary me-2 mt-2"></i><strong>Status:</strong> 
            @if((int)$agency->custom_domain_status === 1)
                <span class="badge bg-success">Connected / Active</span>
            @elseif((int)$agency->custom_domain_status === 2)
                <span class="badge bg-danger">Rejected</span>
            @else
                <span class="badge bg-warning text-dark">Pending Verification</span>
            @endif
        </div>

        @if($customer && !empty($customer->subdomain))
            <a href="{{ route('website-builder.subdomain.site', ['subdomain' => $customer->subdomain]) }}" class="btn-custom">
                <i class="fa-solid fa-arrow-up-right-from-square me-2"></i>Visit Launch Subdomain Site
            </a>
        @endif
    </div>
</body>
</html>
