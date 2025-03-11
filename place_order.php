<?php
$conn = new mysqli('localhost', 'root', '', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['items'])) {
        $items = $conn->real_escape_string(json_encode($data['items']));
        $sql = "INSERT INTO orders (order_data) VALUES ('$items')";

        if ($conn->query($sql) === TRUE) {
            // Return the order ID
            $orderId = $conn->insert_id;
            echo json_encode(['success' => true, 'orderId' => $orderId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error placing order.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No items in the order.']);
    }
}
?>
