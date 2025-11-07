<?php
require "db.php";

// ====================== Users CRUD ======================
// Delete User
if(isset($_GET["delete_user"])){
    $id = $_GET["delete_user"];
    $pdo->prepare("DELETE FROM cart WHERE user_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM orders WHERE user_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    header("Location: test_all.php"); exit;
}

// Update User
if(isset($_POST["update_user"])){
    $id = $_POST["user_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=? AND id<>?");
    $stmt->execute([$email,$id]);
    if($stmt->fetchColumn()==0){
        $pdo->prepare("UPDATE users SET name=?, email=? WHERE id=?")->execute([$name,$email,$id]);
    }
    header("Location: test_all.php"); exit;
}

// ====================== Menu CRUD ======================
// Delete Menu
if(isset($_GET["delete_menu"])){
    $id = $_GET["delete_menu"];
    $pdo->prepare("DELETE FROM menu WHERE id=?")->execute([$id]);
    header("Location: test_all.php"); exit;
}

// Update Menu
if(isset($_POST["update_menu"])){
    $id = $_POST["menu_id"];
    $name = $_POST["name"];
    $desc = $_POST["description"];
    $price = $_POST["price"];
    $pdo->prepare("UPDATE menu SET name=?, description=?, price=? WHERE id=?")->execute([$name,$desc,$price,$id]);
    header("Location: test_all.php"); exit;
}

// ====================== Cart CRUD ======================
// Delete Cart Item
if(isset($_GET["delete_cart"])){
    $id = $_GET["delete_cart"];
    $pdo->prepare("DELETE FROM cart WHERE id=?")->execute([$id]);
    header("Location: test_all.php"); exit;
}

// Update Cart Quantity
if(isset($_POST["update_cart"])){
    $id = $_POST["cart_id"];
    $quantity = $_POST["quantity"];
    $pdo->prepare("UPDATE cart SET quantity=? WHERE id=?")->execute([$quantity,$id]);
    header("Location: test_all.php"); exit;
}

// ====================== Orders CRUD ======================
// Delete Order
if(isset($_GET["delete_order"])){
    $id = $_GET["delete_order"];
    $pdo->prepare("DELETE FROM orders WHERE id=?")->execute([$id]);
    header("Location: test_all.php"); exit;
}

// Update Order Status
if(isset($_POST["update_order"])){
    $id = $_POST["order_id"];
    $status = $_POST["status"];
    $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status,$id]);
    header("Location: test_all.php"); exit;
}

// ====================== Place Order ======================
if(isset($_POST["place_order"])){
    $userName = $_POST["name"];
    $menuId = $_POST["menu_item_id"];
    $quantity = $_POST["quantity"];
    $payment = $_POST["payment_method"];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE name=?");
    $stmt->execute([$userName]);
    $userId = $stmt->fetchColumn();
    if(!$userId){
        $stmt = $pdo->prepare("INSERT INTO users (name) VALUES (?)");
        $stmt->execute([$userName]);
        $userId = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("INSERT INTO cart (user_id, menu_item_id, quantity) VALUES (?,?,?)");
    $stmt->execute([$userId,$menuId,$quantity]);

    $priceStmt = $pdo->prepare("SELECT price FROM menu WHERE id=?");
    $priceStmt->execute([$menuId]);
    $menuPrice = $priceStmt->fetchColumn();

    $total = $menuPrice * $quantity;
    $status = ($payment=="cash")?"paid":"pending";

    $stmt = $pdo->prepare("INSERT INTO orders (user_id,total,status,payment_method) VALUES (?,?,?,?)");
    $stmt->execute([$userId,$total,$status,$payment]);

    if($payment=="fonepay"){
        $qr = "https://dummy-qrcode.com/fonepay?amount=".$total."&user=".$userId;
        echo "<p>Scan QR to pay: <a href='$qr' target='_blank'>$qr</a></p>";
    }

    echo "<p>Order placed successfully! Total: $total</p>";
}

// Fetch current data
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$menu = $pdo->query("SELECT * FROM menu")->fetchAll(PDO::FETCH_ASSOC);
$cart = $pdo->query("SELECT c.id,u.name as user_name,m.name as menu_name,c.quantity,c.created_at FROM cart c JOIN users u ON c.user_id=u.id JOIN menu m ON c.menu_item_id=m.id")->fetchAll(PDO::FETCH_ASSOC);
$orders = $pdo->query("SELECT o.id,u.name,o.total,o.status,o.payment_method,o.created_at FROM orders o JOIN users u ON o.user_id=u.id")->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Place Order</h2>
<form method="post">
<input type="text" name="name" placeholder="Your Name" required>
<select name="menu_item_id" required>
<?php foreach($menu as $m) echo "<option value='{$m['id']}'>{$m['name']} ({$m['price']})</option>"; ?>
</select>
<input type="number" name="quantity" min="1" placeholder="Quantity" required>
<select name="payment_method" required>
<option value="cash">Cash</option>
<option value="fonepay">Fonepay</option>
</select>
<button type="submit" name="place_order">Place Order</button>
</form>

<h3>Users</h3>
<table border="1">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr>
<?php foreach($users as $u): ?>
<tr>
<td><?= $u['id'] ?></td>
<td>
<form method="post">
<input type="hidden" name="user_id" value="<?= $u['id'] ?>">
<input type="text" name="name" value="<?= $u['name'] ?>">
<input type="email" name="email" value="<?= $u['email'] ?>">
<button type="submit" name="update_user">Update</button>
</form>
</td>
<td><?= $u['email'] ?></td>
<td><a href="?delete_user=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Menu</h3>
<table border="1">
<tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Actions</th></tr>
<?php foreach($menu as $m): ?>
<tr>
<td><?= $m['id'] ?></td>
<td>
<form method="post">
<input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
<input type="text" name="name" value="<?= $m['name'] ?>">
<input type="text" name="description" value="<?= $m['description'] ?>">
<input type="number" step="0.01" name="price" value="<?= $m['price'] ?>">
<button type="submit" name="update_menu">Update</button>
</form>
</td>
<td><?= $m['description'] ?></td>
<td><?= $m['price'] ?></td>
<td><a href="?delete_menu=<?= $m['id'] ?>" onclick="return confirm('Delete menu?')">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Cart</h3>
<table border="1">
<tr><th>ID</th><th>User</th><th>Menu Item</th><th>Quantity</th><th>Actions</th></tr>
<?php foreach($cart as $c): ?>
<tr>
<td><?= $c['id'] ?></td>
<td><?= $c['user_name'] ?></td>
<td><?= $c['menu_name'] ?></td>
<td>
<form method="post">
<input type="hidden" name="cart_id" value="<?= $c['id'] ?>">
<input type="number" name="quantity" value="<?= $c['quantity'] ?>" min="1">
<button type="submit" name="update_cart">Update</button>
</form>
</td>
<td><a href="?delete_cart=<?= $c['id'] ?>" onclick="return confirm('Delete cart item?')">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>

<h3>Orders</h3>
<table border="1">
<tr><th>ID</th><th>User</th><th>Total</th><th>Status</th><th>Payment</th><th>Actions</th></tr>
<?php foreach($orders as $o): ?>
<tr>
<td><?= $o['id'] ?></td>
<td><?= $o['name'] ?></td>
<td><?= $o['total'] ?></td>
<td>
<form method="post">
<input type="hidden" name="order_id" value="<?= $o['id'] ?>">
<select name="status">
<option value="pending" <?= $o['status']=="pending"?"selected":""?>>pending</option>
<option value="paid" <?= $o['status']=="paid"?"selected":""?>>paid</option>
<option value="completed" <?= $o['status']=="completed"?"selected":""?>>completed</option>
</select>
<button type="submit" name="update_order">Update</button>
</form>
</td>
<td><?= $o['payment_method'] ?></td>
<td><a href="?delete_order=<?= $o['id'] ?>" onclick="return confirm('Delete order?')">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>
