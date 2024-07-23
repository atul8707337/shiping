05.12 23:23
<?php
// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "betting_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User authentication
function authenticateUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

// Place bet
function placeBet($userId, $optionId, $betAmount) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO bets (user_id, option_id, bet_amount) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $optionId, $betAmount);
    $stmt->execute();
    $stmt->close();
}

// Get betting options
function getBettingOptions() {
    global $conn;
    $sql = "SELECT id, title, description FROM betting_options";
    $result = $conn->query($sql);
    $bettingOptions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bettingOptions[] = $row;
        }
    }
    return $bettingOptions;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $optionId = $_POST['option_id'];
    $betAmount = $_POST['bet_amount'];
    placeBet($userId, $optionId, $betAmount);
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betting App</title>
</head>
<body>
    <h1>Betting Options</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="user_id" value="1"> <!-- Assuming user ID is 1 for demonstration -->
        <select name="option_id">
            <?php foreach (getBettingOptions() as $option): ?>
                <option value="<?php echo $option['id']; ?>"><?php echo $option['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="bet_amount">Bet Amount:</label>
        <input type="text" id="bet_amount" name="bet_amount">
        <button type="submit">Place Bet</button>
    </form>

    <!-- Front-end link -->
    <p>Front-end Link: <a href="https://atul8707337.github.io/crickbio/">https://atul8707337.github.io/crickbio/</a></p>
</body>
</html>

