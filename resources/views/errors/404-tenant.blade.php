<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>404 | Portal Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }

        .wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            padding: 50px 35px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 450px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        /* Funny Character / Gudda Animation */
        .gudda-box {
            font-size: 80px;
            margin-bottom: 10px;
            display: inline-block;
            animation: laugh 1s infinite alternate;
        }

        @keyframes laugh {
            0% {
                transform: translateY(0) scale(1);
            }

            100% {
                transform: translateY(-15px) scale(1.1);
            }
        }

        .error-code {
            font-size: 70px;
            font-weight: 900;
            color: #ff4757;
            margin: 0;
            line-height: 1;
            letter-spacing: -2px;
        }

        h1 {
            font-size: 26px;
            margin: 10px 0;
            color: #2f3542;
        }

        p {
            color: #57606f;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 15px;
        }

        .domain {
            display: block;
            background: #f1f2f6;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 700;
            color: #3742fa;
            margin: 10px 0;
            word-break: break-all;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #3742fa;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(55, 66, 250, 0.3);
        }

        .btn:hover {
            background: #2f3542;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .note {
            margin-top: 25px;
            font-size: 13px;
            color: #a4b0be;
            border-top: 1px dashed #ced4da;
            padding-top: 15px;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="card">

            <div class="gudda-box">😂</div>

            <div class="error-code">404</div>

            <h1>Opps! Page Not Found</h1>

            <p>
                Haha! It looks like the portal
                <span class="domain">{{ request()->getHost() }}</span>
                is playing hide and seek. It doesn't exist!
            </p>

            <a href="{{ config('app.url') }}" class="btn">
                🏠 Take Me Home
            </a>

            <div class="note">
                Don't worry, even the best of us get lost sometimes.
            </div>

        </div>
    </div>

</body>

</html>
