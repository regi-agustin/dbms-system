<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cinema';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle payment method update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_payment') {
    $response = array('success' => false, 'message' => '');
    
    try {
        $ticket_id = $_POST['ticket_id'];
        $paymode = $_POST['paymode'];
        $customer_id = $_POST['customer_id'];
        
        // Debug log
        error_log("Updating payment - Ticket ID: $ticket_id, PayMode: $paymode, Customer ID: $customer_id");
        
        // Start transaction
        $conn->begin_transaction();
        
        // First check if the ticket exists
        $checkStmt = $conn->prepare("SELECT Ticket_ID, Customer_ID FROM ticket WHERE Ticket_ID = ?");
        $checkStmt->bind_param("i", $ticket_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $ticketData = $result->fetch_assoc();
        
        if (!$ticketData) {
            throw new Exception("Ticket not found with ID: " . $ticket_id);
        }
        
        // If customer_id is null, update it
        if ($ticketData['Customer_ID'] === null) {
            $updateStmt = $conn->prepare("UPDATE ticket SET PayMode = ?, Customer_ID = ? WHERE Ticket_ID = ?");
            $updateStmt->bind_param("sii", $paymode, $customer_id, $ticket_id);
            
            if (!$updateStmt->execute()) {
                throw new Exception("Error updating ticket: " . $updateStmt->error);
            }
            
            if ($updateStmt->affected_rows === 0) {
                throw new Exception("No changes made to ticket ID: " . $ticket_id);
            }
            
            $updateStmt->close();
        } else {
            // Only update PayMode if customer_id already exists
            $updateStmt = $conn->prepare("UPDATE ticket SET PayMode = ? WHERE Ticket_ID = ?");
            $updateStmt->bind_param("si", $paymode, $ticket_id);
            
            if (!$updateStmt->execute()) {
                throw new Exception("Error updating payment mode: " . $updateStmt->error);
            }
            
            $updateStmt->close();
        }
        
        // Commit transaction
        $conn->commit();
        
        $response['success'] = true;
        $response['message'] = 'Payment method updated successfully';
        error_log("Successfully updated ticket ID: $ticket_id with paymode: $paymode");
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $response['message'] = 'Error updating payment method: ' . $e->getMessage();
        error_log("Payment update error: " . $e->getMessage());
    }
    
    echo json_encode($response);
    exit;
}

// Create seats table if it doesn't exist
$createSeatsTable = "CREATE TABLE IF NOT EXISTS seats (
    Seat_No VARCHAR(10) PRIMARY KEY,
    Customer_ID INT,
    OR_ID INT,
    Ticket_ID INT,
    SeatStatus VARCHAR(20) DEFAULT 'available',
    FOREIGN KEY (Customer_ID) REFERENCES customer(customer_id),
    FOREIGN KEY (OR_ID) REFERENCES sales(OR_ID),
    FOREIGN KEY (Ticket_ID) REFERENCES ticket(Ticket_ID)
)";

if (!$conn->query($createSeatsTable)) {
    die("Error creating seats table: " . $conn->error);
}

// Handle seat booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book_seats') {
    $selectedSeats = json_decode($_POST['selected_seats'], true);
    $response = array('success' => false, 'message' => '');
    
    if (empty($selectedSeats)) {
        $response['message'] = 'No seats selected';
        echo json_encode($response);
        exit;
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Get next available IDs
        $or_id = getNextId($conn, 'sales', 'OR_ID');
        $ticket_id = getNextId($conn, 'ticket', 'Ticket_ID');
        $customer_id = getNextId($conn, 'customer', 'customer_id') - 1; // Subtract 1 from customer_id
        
        // Debug log
        error_log("Booking seats: " . print_r($selectedSeats, true));
        error_log("Generated IDs - Customer: $customer_id, OR: $or_id, Ticket: $ticket_id");
        
        // Create sales record with required information
        $seatTixNumber = 'TBOY-' . str_pad($or_id, 3, '0', STR_PAD_LEFT);
        $quantity = count($selectedSeats);
        
        $stmt = $conn->prepare("INSERT INTO sales (OR_ID, SeatTixNumber, Ticket_ID, Quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $or_id, $seatTixNumber, $ticket_id, $quantity);
        if (!$stmt->execute()) {
            throw new Exception("Error creating sales record: " . $stmt->error);
        }
        $stmt->close();
        
        // Create ticket record with all required fields including customer_id
        $showtime_id = 10; // Set your showtime ID here
        $stmt = $conn->prepare("INSERT INTO ticket (Ticket_ID, OR_ID, Customer_ID, Showtime_ID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $ticket_id, $or_id, $customer_id, $showtime_id);
        if (!$stmt->execute()) {
            throw new Exception("Error creating ticket record: " . $stmt->error);
        }
        $stmt->close();
        
        // Now insert/update seats
        $stmt = $conn->prepare("INSERT INTO seats (Seat_No, Customer_ID, OR_ID, Ticket_ID, SeatStatus) VALUES (?, ?, ?, ?, 'occupied') ON DUPLICATE KEY UPDATE Customer_ID = ?, OR_ID = ?, Ticket_ID = ?, SeatStatus = 'occupied'");
        
        foreach ($selectedSeats as $seatNo) {
            $stmt->bind_param("siiiiii", $seatNo, $customer_id, $or_id, $ticket_id, $customer_id, $or_id, $ticket_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating seat $seatNo: " . $stmt->error);
            }
            error_log("Updated seat: $seatNo");
        }
        
        $stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        $response['success'] = true;
        $response['message'] = 'Seats booked successfully';
        $response['customer_id'] = $customer_id;
        $response['or_id'] = $or_id;
        $response['ticket_id'] = $ticket_id;
        $response['seat_tix_number'] = $seatTixNumber;
        $response['quantity'] = $quantity;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $response['message'] = 'Error booking seats: ' . $e->getMessage();
        error_log("Booking error: " . $e->getMessage());
    }
    
    echo json_encode($response);
    exit;
}

// Function to get next available ID
function getNextId($conn, $table, $column) {
    $sql = "SELECT MAX($column) as max_id FROM $table";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return ($row['max_id'] ?? 0) + 1;
    }
    return 1;
}

// Get occupied seats for display
function getOccupiedSeats($conn) {
    $sql = "SELECT Seat_No FROM seats WHERE SeatStatus = 'occupied'";
    $result = $conn->query($sql);
    $occupiedSeats = array();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $occupiedSeats[] = $row['Seat_No'];
        }
    }
    
    return $occupiedSeats;
}

$occupiedSeats = getOccupiedSeats($conn);
?>

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
        color: #3b4252;
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
        color: #2e3440;
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

    .movie-details h2 {
        color: #5e81ac;
        font-size: 20px;
        margin-bottom: 5px;
    }

    .movie-title {
        color: #bf616a;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .movie-rating,
    .showtime,
    .ticket-price {
        color: #4c566a;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .ticket-price {
        color: #d08770;
        font-weight: 700;
    }

    .theater-container {
        padding: 30px;
        text-align: center;
        color: #434c5e;
    }

    .screen-label {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 8px;
        color: #3b4252;
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
        color: #bf616a;
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
        background-color: #a3be8c;
        border-color: #668a2d;
    }

    .seat.occupied {
        background-color: #bf616a;
        border-color: #a54347;
        cursor: not-allowed;
        filter: grayscale(65%);
        box-shadow: none;
    }

    .seat.selected {
        background-color: #ebcb8b;
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
        background-color: #bf616a;
        border-color: #a54347;
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

    .btn-modal:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .loading {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #eceff4;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
        margin-right: 8px;
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

    @keyframes spin {
        to { transform: rotate(360deg); }
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
        <img src="images/10.jpg" alt="The Boy, the Mole, the Fox and the Horse" class="movie-poster">
        <div class="movie-details">
                <h2>Bonifacio High Street</h2>
                <div class="movie-title">Cinema 2, (2D) THE BOY, THE MOLE, 
                    <br>THE FOX AND THE HORSE</div>
                <div class="movie-rating">RATED G</div>
                <div class="showtime">Sunday, June 22, 2025 1:00 PM</div>
                <div class="ticket-price">Ticket Price: Php250.00</div>
            </div>
        </div>

        <div class="theater-container">
            <div class="screen-label">THE SCREEN IS HERE</div>
            
            <div class="theater-layout">
                <?php
                $rows = ['AO', 'BP', 'CQ', 'DR', 'ES', 'FT', 'GU', 'HV'];
                foreach ($rows as $row) {
                    echo '<div class="row">';
                    echo '<div class="row-label">' . $row . '</div>';
                    
                    for ($i = 1; $i <= 20; $i++) {
                        $seatNo = $row . $i;
                        $isOccupied = in_array($seatNo, $occupiedSeats);
                        $seatClass = $isOccupied ? 'seat occupied' : 'seat available';
                        echo '<div class="' . $seatClass . '" data-seat="' . $seatNo . '"></div>';
                    }
                    
                    echo '<div class="row-label-right">' . $row . '</div>';
                    echo '</div>';
                }
                ?>
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
                <h2>Booking Confirmation</h2>
            </div>
            <div class="modal-body">
                <div class="confirmation-details">
                    <div class="detail-row">
                        <span class="detail-label">Movie:</span>
                        <span class="detail-value">THE BOY, THE MOLE, THE FOX AND THE HORSE (2D)</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Cinema:</span>
                        <span class="detail-value">Cinema 2, Bonifacio High Street</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Showtime:</span>
                        <span class="detail-value">June 9, 2025 - 1:00 PM</span>
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
                    <button class="btn-modal btn-done" onclick="proceedToPayment()" id="doneBtn">Done - Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const TICKET_PRICE = 250;
        let selectedSeats = [];

        // Get all available seats
        const seats = document.querySelectorAll('.seat.available');
        const selectedSeatsDisplay = document.getElementById('selectedSeats');
        const totalPriceDisplay = document.getElementById('totalPrice');
        const seatQuantityDisplay = document.getElementById('seatQuantity');
        const modal = document.getElementById('bookingModal');
        const doneBtn = document.getElementById('doneBtn');

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

        async function proceedToPayment() {
            if (selectedSeats.length === 0) {
                alert('Please select at least one seat before proceeding.');
                return;
            }

            // Show loading state
            doneBtn.disabled = true;
            doneBtn.innerHTML = '<span class="loading"></span>Processing...';

            try {
                // Calculate total amount before resetting selectedSeats
                const totalAmount = selectedSeats.length * TICKET_PRICE;

                // Send booking request to server
                const formData = new FormData();
                formData.append('action', 'book_seats');
                formData.append('selected_seats', JSON.stringify(selectedSeats));

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update UI to show booked seats as occupied
                    selectedSeats.forEach(seatNo => {
                        const seatElement = document.querySelector(`[data-seat="${seatNo}"]`);
                        if (seatElement) {
                            seatElement.classList.remove('available', 'selected');
                            seatElement.classList.add('occupied');
                        }
                    });

                    // Reset selection
                    selectedSeats = [];
                    updateDisplay();
                    closeModal();

                    // Redirect to payment page with all necessary parameters
                    window.location.href = `payment.php?total=${totalAmount}&or_id=${result.or_id}&ticket_id=${result.ticket_id}&showtime_id=1&total_amount=${totalAmount}`;
                } else {
                    alert('Error: ' + result.message);
                    console.error('Booking error:', result.message);
                }
            } catch (error) {
                alert('Network error occurred. Please try again.');
                console.error('Network error:', error);
            } finally {
                // Reset button state
                doneBtn.disabled = false;
                doneBtn.innerHTML = 'Done - Proceed to Payment';
            }
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

        // Add function to handle payment method update
        async function updatePaymentMethod(ticketId, paymode, customerId) {
            try {
                // Debug log
                console.log('Updating payment method:', { ticketId, paymode, customerId });
                
                const formData = new FormData();
                formData.append('action', 'update_payment');
                formData.append('ticket_id', ticketId);
                formData.append('paymode', paymode);
                formData.append('customer_id', customerId);

                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log('Update response:', result);

                if (result.success) {
                    return true;
                } else {
                    console.error('Payment update error:', result.message);
                    return false;
                }
            } catch (error) {
                console.error('Network error:', error);
                return false;
            }
        }
    </script>
</body>
</html>