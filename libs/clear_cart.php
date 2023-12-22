<?php
include "db_conn.php"; // Include your database connection file here
session_start();

// Fetch session information
$session_id = $_SESSION['shop_session'];

// Clear the cart for the current session
$query = "DELETE FROM cart_item WHERE session_id = '$session_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    echo "Cart cleared successfully";
} else {
    echo "Error clearing cart: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
