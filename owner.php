<?php
$conn = new mysqli('localhost', 'root', '', 'restaurant');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders when the button is clicked
$orders = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['get_orders'])) {
        $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['order_data'] = json_decode($row['order_data'], true); // Decode JSON
                $orders[] = $row;
            }
        }
    } elseif (isset($_POST['clear_orders'])) {
        $conn->query("DELETE FROM orders");
        $orders = [];  // Clear the orders displayed on the page
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEEPIKA RESTAURANT - Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-gray-200 to-gray-400">
    <header class="bg-gray-600 shadow-md fixed top-0 left-0 right-0 z-10">
        <div class="container mx-auto px-6 py-4">
            <h1 class="text-xl font-bold text-white">DEEPIKA RESTAURANT - Orders</h1>
        </div>
    </header>

    <main class="pt-20 container mx-auto px-6">
        <!-- Buttons to Get Orders and Clear Orders -->
        <form method="POST" class="mb-4">
            <button type="submit" name="get_orders" class="bg-gradient-to-r from-green-400 to-green-600 text-white px-4 py-2 rounded-full hover:bg-green-600 mr-[30px] shadow-xl">Get Orders</button>
            <button type="submit" name="clear_orders" class="bg-gradient-to-r from-red-400 to-red-600 text-white px-4 py-2 rounded-full hover:bg-red-600 shadow-2xl">Clear Orders</button>
        </form>

        <!-- Display Orders -->
        <div class="bg-white p-4 rounded-xl shadow-2xl">
            <?php if (!empty($orders)): ?>
                <table class="min-w-full border-collapse border border-gray-200 text-left">
                    <thead>
                        <tr class="bg-gray-700">
                            <th class="border border-gray-200 px-4 py-2 text-white">Table No</th>
                            <th class="border border-gray-200 px-4 py-2 text-white">Order ID</th>
                            <th class="border border-gray-200 px-4 py-2 text-white">Placed At</th>
                            <th class="border border-gray-200 px-4 py-2 text-white">Order Details</th>
                            <th class="border border-gray-200 px-4 py-2 text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <?php
                            $orderItems = $order['order_data'];
                            $totalPrice = 0;
                            ?>
                            <tr>
                                <td class="border border-gray-200 px-4 py-2 bg-gradient-to-r from-cyan-100 to-cyan-300 "><?= htmlspecialchars($order['table_number']) ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?= htmlspecialchars($order['id']) ?></td>
                                <td class="border border-gray-200 px-4 py-2"><?= htmlspecialchars($order['created_at']) ?></td>
                                <td class="border border-gray-200 px-4 py-2">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="bg-gray-700">
                                                <th class="border px-2 py-1 text-white">Name</th>
                                                <th class="border px-2 py-1 text-white">Quantity</th>
                                                <th class="border px-2 py-1 text-white">Price</th>
                                                <th class="border px-2 py-1 text-white">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orderItems as $item): ?>
                                                <?php
                                                $subtotal = $item['quantity'] * $item['price'];
                                                $totalPrice += $subtotal;
                                                ?>
                                                <tr>
                                                    <td class="border px-2 py-1"><?= htmlspecialchars($item['name']) ?></td>
                                                    <td class="border px-2 py-1"><?= htmlspecialchars($item['quantity']) ?></td>
                                                    <td class="border px-2 py-1">₹<?= htmlspecialchars($item['price']) ?></td>
                                                    <td class="border px-2 py-1">₹<?= htmlspecialchars($subtotal) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="border border-gray-200 px-4 py-2 font-bold">₹<?= $totalPrice ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">No orders to display.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
