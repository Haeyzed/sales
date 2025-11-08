<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'Sales Pro Notification' }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles */
        body, table, td, div, p, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        /* Typography */
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f7;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header img {
            max-width: 200px;
            height: auto;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }

        .email-body h2 {
            color: #1a202c;
            font-size: 24px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .email-body h3 {
            color: #2d3748;
            font-size: 18px;
            margin: 24px 0 12px 0;
            font-weight: 600;
        }

        .email-body p {
            margin: 0 0 16px 0;
            color: #4a5568;
            font-size: 16px;
        }

        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 16px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin: 8px 0;
        }

        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #2d3748;
            width: 140px;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            color: #4a5568;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #c6f6d5;
            color: #22543d;
        }

        .status-pending {
            background-color: #fed7d7;
            color: #742a2a;
        }

        .status-paid {
            background-color: #bee3f8;
            color: #2c5282;
        }

        .status-due {
            background-color: #feebc8;
            color: #7c2d12;
        }

        .status-partial {
            background-color: #fbd38d;
            color: #744210;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .data-table thead {
            background-color: #667eea;
            color: #ffffff;
        }

        .data-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #4a5568;
            font-size: 14px;
        }

        .data-table tbody tr:hover {
            background-color: #f7fafc;
        }

        .data-table tfoot {
            background-color: #f7fafc;
            font-weight: 600;
        }

        .data-table tfoot td {
            border-top: 2px solid #667eea;
            color: #1a202c;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #667eea;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #5568d3;
        }

        .email-footer {
            background-color: #1a202c;
            padding: 30px;
            text-align: center;
            color: #a0aec0;
            font-size: 14px;
        }

        .email-footer p {
            margin: 8px 0;
            color: #a0aec0;
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 24px 0;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .email-body {
                padding: 24px 20px !important;
            }
            .email-header {
                padding: 30px 20px !important;
            }
            .data-table {
                font-size: 12px !important;
            }
            .data-table th,
            .data-table td {
                padding: 8px !important;
            }
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f7;">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background-color:#f4f4f7;">
        <tr>
            <td align="center" style="padding:20px 0;">
                <table role="presentation" class="email-container" style="width:600px;border-collapse:collapse;border:0;border-spacing:0;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            @if(isset($generalSetting) && $generalSetting->site_logo)
                                <a href="{{ url('/') }}" style="text-decoration:none;">
                                    <img src="{{ url('logo', $generalSetting->site_logo) }}" alt="{{ $generalSetting->site_title ?? 'Sales Pro' }}" style="max-width:200px;height:auto;display:block;margin:0 auto;">
                                </a>
                            @else
                                <a href="{{ url('/') }}" style="text-decoration:none;color:#ffffff;">
                                    <h1 style="color:#ffffff;margin:0;font-size:28px;font-weight:600;">{{ $generalSetting->site_title ?? 'Sales Pro' }}</h1>
                                </a>
                            @endif
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="email-body">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p style="margin:0;color:#a0aec0;font-size:14px;">
                                &copy; {{ date('Y') }} {{ $generalSetting->site_title ?? 'Sales Pro' }}. All rights reserved.
                            </p>
                            @if(isset($generalSetting) && $generalSetting->phone)
                                <p style="margin:8px 0 0 0;color:#a0aec0;font-size:12px;">
                                    Contact: {{ $generalSetting->phone }}
                                    @if($generalSetting->email)
                                        | {{ $generalSetting->email }}
                                    @endif
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

