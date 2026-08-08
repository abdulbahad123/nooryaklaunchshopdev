<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $subject ?? '' }}</title>
    <style>
        /* Email resets */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        
        /* Mobile styles */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; padding: 10px !important; }
            .email-card { padding: 25px 20px !important; }
            .email-header { padding: 20px 0 !important; }
        }
    </style>
</head>
<body style="background-color: #f8fafc; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc;">
        <tr>
            <td align="center" style="padding: 40px 0 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="email-container" style="max-width: 600px; width: 100%;">
                    <!-- HEADER -->
                    <tr>
                        <td align="center" class="email-header" style="padding-bottom: 25px;">
                            @if(!empty($logo_url))
                                <img src="{{ $logo_url }}" alt="{{ $website_title ?? 'Logo' }}" style="display: block; max-height: 45px; width: auto; border: 0;" />
                            @else
                                <span style="font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">{{ $website_title ?? 'Launchshop' }}</span>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- MAIN CARD -->
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); overflow: hidden;">
                            <!-- Top color bar -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td height="4" style="background-color: {{ !empty($base_color) ? '#' . str_replace('#', '', $base_color) : '#ff6f61' }}; line-height: 4px; font-size: 4px;">&nbsp;</td>
                                </tr>
                            </table>
                            
                            <!-- Content -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td class="email-card" style="padding: 40px; color: #334155; font-size: 15px; line-height: 1.6;">
                                        <!-- Inject summernote/raw HTML body with clean styling for standard tags -->
                                        <div style="font-family: inherit;">
                                            {!! $body !!}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- FOOTER -->
                    <tr>
                        <td align="center" style="padding: 30px 20px 0 20px; color: #94a3b8; font-size: 13px; line-height: 1.5; text-align: center;">
                            <p style="margin: 0 0 6px 0; font-weight: 500;">This email was sent by <strong>{{ $website_title ?? 'Launchshop' }}</strong></p>
                            <p style="margin: 0;">&copy; {{ date('Y') }} {{ $website_title ?? 'Launchshop' }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
