<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        .email-container {
            width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        h1, h2 {
            color: #082755;
            margin: 0;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
            margin-top: 10px;
            text-decoration: underline;
        }
        p {
            color: #777;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
        }

        .medicine-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .medicine-table th, .medicine-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        .medicine-table th {
            background-color: #082755;
            color: #fff;
        }
        .medicine-table td {
            background-color: #f9f9f9;
        }

        .price-breakdown {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e0e0e0;
        }
        .price-breakdown table {
            width: 100%;
        }
        .price-breakdown td {
            padding: 5px 0;
        }
        .price-breakdown .label {
            color: #082755;
            font-weight: bold;
            text-align: left;
        }
        .price-breakdown .amount {
            text-align: right;
            color: #082755;
        }

        .price-breakdown .total {
            font-size: 16px;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 30px;
        }
        .footer a {
            color: #082755;
            text-decoration: none;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <table role="presentation" class="email-container">
        <tr>
            <td class="header">
                <img src="https://pk.communityhealthcareclinics.com/assets/new_frontend/logo.png" alt="communityhealthcareclinics">
            </td>
        </tr>

        <tr>
            <td class="order-details">
                <h2>Order Confirmation</h2>
                <p>Thank you for your order! We are processing your medicine order and will notify you once it's shipped.</p>
            </td>
        </tr>

        <tr>
            <td>
                <table class="medicine-table">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Paracetamol 500mg</td>
                            <td>Pain Relief</td>
                            <td>2</td>
                            <td>$10.00</td>
                        </tr>
                        <tr>
                            <td>Ibuprofen 200mg</td>
                            <td>Anti-inflammatory</td>
                            <td>1</td>
                            <td>$5.00</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td class="price-breakdown">
                <table>
                    <tr>
                        <td class="label">Tax:</td>
                        <td class="amount">$1.50</td>
                    </tr>
                    <tr>
                        <td class="label">Discount:</td>
                        <td class="amount">-$5.00</td>
                    </tr>
                    <tr>
                        <td class="label">Total Price:</td>
                        <td class="amount">$25.00</td>
                    </tr>
                    <tr class="total">
                        <td class="label"><strong>Grand Total:</strong></td>
                        <td class="amount"><strong>$21.50</strong></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td class="footer">
                <p>If you have any questions, please contact us at <a href="mailto:support@communityhealthcareclinics.com">support@communityhealthcareclinics.com</a>.</p>
                <p>&copy; 2024 Your Company. All Rights Reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
