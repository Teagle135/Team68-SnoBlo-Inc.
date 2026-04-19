<?php
//SnoBlo Inc. Team 68 (Alvin Qin, Tony Ren, Eric Xu, Kuba Calik) - File authored by: Alvin Qin and Kuba Calik
$servername = "localhost";
$username = "qina12_local";
$password = "a.v(aSG2";
$dbname = "qina12_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

//Send booked dates variable to JS to dim booked dates
$booked_dates = $conn->prepare("SELECT `booking_date` FROM `bookings`");
$booked_dates->execute();
$used_dates = $booked_dates->fetchAll(PDO::FETCH_COLUMN);
echo "<script> const used_dates = " . json_encode($used_dates) . "; </script>";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $postal = strtoupper(trim($_POST['postalCode']));
    $time = $_POST['time'];
    $date = $_POST["selectedDate"];
    if (!preg_match("/^[A-Z]\d[A-Z] ?\d[A-Z]\d$/", $postal)) {
        $message = "<p style='color: #d32f2f; font-weight: bold;'>Error: Invalid Canadian Postal Code format.</p>";
    } else {
        try {
            if (!empty($date)) {
                $date_check = $conn->prepare("SELECT COUNT(*) FROM `bookings` WHERE booking_date = :date");
                $date_check->execute([':date' => $date]);

                $date_count = $date_check->fetchColumn();

                if ($date_count == 0) {
                    $sql = "INSERT INTO bookings (customer_name, address, postal_code, booking_date, booking_time) 
                            VALUES (:name, :address, :postal, :date, :time)";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':name' => $name,
                        ':address' => $address,
                        ':postal' => $postal,
                        ':date' => $date,
                        ':time' => $time
                    ]);

                    $message = "<p style='color: #2e7d32; font-weight: bold;'>Success! Booking confirmed for $name.</p>";
                } else {
                    $message = "<p style='color: #d32f2f; font-weight: bold;'>Error: There is a booking already made for this date. </p>";
                }
            } else {
                $message = "<p style='color: #d32f2f; font-weight: bold;'>Error: No date booked.</p>";
            }
                

        } catch (PDOException $e) {
            $message = "<p style='color: #d32f2f;'>Database Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/booking_style.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/booking_script.js" defer> </script>
    <title>SnoBlo Inc. | Booking</title>
</head>

<body style="padding-top: 0; flex-direction: column;">
    <header id="nav-bar">
        <nav aria-label="Main navigation">
            <a class="brand" href="index.html">SnoBlo Inc.</a>
            <ul class="nav-links">
                <li><a href="booking.php">Services</a></li>
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html">Contact</a></li>
            </ul>
            <div class="auth-actions">
                <a class="btn btn-signup" href="signup.php">Sign Up</a>
                <a class="btn btn-signin" href="login.php">Log In</a>
            </div>
        </nav>
    </header>

    <div id="mainContent" style="display: flex; flex-direction: row; justify-content: center; align-items: flex-start; gap: 40px; padding: 40px;">
        <div id="calendar-holder">
            <b id="date-message"> Select a Date </b>
            <div id="calendar-object">
                <div id="month">
                    <button id="left-button"> Left </button>
                    <p id="current"> April 2026 </p>
                    <button id="right-button"> Right </button>
                </div>

                <div id="date">
                </div>


            </div>
            <div id="status"> </div>
        </div>
        <div class="info-container">
            <h2>Information</h2>



            <form action="booking.php" method="POST">

                <input type="hidden" name="selectedDate" id="selectedDate">
                
                <?php echo $message ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" required>
                </div>

                <div class="form-group">
                    <label for="postalCode">Postal Code</label>
                    <input type="text" name="postalCode" id="postalCode" placeholder="A1B 2C3" required>
                </div>

                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" name="time" id="time" required>
                </div>

                <button type="submit" class="submit-btn" id="submit-button">Confirm Booking</button>
            </form>
        </div>
    </div>
</body>

</html>