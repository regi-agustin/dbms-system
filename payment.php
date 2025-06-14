<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background-color: #f0f0e8;
            border: 1px solid #ccc;
            padding: 30px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .payment-options {
            display: flex;
            gap: 10px;
        }

        .payment-option {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option:hover {
            background-color: #f8f8f8;
        }

        .payment-option.active {
            background-color: #ff8c00;
            color: white;
            border-color: #e67300;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 14px;
            color: #333;
            width: 150px;
            text-align: right;
            margin-right: 20px;
            font-weight: normal;
        }

        .form-group input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            background-color: white;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff8c00;
        }

        .form-group input:read-only {
            background-color: #f8f8f8;
        }

        .section-divider {
            height: 1px;
            background-color: #ccc;
            margin: 30px 0;
        }

        .order-summary-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            gap: 20px;
        }

        .cancel-button {
            background-color: #d3d3d3;
            border: 1px solid #999;
            padding: 15px 40px;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
        }

        .cancel-button:hover {
            background-color: #c0c0c0;
        }

        .proceed-button {
            background-color: #ff8c00;
            border: 1px solid #e67300;
            padding: 15px 40px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
        }

        .proceed-button:hover {
            background-color: #e67300;
        }

        .proceed-button:disabled {
            background-color: #ccc;
            border-color: #999;
            cursor: not-allowed;
        }

        .error-message {
            color: #d32f2f;
            font-size: 12px;
            margin-left: 170px;
            margin-top: -15px;
            margin-bottom: 15px;
            display: none;
        }

        .gcash-section {
            display: none;
        }

        .gcash-info {
            background-color: #e8f5e8;
            border: 1px solid #4caf50;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .gcash-info h3 {
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .gcash-info p {
            color: #1b5e20;
            margin-bottom: 5px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 8% auto;
            padding: 30px;
            border: 2px solid #666;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-title {
            font-size: 22px;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 20px;
        }

        .modal-message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .modal-icon {
            font-size: 48px;
            color: #4caf50;
            margin-bottom: 15px;
        }

        .modal-button {
            background-color: #ff8c00;
            border: 1px solid #e67300;
            padding: 12px 30px;
            font-size: 14px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        .modal-button:hover {
            background-color: #e67300;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }
            
            .payment-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-group label {
                width: auto;
                text-align: left;
                margin-right: 0;
                margin-bottom: 5px;
            }
            
            .error-message {
                margin-left: 0;
            }

            .modal-content {
                margin: 20% auto;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form id="paymentForm">
            <div class="payment-header">
                <div class="section-title">Payment Information</div>
                <div class="payment-options">
                    <button type="button" class="payment-option active" id="creditCardOption" onclick="switchPaymentMethod('credit')">Credit / Debit Card</button>
                    <button type="button" class="payment-option" id="gcashOption" onclick="switchPaymentMethod('gcash')">GCash</button>
                </div>
            </div>

            <!-- Credit Card Section -->
            <div id="creditCardSection" class="credit-card-section">
                <div class="form-group">
                    <label for="cardNumber">Card Number:</label>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
                </div>
                <div class="error-message" id="cardNumberError">Please enter a valid card number</div>

                <div class="form-group">
                    <label for="cardName">Cardholder Name:</label>
                    <input type="text" id="cardName" name="cardName" required>
                </div>
                <div class="error-message" id="cardNameError">Please enter the cardholder name</div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="error-message" id="addressError">Please enter your address</div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="error-message" id="cityError">Please enter your city</div>
            </div>

            <!-- GCash Section -->
            <div id="gcashSection" class="gcash-section">
                <div class="gcash-info">
                    <h3>Pay via GCash</h3>
                    <p>Send payment to: <strong>09123456789</strong></p>
                    <p>Account Name: <strong>Cinema Booking</strong></p>
                    <p>Please enter your GCash reference number below after payment</p>
                </div>

                <div class="form-group">
                    <label for="gcashRef">Reference Number:</label>
                    <input type="text" id="gcashRef" name="gcashRef" placeholder="Enter GCash reference number" required>
                </div>
                <div class="error-message" id="gcashRefError">Please enter your GCash reference number</div>

                <div class="form-group">
                    <label for="gcashName">Sender Name:</label>
                    <input type="text" id="gcashName" name="gcashName" placeholder="Name used in GCash" required>
                </div>
                <div class="error-message" id="gcashNameError">Please enter the sender name</div>
            </div>

            <div class="section-divider"></div>

            <div class="order-summary-title">Order Summary</div>
            
            <div class="form-group">
                <label for="amount">Total Amount:</label>
                <input type="text" id="amount" name="amount" value="₱700.00" readonly>
            </div>

            <div class="button-container">
                <button type="button" class="cancel-button" onclick="goBack()">Cancel</button>
                <button type="submit" class="proceed-button" id="submitButton">Proceed</button>
            </div>
        </form>
    </div>

    <!-- Payment Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon">✓</div>
            <div class="modal-title">Payment Successful!</div>
            <div class="modal-message">
                Your payment has been processed successfully!<br><br>
                <strong>Transaction Details:</strong><br>
                Amount: <span id="modalAmount">₱700.00</span><br>
                Payment Method: <span id="modalPaymentMethod">Credit Card</span><br><br>
                Thank you for your purchase. You will be redirected to the homepage.
            </div>
            <button class="modal-button" onclick="closeModalAndRedirect()">OK</button>
        </div>
    </div>

    <script>
        let currentPaymentMethod = 'credit';

        // Get total amount from URL parameters
        function getTotalAmount() {
            const urlParams = new URLSearchParams(window.location.search);
            const totalFromUrl = urlParams.get('total');
            
            if (totalFromUrl) {
                return parseFloat(totalFromUrl);
            }
            
            return 700.00;
        }

        // Set the total amount on page load
        window.addEventListener('load', function() {
            const totalAmount = getTotalAmount();
            document.getElementById('amount').value = '₱' + totalAmount.toFixed(2);
        });

        // Switch payment method
        function switchPaymentMethod(method) {
            currentPaymentMethod = method;
            
            // Update button styles
            document.getElementById('creditCardOption').classList.remove('active');
            document.getElementById('gcashOption').classList.remove('active');
            
            if (method === 'credit') {
                document.getElementById('creditCardOption').classList.add('active');
                document.getElementById('creditCardSection').style.display = 'block';
                document.getElementById('gcashSection').style.display = 'none';
                
                // Set required attributes for credit card fields
                document.getElementById('cardNumber').required = true;
                document.getElementById('cardName').required = true;
                document.getElementById('address').required = true;
                document.getElementById('city').required = true;
                document.getElementById('gcashRef').required = false;
                document.getElementById('gcashName').required = false;
            } else {
                document.getElementById('gcashOption').classList.add('active');
                document.getElementById('creditCardSection').style.display = 'none';
                document.getElementById('gcashSection').style.display = 'block';
                
                // Set required attributes for GCash fields
                document.getElementById('cardNumber').required = false;
                document.getElementById('cardName').required = false;
                document.getElementById('address').required = false;
                document.getElementById('city').required = false;
                document.getElementById('gcashRef').required = true;
                document.getElementById('gcashName').required = true;
            }
            
            // Clear all error messages
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(el => el.style.display = 'none');
        }

        // Card number formatting
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Validation functions
        function validateCardNumber(cardNumber) {
            const cleaned = cardNumber.replace(/\s/g, '');
            return cleaned.length >= 13 && cleaned.length <= 19 && /^\d+$/.test(cleaned);
        }

        function showError(fieldId, show) {
            const errorElement = document.getElementById(fieldId + 'Error');
            if (errorElement) {
                errorElement.style.display = show ? 'block' : 'none';
            }
        }

        // Form submission
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submitted - currentPaymentMethod:', currentPaymentMethod);
            
            // Reset all error messages
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(el => el.style.display = 'none');
            
            let isValid = true;
            
            if (currentPaymentMethod === 'credit') {
                // Validate credit card fields
                const cardNumber = document.getElementById('cardNumber').value;
                console.log('Validating card number:', cardNumber);
                if (!validateCardNumber(cardNumber)) {
                    showError('cardNumber', true);
                    isValid = false;
                }
                
                if (!document.getElementById('cardName').value.trim()) {
                    showError('cardName', true);
                    isValid = false;
                }
                
                if (!document.getElementById('address').value.trim()) {
                    showError('address', true);
                    isValid = false;
                }
                
                if (!document.getElementById('city').value.trim()) {
                    showError('city', true);
                    isValid = false;
                }
            } else {
                // Validate GCash fields
                if (!document.getElementById('gcashRef').value.trim()) {
                    showError('gcashRef', true);
                    isValid = false;
                }
                
                if (!document.getElementById('gcashName').value.trim()) {
                    showError('gcashName', true);
                    isValid = false;
                }
            }
            
            console.log('Form validation result:', isValid);
            
            if (isValid) {
                // Update modal with current payment details
                const totalAmount = document.getElementById('amount').value;
                const paymentMethod = currentPaymentMethod === 'credit' ? 'Credit / Debit Card' : 'GCash';
                
                document.getElementById('modalAmount').textContent = totalAmount;
                document.getElementById('modalPaymentMethod').textContent = paymentMethod;
                
                // Show confirmation modal
                document.getElementById('confirmationModal').style.display = 'block';
            }
        });

        function goBack() {
            const urlParams = new URLSearchParams(window.location.search);
            const movie = urlParams.get('movie');

            if (movie) {
                window.location.href = `${movie}.php`;
            } else {
                window.location.href = 'homepage.php';
            }
        }

        function closeModalAndRedirect() {
            document.getElementById('confirmationModal').style.display = 'none';
            window.location.href = 'homepage.php';
        }

        // Real-time validation
        document.querySelectorAll('input').forEach(element => {
            element.addEventListener('blur', function() {
                const fieldName = this.name;
                let isValid = true;
                
                switch(fieldName) {
                    case 'cardNumber':
                        if (currentPaymentMethod === 'credit') {
                            isValid = validateCardNumber(this.value);
                            showError('cardNumber', !isValid);
                        }
                        break;
                    case 'cardName':
                        if (currentPaymentMethod === 'credit') {
                            isValid = this.value.trim() !== '';
                            showError('cardName', !isValid);
                        }
                        break;
                    case 'address':
                        if (currentPaymentMethod === 'credit') {
                            isValid = this.value.trim() !== '';
                            showError('address', !isValid);
                        }
                        break;
                    case 'city':
                        if (currentPaymentMethod === 'credit') {
                            isValid = this.value.trim() !== '';
                            showError('city', !isValid);
                        }
                        break;
                    case 'gcashRef':
                        if (currentPaymentMethod === 'gcash') {
                            isValid = this.value.trim() !== '';
                            showError('gcashRef', !isValid);
                        }
                        break;
                    case 'gcashName':
                        if (currentPaymentMethod === 'gcash') {
                            isValid = this.value.trim() !== '';
                            showError('gcashName', !isValid);
                        }
                        break;
                }
            });
        });
    </script>
</body>
</html>