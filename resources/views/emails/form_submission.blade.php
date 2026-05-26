<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f6f8;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e4e8;
        }
        .email-header {
            background-color: #0c5930; /* Mukmin Brand Green */
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #b3913b; /* Accent Gold */
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .email-header p {
            color: #d1ffd6;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .intro-text {
            font-size: 16px;
            margin-bottom: 30px;
            color: #4a5568;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 15px;
        }
        .form-table th, .form-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
            vertical-align: top;
        }
        .form-table th {
            width: 35%;
            color: #718096;
            font-weight: 600;
            background-color: #f7fafc;
        }
        .form-table td {
            color: #2d3748;
        }
        .form-table tr:hover td {
            background-color: #fcfdfd;
        }
        .footer-action {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0c5930;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            font-size: 15px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #083c20;
        }
        .email-footer {
            background-color: #f7fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer a {
            color: #0c5930;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 15px 0;
            }
            .email-body {
                padding: 25px 15px;
            }
            .form-table th, .form-table td {
                display: block;
                width: 100% !important;
                box-sizing: border-box;
            }
            .form-table th {
                border-bottom: none;
                padding-bottom: 4px;
                padding-top: 12px;
            }
            .form-table td {
                padding-top: 4px;
                padding-bottom: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header Section -->
            <div class="email-header">
                <h1>Pertubuhan Gabungan MUKMIN Nasional</h1>
                <p>{{ $formName }}</p>
            </div>

            <!-- Body Section -->
            <div class="email-body">
                @if($isForSupport)
                    <div class="intro-text">
                        Hello Admin,<br><br>
                        A new submission has been received for the <strong>{{ $formName }}</strong> form. Below are the complete submission details:
                    </div>
                @else
                    <div class="intro-text">
                        Assalamu alaikum,<br><br>
                        Thank you for registering. Your submission for <strong>{{ $formName }}</strong> has been successfully received. Below is a copy of the details you submitted for your reference:
                    </div>
                @endif

                <!-- Form Content Table -->
                <table class="form-table">
                    <tbody>
                        @foreach($formattedData as $item)
                            <tr>
                                <th>{{ $item['label'] }}</th>
                                <td>
                                    @if(isset($item['is_html']) && $item['is_html'])
                                        {!! $item['value'] !!}
                                    @else
                                        {{ $item['value'] }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if(!$isForSupport)
                    <div class="intro-text" style="margin-top: 20px; margin-bottom: 0;">
                        Our team is reviewing your submission and will get in touch with you shortly. If you have any urgent queries, please contact us at <a href="mailto:support@mukmin.org" style="color: #0c5930; text-decoration: none; font-weight: 500;">support@mukmin.org</a>.
                    </div>
                @endif
            </div>

            <!-- Footer Section -->
            <div class="email-footer">
                <p>&copy; 2026 Pertubuhan Gabungan MUKMIN Nasional. All rights reserved.</p>
                <p>Website: <a href="http://mukmin.org" target="_blank">mukmin.org</a> | Email: <a href="mailto:support@mukmin.org">support@mukmin.org</a></p>
            </div>
        </div>
    </div>
</body>
</html>
