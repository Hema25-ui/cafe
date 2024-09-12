<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafe_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$seatData = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $seatNumber = $_POST['seat_number'];

 
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, seat_number, status) VALUES (?, ?, ?, ?, 'selected')");
    $stmt->bind_param("sssi", $name, $email, $phone, $seatNumber);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$sql = "SELECT seat_number, status FROM bookings";
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $seatData[$row['seat_number']] = $row['status'];
    }
    $result->free();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Seats - Le French Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .container {
            margin-top: 30px;
        }

        .screen {
            background-color: #ccc;
            height: 50px;
            margin: 20px 0;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
            border-radius: 10px;
        }

        .seats {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 500px;
            margin: 0 auto;
        }

        .seat {
            background-color: #ddd;
            height: 60px; 
            width: 60px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px; 
            color: #333;
        }

        .seat.selected {
            background-color: #6b4226;
            color: #fff; 
        }

        .seat.occupied {
            background-color: #444;
            cursor: not-allowed;
            color: #fff; 
        }

        .legend {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .legend-item {
            margin-right: 20px;
            display: flex;
            align-items: center;
        }

        .legend-item span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            background-color: #ddd;
        }

        .legend-item span.selected {
            background-color: #6b4226;
        }

        .legend-item span.occupied {
            background-color: #444;
        }

        .btn-primary {
            background-color: #6b4226;
            border-color: #6b4226;
        }

        .btn-primary:hover {
            background-color: #8c5638;
            border-color: #8c5638;
        }

        .seat-table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
        }

        .seat-table th, .seat-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .seat-table th {
            background-color: #6b4226;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Book a Table at Your Cafe</h2>
        <div class="screen">WINDOW</div>

        <div class="seats" id="seat-container">
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <div class="seat <?php echo isset($seatData[$i]) ? $seatData[$i] : ''; ?>" data-seat-number="<?php echo $i; ?>">
                    <?php echo $i; ?>
                </div>
            <?php endfor; ?>
        </div>

        <div class="legend">
            <div class="legend-item">
                <span></span> Available
            </div>
            <div class="legend-item">
                <span class="selected"></span> Selected
            </div>
            <div class="legend-item">
                <span class="occupied"></span> Occupied
            </div>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            <div class="mb-3">
                <label for="seat_number" class="form-label">Select Seat Number</label>
                <input type="number" class="form-control" id="seat_number" name="seat_number" min="1" max="20" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Book Now</button>
        </form>

        <table class="seat-table">
            <thead>
                <tr>
                    <th>Seat Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seatData as $seatNumber => $status): ?>
                    <tr>
                        <td><?php echo $seatNumber; ?></td>
                        <td><?php echo ucfirst($status); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        const container = document.getElementById('seat-container');
        const seats = container.getElementsByClassName('seat');

       
        Array.from(seats).forEach(seat => {
            const seatNumber = seat.getAttribute('data-seat-number');
            if (seatNumber) {
                const status = seat.classList.contains('occupied') ? 'occupied' : (seat.classList.contains('selected') ? 'selected' : 'available');
                seat.classList.add(status);
            }
        });

        container.addEventListener('click', (event) => {
            if (event.target.classList.contains('seat') && !event.target.classList.contains('occupied')) {
                event.target.classList.toggle('selected');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
