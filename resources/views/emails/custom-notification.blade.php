<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f9;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Header */
        .header {
            background-color: #ffffff;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #eeeeee;
        }

        .logo {
            max-width: 180px;
            height: auto;
        }

        /* Content Body */
        .content {
            padding: 40px 30px;
            line-height: 1.6;
            color: #333333;
        }

        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .message-body {
            font-size: 16px;
            margin-bottom: 30px;
            white-space: pre-line;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 20px 0;
        }

        /* Footer & Contact Grid */
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            border-top: 1px solid #eeeeee;
        }

        .contact-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contact-column {
            width: 33.33%;
            vertical-align: top;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #555555;
            border-right: 1px solid #e0e0e0;
        }

        .contact-column:last-child {
            border-right: none;
        }

        .contact-title {
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            font-size: 11px;
            margin-bottom: 8px;
            display: block;
        }

        .contact-link {
            color: #3498db;
            text-decoration: none;
        }

        .legal {
            text-align: center;
            font-size: 11px;
            color: #999999;
            margin-top: 20px;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
            line-height: 1.5;
        }

        /* MOBILE RESPONSIVE OVERRIDE */
        @media only screen and (max-width: 600px) {
            .main {
                width: 95% !important;
            }

            .contact-column {
                display: block !important;
                width: 100% !important;
                border-right: none !important;
                border-bottom: 1px solid #e0e0e0;
                padding: 15px 0 !important;
            }

            .contact-column:last-child {
                border-bottom: none !important;
            }

            .content {
                padding: 25px 20px !important;
            }
        }
    </style>
</head>

<body>
    <center class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <img src="{{ $logoPath }}" alt="Vacay Guider" class="logo">
                </td>

            </tr>

            <tr>
                <td class="content">
                    @if ($greeting)
                        <div class="greeting">{{ $greeting }}</div>
                    @endif

                    <div class="message-body">
                        {!! nl2br(e($messageText)) !!}
                    </div>

                    @if ($footerText)
                        <div class="divider"></div>
                        <div style="font-style: italic; color: #777777; font-size: 14px;">
                            {!! nl2br(e($footerText)) !!}
                        </div>
                    @endif
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <table class="contact-table">
                        <tr>
                            <td class="contact-column">
                                <span class="contact-title">Visit Our Office</span>
                                22/14C, Asarappa Road,<br>
                                Negombo, Sri Lanka
                            </td>
                            <td class="contact-column">
                                <span class="contact-title">Email Us</span>
                                <a href="mailto:info@vacayguider.com" class="contact-link">info@vacayguider.com</a><br>
                                <a href="https://www.vacayguider.com" class="contact-link">www.vacayguider.com</a>
                            </td>
                            <td class="contact-column">
                                <span class="contact-title">Call Us</span>
                                +94 114 272 372<br>
                                +94 711 999 444<br>
                                +94 777 035 325
                            </td>
                        </tr>
                    </table>

                    <div class="legal">
                        <strong>Vacay Guider (Pvt) Ltd.</strong> — Your Trusted Travel Partner<br>
                        © {{ date('Y') }} Vacay Guider. All rights reserved.<br>
                        <span style="font-size: 10px;">You are receiving this email because you are a registered
                            customer of Vacay Guider.</span>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
