<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $ticket_details['ticket_number'] }}</title>
    <style>
        /* تحسين التصميم */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            color: #333;
        }
        .receipt-container {
            width: 80%;
            max-width: 400px;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-header h1 {
            font-size: 24px;
            margin: 0;
            color: #007bff;
        }
        .receipt-header p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }
        .receipt-body {
            margin: 20px 0;
        }
        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .receipt-item:last-child {
            border-top: 1px dashed #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }
        .receipt-item strong {
            color: #007bff;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
        .receipt-footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Receipt #{{ $ticket_details['ticket_number'] }}</h1>
            <p>{{ $ticket_details['business_details']['name'] ?? 'Business Name' }}</p>
        </div>

        <div class="receipt-body">
            <div class="receipt-item">
                <span>Product Count:</span>
                <strong>{{ $ticket_details['product_count'] }}</strong>
            </div>
            <div class="receipt-item">
                <span>Number of Days:</span>
                <strong>{{ $ticket_details['number_of_day'] }}</strong>
            </div>
            
            <div class="receipt-item">
                <span>Price:</span>
                <strong>{{ $ticket_details['price'] }} {{ $ticket_details['business_details']['currency_symbol'] ?? '$' }}</strong>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Thank you for your business!</p>
            <p>{{ $ticket_details['business_details']['address'] ?? 'Business Address' }}</p>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
