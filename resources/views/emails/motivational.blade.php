<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Daily Dose of Motivation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }

        .email-body {
            padding: 40px 30px;
        }

        .quote-container {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-left: 4px solid #667eea;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .quote-icon {
            font-size: 48px;
            color: #667eea;
            opacity: 0.3;
            margin-bottom: 10px;
        }

        .quote-content {
            font-size: 22px;
            font-style: italic;
            color: #333333;
            line-height: 1.6;
            margin: 0 0 15px 0;
        }

        .quote-author {
            font-size: 16px;
            color: #666666;
            font-weight: 600;
            text-align: right;
            margin: 0;
        }

        .motivation-text {
            text-align: center;
            color: #555555;
            font-size: 18px;
            margin: 30px 0;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            margin: 20px auto;
            text-align: center;
        }

        .button-container {
            text-align: center;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }

        .email-footer p {
            margin: 5px 0;
        }

        .footer-link {
            color: #667eea;
            text-decoration: none;
        }

        .date-badge {
            display: inline-block;
            background-color: #667eea;
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .icon {
            display: inline-block;
            margin-right: 8px;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
            }

            .email-header,
            .email-body,
            .email-footer {
                padding: 20px;
            }

            .quote-content {
                font-size: 18px;
            }

            .email-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🌟 DailyDrive</h1>
            <p>Your Daily Dose of Motivation</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div style="text-align: center;">
                <span class="date-badge">
                    📅 {{ now()->format('l, F j, Y') }}
                </span>
            </div>

            <p class="motivation-text">
                Good morning! Start your day with this powerful thought:
            </p>

            <!-- Quote Container -->
            <div class="quote-container">
                <div class="quote-icon">"</div>
                <p class="quote-content">{{ $quote->content }}</p>
                @if($quote->author)
                    <p class="quote-author">— {{ $quote->author }}</p>
                @endif
            </div>

            <p class="motivation-text">
                <strong>Have a productive day!</strong><br>
                Make today count by focusing on your goals and taking small steps forward.
            </p>

            <!-- Call to Action -->
            <div class="button-container">
                <a href="{{ url('/dashboard') }}" class="cta-button">
                    📊 View Your Dashboard
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>DailyDrive</strong></p>
            <p>Stay productive, stay driven.</p>
            <p style="margin-top: 15px; color: #999999; font-size: 12px;">
                This is an automated motivational email from DailyDrive.<br>
                You're receiving this because you're awesome! 🚀
            </p>
            <p style="margin-top: 10px;">
                <a href="{{ url('/dashboard') }}" class="footer-link">Dashboard</a> •
                <a href="{{ url('/goals') }}" class="footer-link">Goals</a> •
                <a href="{{ url('/tasks') }}" class="footer-link">Tasks</a>
            </p>
        </div>
    </div>
</body>

</html>