<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats</title>
    <style>
        /* Import Inter font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
          Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        background-color: #f9fafb;
        color: #3b4252; /* soft dark slate */
    }

    .main-container {
        max-width: 800px;
        margin: 20px auto;
        background: linear-gradient(135deg, #ffffff 0%, #e0e6ef 100%);
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .main-container:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
    }

    .movie-info {
        display: flex;
        padding: 20px;
        gap: 20px;
        background-color: #f3f6fc;
        border-bottom: 1px solid #cbd5e1;
        color: #2e3440; /* deeper slate */
    }

    .movie-poster {
        width: 140px;
        height: 160px;
        background: linear-gradient(45deg, #ff7e5f, #feb47b);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fefefe;
        font-weight: 700;
        font-size: 12px;
        text-align: center;
        box-shadow: 0 4px 8px rgb(254 180 123 / 0.5);
    }

    .movie-img {
        flex-shrink: 0;        
        width: 120px;          
        height: auto;         
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(254, 180, 123, 0.5);
        object-fit: cover;     
        margin-left: 215px;
    }

    .movie-details h2 {
        color: #5e81ac; /* muted blue */
        font-size: 20px;
        margin-bottom: 5px;
    }

    .movie-title {
        color: #bf616a; /* warm rose */
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .movie-rating,
    .showtime,
    .ticket-price {
        color: #4c566a; /* slate gray */
        font-size: 14px;
        margin-bottom: 5px;
    }

    .ticket-price {
        color: #d08770; /* soft orange */
        font-weight: 700;
    }

    .theater-container {
        padding: 30px;
        text-align: center;
        color: #434c5e; /* dark grayish blue */
    }

    .screen-label {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 8px;
        color: #3b4252; /* slate */
        margin-bottom: 30px;
        text-shadow: 0 0 4px rgba(59, 66, 82, 0.15);
    }

    .theater-layout {
        display: inline-block;
        border: 2px solid #cbd5e1;
        padding: 20px;
        background-color: #f7fafc;
        border-radius: 12px;
        box-shadow: inset 0 0 8px rgb(203 213 225 / 0.3);
    }

    .row {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        justify-content: center;
    }

    .row-label,
    .row-label-right {
        width: 20px;
        font-weight: 700;
        color: #bf616a; /* rose hue */
        margin: 0 10px;
        user-select: none;
    }

    .seat {
        width: 22px;
        height: 22px;
        margin: 3px;
        border-radius: 2px;
        box-shadow: 0 2px 4px rgb(0 0 0 / 0.15);
        cursor: pointer;
        transition: background-color 0.25s ease, border-color 0.25s ease,
            transform 0.25s ease, box-shadow 0.25s ease;
        border: 2px solid transparent;
        outline-offset: 2px;
    }

    .seat.available {
        background-color: #a3be8c; /* soft green */
        border-color: #668a2d;
    }

    .seat.occupied {
        background-color: #ebcb8b; /* muted orange */
        border-color: #d08770;
        cursor: not-allowed;
        filter: grayscale(65%);
        box-shadow: none;
    }

    .seat.selected {
        background-color: #ebcb8b; /* accent gold */
        border-color: #d08770;
        box-shadow: 0 0 12px 3px #d0877033;
    }

    .seat:hover:not(.occupied),
    .seat:focus-visible:not(.occupied) {
        transform: scale(1.01);
        border-color: #bf616a;
        box-shadow: 0 4px 8px rgba(191, 97, 106, 0.6);
        outline: none;
    }

    .selection-info {
        margin-top: 30px;
        padding: 20px;
        background-color: #f3f6fc;
        border-radius: 12px;
        color: #4c566a;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.02em;
        box-shadow: 0 2px 6px rgb(76 86 106 / 0.1);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .selected-seats {
        color: #bf616a;
        font-weight: 700;
    }

    .total-price {
        font-weight: 700;
        font-size: 20px;
        color: #d08770;
        font-variant-numeric: tabular-nums;
    }

    .quantity {
        justify-content: space-between;
        margin-top: 15px;
        display: flex;
    }

    .buttons {
        display: flex;
        justify-content: center;
        gap: 18px;
        margin-top: 24px;
        padding-bottom: 24px;
    }

    .btn {
        padding: 12px 26px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 700;
        transition: background-color 0.3s ease, transform 0.2s ease;
        box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
        user-select: none;
    }

    .btn-back {
        background-color: #81a1c1;
        color: #f9fafb;
        text-decoration: none;
    }

    .btn-back:hover,
    .btn-back:focus-visible {
        background-color: #5e81ac;
        outline: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgb(94 129 172 / 0.3);
    }

    .btn-continue {
        background-color: #a3be8c;
        color: #2e3440;
    }

    .btn-continue:hover,
    .btn-continue:focus-visible {
        background-color: #8fbcbb;
        outline: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgb(143 188 187 / 0.3);
        color: #2e3440;
    }

    .disclaimer {
        font-size: 12px;
        color: #616e87;
        text-align: center;
        margin: 20px;
        line-height: 1.4;
    }

    .legend {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin: 20px 0;
        font-size: 13px;
        color: #434c5e;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .legend-seat {
        width: 16px;
        height: 16px;
        border: 1.5px solid #cbd5e1;
        border-radius: 2px;
    }

    .legend-seat.available {
        background-color: #a3be8c;
        border-color: #668a2d;
    }

    .legend-seat.occupied {
        background-color: #FFB6C1;
        border-color: #F44336;
        box-shadow: none;
    }

    .legend-seat.selected {
        background-color: #ebcb8b;
        border-color: #d08770;
        box-shadow: 0 0 10px 3px #d0877033;
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
        background-color: rgba(59, 66, 82, 0.75);
        animation: fadeIn 0.35s ease-out forwards;
    }

    .modal-content {
        background-color: #eceff4;
        margin: 5% auto;
        padding: 0;
        border-radius: 14px;
        width: 90%;
        max-width: 520px;
        box-shadow: 0 16px 40px rgb(59 66 82 / 0.3);
        animation: slideIn 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        overflow: hidden;
        color: #2e3440;
        font-weight: 600;
    }

    .modal-header {
        background: linear-gradient(45deg, #5e81ac, #81a1c1);
        color: #eceff4;
        padding: 24px 20px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        user-select: none;
        letter-spacing: 1px;
    }

    .modal-body {
        padding: 28px 32px;
    }

    .confirmation-details {
        background-color: #d8dee9;
        border-radius: 12px;
        padding: 28px 30px;
        margin-bottom: 28px;
        box-shadow: inset 0 4px 10px rgb(0 0 0 / 0.03);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        font-size: 17px;
    }

    .detail-row:last-child {
        margin-bottom: 0;
        padding-top: 18px;
        border-top: 2px solid #b48ead;
        font-weight: 700;
        font-size: 20px;
        color: #bf616a;
    }

    .detail-label {
        color: #4c566a;
        font-weight: 700;
    }

    .detail-value {
        color: #bf616a;
        font-weight: 800;
        user-select: text;
    }

    .modal-buttons {
        display: flex;
        gap: 18px;
        justify-content: center;
    }

    .btn-modal {
        padding: 14px 36px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 17px;
        font-weight: 700;
        user-select: none;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background-color: #a3be8c;
        color: #2e3440;
    }

    .btn-done {
        background: linear-gradient(45deg, #bf616a, #d08770);
        color: #eceff4;
        box-shadow: 0 4px 12px rgb(191 97 106 / 0.6);
    }

    .btn-modal:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgb(191 97 106 / 0.8);
        outline: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-60px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .main-container {
            margin: 12px;
            border-radius: 8px;
        }

        .theater-container {
            padding: 20px;
        }

        .seat {
            width: 18px;
            height: 18px;
            margin: 2px;
        }

        .movie-info {
            flex-direction: column;
            text-align: center;
        }

        .modal-content {
            margin: 10% auto;
            width: 95%;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-buttons {
            flex-direction: column;
        }

        .btn-modal {
            width: 100%;
        }
    }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="movie-info">
            <img src="images/9.jpg" alt="Last Bullet" class="movie-poster">
            <div class="movie-details">
                <h2>Bonifacio High Street</h2>
                <div class="movie-title">Cinema 3, (IMAX) LAST BULLET</div>
                <div class="movie-rating">R-18 (Restricted 18)</div>
                <div class="showtime">Sunday, June 22, 2025 9:00 AM</div>
                <div class="ticket-price">Ticket Price: Php300.00</div>
            </div>
        </div>

        <div class="theater-container">
            <div class="screen-label">THE SCREEN IS HERE</div>
            
            <div class="theater-layout">
                <div class="row">
                    <div class="row-label">A</div>
                    <div class="seat available" data-seat="A1"></div><div class="seat available" data-seat="A2"></div><div class="seat available" data-seat="A3"></div><div class="seat available" data-seat="A4"></div><div class="seat available" data-seat="A5"></div><div class="seat available" data-seat="A6"></div><div class="seat available" data-seat="A7"></div><div class="seat available" data-seat="A8"></div><div class="seat available" data-seat="A9"></div><div class="seat available" data-seat="A10"></div><div class="seat available" data-seat="A11"></div><div class="seat available" data-seat="A12"></div><div class="seat available" data-seat="A13"></div><div class="seat available" data-seat="A14"></div><div class="seat available" data-seat="A15"></div><div class="seat available" data-seat="A16"></div><div class="seat available" data-seat="A17"></div><div class="seat available" data-seat="A18"></div><div class="seat available" data-seat="A19"></div><div class="seat available" data-seat="A20"></div>
                    <div class="row-label-right">A</div>
                </div>
                <div class="row">
                    <div class="row-label">B</div>
                    <div class="seat available" data-seat="B1"></div><div class="seat available" data-seat="B2"></div><div class="seat available" data-seat="B3"></div><div class="seat available" data-seat="B4"></div><div class="seat available" data-seat="B5"></div><div class="seat available" data-seat="B6"></div><div class="seat available" data-seat="B7"></div><div class="seat available" data-seat="B8"></div><div class="seat available" data-seat="B9"></div><div class="seat available" data-seat="B10"></div><div class="seat available" data-seat="B11"></div><div class="seat available" data-seat="B12"></div><div class="seat available" data-seat="B13"></div><div class="seat available" data-seat="B14"></div><div class="seat available" data-seat="B15"></div><div class="seat available" data-seat="B16"></div><div class="seat available" data-seat="B17"></div><div class="seat available" data-seat="B18"></div><div class="seat available" data-seat="B19"></div><div class="seat available" data-seat="B20"></div>
                    <div class="row-label-right">B</div>
                </div>
                <div class="row">
                    <div class="row-label">C</div>
                    <div class="seat available" data-seat="C1"></div><div class="seat available" data-seat="C2"></div><div class="seat available" data-seat="C3"></div><div class="seat available" data-seat="C4"></div><div class="seat available" data-seat="C5"></div><div class="seat available" data-seat="C6"></div><div class="seat available" data-seat="C7"></div><div class="seat available" data-seat="C8"></div><div class="seat available" data-seat="C9"></div><div class="seat available" data-seat="C10"></div><div class="seat available" data-seat="C11"></div><div class="seat available" data-seat="C12"></div><div class="seat available" data-seat="C13"></div><div class="seat available" data-seat="C14"></div><div class="seat available" data-seat="C15"></div><div class="seat available" data-seat="C16"></div><div class="seat available" data-seat="C17"></div><div class="seat available" data-seat="C18"></div><div class="seat available" data-seat="C19"></div><div class="seat available" data-seat="C20"></div>
                    <div class="row-label-right">C</div>
                </div>
                <div class="row">
                    <div class="row-label">D</div>
                    <div class="seat available" data-seat="D1"></div><div class="seat available" data-seat="D2"></div><div class="seat available" data-seat="D3"></div><div class="seat available" data-seat="D4"></div><div class="seat available" data-seat="D5"></div><div class="seat available" data-seat="D6"></div><div class="seat available" data-seat="D7"></div><div class="seat available" data-seat="D8"></div><div class="seat available" data-seat="D9"></div><div class="seat available" data-seat="D10"></div><div class="seat available" data-seat="D11"></div><div class="seat available" data-seat="D12"></div><div class="seat available" data-seat="D13"></div><div class="seat available" data-seat="D14"></div><div class="seat available" data-seat="D15"></div><div class="seat available" data-seat="D16"></div><div class="seat available" data-seat="D17"></div><div class="seat available" data-seat="D18"></div><div class="seat available" data-seat="D19"></div><div class="seat available" data-seat="D20"></div>
                    <div class="row-label-right">D</div>
                </div>
                <div class="row">
                    <div class="row-label">E</div>
                    <div class="seat available" data-seat="E1"></div><div class="seat available" data-seat="E2"></div><div class="seat available" data-seat="E3"></div><div class="seat available" data-seat="E4"></div><div class="seat available" data-seat="E5"></div><div class="seat available" data-seat="E6"></div><div class="seat available" data-seat="E7"></div><div class="seat available" data-seat="E8"></div><div class="seat available" data-seat="E9"></div><div class="seat available" data-seat="E10"></div><div class="seat available" data-seat="E11"></div><div class="seat available" data-seat="E12"></div><div class="seat available" data-seat="E13"></div><div class="seat available" data-seat="E14"></div><div class="seat available" data-seat="E15"></div><div class="seat available" data-seat="E16"></div><div class="seat available" data-seat="E17"></div><div class="seat available" data-seat="E18"></div><div class="seat available" data-seat="E19"></div><div class="seat available" data-seat="E20"></div>
                    <div class="row-label-right">E</div>
                </div>
                <div class="row">
                    <div class="row-label">F</div>
                    <div class="seat available" data-seat="F1"></div><div class="seat available" data-seat="F2"></div><div class="seat available" data-seat="F3"></div><div class="seat available" data-seat="F4"></div><div class="seat available" data-seat="F5"></div><div class="seat available" data-seat="F6"></div><div class="seat available" data-seat="F7"></div><div class="seat available" data-seat="F8"></div><div class="seat available" data-seat="F9"></div><div class="seat available" data-seat="F10"></div><div class="seat available" data-seat="F11"></div><div class="seat available" data-seat="F12"></div><div class="seat available" data-seat="F13"></div><div class="seat available" data-seat="F14"></div><div class="seat available" data-seat="F15"></div><div class="seat available" data-seat="F16"></div><div class="seat available" data-seat="F17"></div><div class="seat available" data-seat="F18"></div><div class="seat available" data-seat="F19"></div><div class="seat available" data-seat="F20"></div>
                    <div class="row-label-right">F</div>
                </div>
                <div class="row">
                    <div class="row-label">G</div>
                    <div class="seat available" data-seat="G1"></div><div class="seat available" data-seat="G2"></div><div class="seat available" data-seat="G3"></div><div class="seat available" data-seat="G4"></div><div class="seat available" data-seat="G5"></div><div class="seat available" data-seat="G6"></div><div class="seat available" data-seat="G7"></div><div class="seat available" data-seat="G8"></div><div class="seat available" data-seat="G9"></div><div class="seat available" data-seat="G10"></div><div class="seat available" data-seat="G11"></div><div class="seat available" data-seat="G12"></div><div class="seat available" data-seat="G13"></div><div class="seat available" data-seat="G14"></div><div class="seat available" data-seat="G15"></div><div class="seat available" data-seat="G16"></div><div class="seat available" data-seat="G17"></div><div class="seat available" data-seat="G18"></div><div class="seat available" data-seat="G19"></div><div class="seat available" data-seat="G20"></div>
                    <div class="row-label-right">G</div>
                </div>
                <div class="row">
                    <div class="row-label">H</div>
                    <div class="seat available" data-seat="H1"></div><div class="seat available" data-seat="H2"></div><div class="seat available" data-seat="H3"></div><div class="seat available" data-seat="H4"></div><div class="seat available" data-seat="H5"></div><div class="seat available" data-seat="H6"></div><div class="seat available" data-seat="H7"></div><div class="seat available" data-seat="H8"></div><div class="seat available" data-seat="H9"></div><div class="seat available" data-seat="H10"></div><div class="seat available" data-seat="H11"></div><div class="seat available" data-seat="H12"></div><div class="seat available" data-seat="H13"></div><div class="seat available" data-seat="H14"></div><div class="seat available" data-seat="H15"></div><div class="seat available" data-seat="H16"></div><div class="seat available" data-seat="H17"></div><div class="seat available" data-seat="H18"></div><div class="seat available" data-seat="H19"></div><div class="seat available" data-seat="H20"></div>
                    <div class="row-label-right">H</div>
                </div>
            </div>


            <div class="legend">
                <div class="legend-item">
                    <div class="legend-seat available"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat occupied"></div>
                    <span>Occupied</span>
                </div>
                <div class="legend-item">
                    <div class="legend-seat selected"></div>
                    <span>Selected</span>
                </div>
            </div>

            <div class="selection-info">
                <div class="info-row">
                    <span>Selected seats:</span>
                    <span class="selected-seats" id="selectedSeats">None</span>
                </div>
                <div class="info-row">
                    <span>Total Ticket Price:</span>
                    <span class="total-price" id="totalPrice">0</span>
                </div>
                <div class="quantity">
                    <span>Quantity:</span>
                    <span class="selected-seats" id="seatQuantity">0</span>
                </div>
            </div>

            <div class="buttons">
                <a href="homepage.php" class="btn btn-back">Back</a>
                <button class="btn btn-continue">SELECT SEATS</button>
            </div>
        </div>
    </div>

    <!-- Booking Confirmation Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2> Booking Confirmation</h2>
            </div>
            <div class="modal-body">
                <div class="confirmation-details">
                    <div class="detail-row">
                        <span class="detail-label">Movie:</span>
                        <span class="detail-value">LAST BULLET (2D)</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Cinema:</span>
                        <span class="detail-value">Cinema 2, Bonifacio High Street</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Showtime:</span>
                        <span class="detail-value">June 22, 2025 - 9:00 AM</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Selected Seats:</span>
                        <span class="detail-value" id="modalSelectedSeats">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Quantity:</span>
                        <span class="detail-value" id="modalQuantity">0</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Amount:</span>
                        <span class="detail-value" id="modalTotalPrice">Php 0.00</span>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button class="btn-modal btn-cancel" onclick="closeModal()">Cancel</button>
                    <button class="btn-modal btn-done" onclick="proceedToPayment()">Done - Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const TICKET_PRICE = 300;
        let selectedSeats = [];

        // Get all available seats
        const seats = document.querySelectorAll('.seat.available');
        const selectedSeatsDisplay = document.getElementById('selectedSeats');
        const totalPriceDisplay = document.getElementById('totalPrice');
        const seatQuantityDisplay = document.getElementById('seatQuantity');
        const modal = document.getElementById('bookingModal');

        // Add click event to each available seat
        seats.forEach(seat => {
            seat.addEventListener('click', function() {
                const seatNumber = this.getAttribute('data-seat');
                
                if (this.classList.contains('selected')) {
                    // Deselect seat
                    this.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(s => s !== seatNumber);
                } else {
                    // Select seat
                    this.classList.add('selected');
                    selectedSeats.push(seatNumber);
                }
                
                updateDisplay();
            });
        });

        function updateDisplay() {
            // Update selected seats display
            if (selectedSeats.length === 0) {
                selectedSeatsDisplay.textContent = 'None';
            } else {
                selectedSeatsDisplay.textContent = selectedSeats.sort().join(', ');
            }

            // Update quantity display
            seatQuantityDisplay.textContent = selectedSeats.length;

            // Update total price
            const totalPrice = selectedSeats.length * TICKET_PRICE;
            totalPriceDisplay.textContent = totalPrice;
        }

        function showBookingModal() {
            if (selectedSeats.length === 0) {
                alert('Please select at least one seat before proceeding.');
                return;
            }

            // Update modal content
            document.getElementById('modalSelectedSeats').textContent = selectedSeats.sort().join(', ');
            document.getElementById('modalQuantity').textContent = selectedSeats.length;
            document.getElementById('modalTotalPrice').textContent = `Php ${(selectedSeats.length * TICKET_PRICE).toFixed(2)}`;

            // Show modal
            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function proceedToPayment() {
            const totalPrice = selectedSeats.length * TICKET_PRICE;

        // Save to sessionStorage (optional fallback)
        sessionStorage.setItem('totalAmount', totalPrice);
        // Redirect to payment.php with total in URL and movie identifier
        window.location.href = `payment.php?total=${totalPrice}&movie=seats_last_bullet`;
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Update the continue button to show modal instead of alert
        document.querySelector('.btn-continue').addEventListener('click', showBookingModal);

        // Initialize display
        updateDisplay();

       
    </script>
</body>
</html>