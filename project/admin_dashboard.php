<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "product"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Current tab (orders or messages)
$tab = $_GET['tab'] ?? 'orders';

// Handle bulk actions (Orders)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && $tab === 'orders') {
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        if (!empty($_POST['bulk_action']) && !empty($_POST['order_ids']) && is_array($_POST['order_ids'])) {
            $order_ids = array_map('intval', $_POST['order_ids']);
            if (count($order_ids) > 0) {
                $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
                $types = str_repeat('i', count($order_ids));
                if ($_POST['bulk_action'] === 'mark_paid') {
                    $stmt = $conn->prepare("UPDATE orders SET pay='Paid' WHERE id IN ($placeholders)");
                } elseif ($_POST['bulk_action'] === 'remove') {
                    $stmt = $conn->prepare("DELETE FROM orders WHERE id IN ($placeholders)");
                } else {
                    $stmt = null;
                }
                if ($stmt) {
                    $stmt->bind_param($types, ...$order_ids);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }
}

// Handle delete message (Messages)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message']) && $tab === 'messages') {
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $msg_id = (int)$_POST['message_id'];
        $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id=?");
        $stmt->bind_param("i", $msg_id);
        $stmt->execute();
        $stmt->close();
    }
}   

// === ORDERS LOGIC ===
$orders = [];
$total_pages = 1;

if ($tab === 'orders') {
    // Pagination setup
    $per_page = 10;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $per_page;

    // Sorting
    $allowed_sorts = ['id', 'customer_name', 'product_name', 'quantity', 'total_price', 'order_date', 'pay'];
    $sort = in_array($_GET['sort'] ?? '', $allowed_sorts) ? $_GET['sort'] : 'order_date';
    $order_dir = ($_GET['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

    // Filters
    $filter = $_GET['filter'] ?? '';
    $search = $_GET['search'] ?? '';
    $date_from = $_GET['date_from'] ?? '';
    $date_to = $_GET['date_to'] ?? '';

    $where_clauses = ['1'];
    $params = [];
    $types = '';

    if (in_array($filter, ['Paid', 'Unpaid'])) {
        $where_clauses[] = "pay = ?";
        $params[] = $filter;
        $types .= 's';
    }

    if ($search !== '') {
        $where_clauses[] = "(customer_name LIKE ? OR product_name LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'ss';
    }

    if ($date_from !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from)) {
        $where_clauses[] = "order_date >= ?";
        $params[] = $date_from . " 00:00:00";
        $types .= 's';
    }

    if ($date_to !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
        $where_clauses[] = "order_date <= ?";
        $params[] = $date_to . " 23:59:59";
        $types .= 's';
    }

    $where_sql = implode(' AND ', $where_clauses);

    // Count rows
    $count_sql = "SELECT COUNT(*) FROM orders WHERE $where_sql";
    $stmt_count = $conn->prepare($count_sql);
    if ($params) {
        $stmt_count->bind_param($types, ...$params);
    }
    $stmt_count->execute();
    $stmt_count->bind_result($total_rows);
    $stmt_count->fetch();
    $stmt_count->close();

    $total_pages = max(1, ceil($total_rows / $per_page));

    // Fetch orders
    $sql = "SELECT * FROM orders WHERE $where_sql ORDER BY $sort $order_dir LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($params) {
        $types_pag = $types . "ii";
        $params_pag = array_merge($params, [$per_page, $offset]);
        $stmt->bind_param($types_pag, ...$params_pag);
    } else {
        $stmt->bind_param("ii", $per_page, $offset);
    }
    $stmt->execute();
    $orders = $stmt->get_result();
}
// === REPORTS LOGIC ===
$reports = [];
if ($tab === 'reports') {
    $reports = $conn->query("SELECT * FROM issue_reports ORDER BY created_at DESC");
}

// === MESSAGES LOGIC ===
$messages = [];
if ($tab === 'messages') {
    $messages = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <style>
body {
    background: #121212;
    color: #eee;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 30px 20px;
    margin: 0;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Heading */
h2 {
    text-align: center;
    font-weight: 700;
    font-size: 32px;
    margin-bottom: 30px;
    color: #f39c12;
    letter-spacing: 1px;
    text-shadow: 0 0 6px #f39c12aa;
}

/* Navigation */
.nav {
    text-align: center;
    margin-bottom: 40px;
}

.nav a {
    text-decoration: none;
    padding: 12px 28px;
    margin: 0 10px;
    background: #2c3e50;
    color: #f39c12;
    border-radius: 30px;
    font-weight: 600;
    font-size: 16px;
    box-shadow: 0 0 15px #f39c12aa;
    display: inline-block;
    transition: background 0.4s ease, box-shadow 0.4s ease, transform 0.15s ease;
}

.nav a.active,
.nav a:hover {
    background: #f39c12;
    color: #121212;
    box-shadow: 0 0 20px #f39c12ff;
    transform: translateY(-3px);
}

/* Buttons */
.btn {
    background: #e67e22;
    color: #121212;
    padding: 10px 24px;
    border: none;
    border-radius: 30px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    box-shadow: 0 0 12px #e67e22bb;
    transition: background 0.4s ease, box-shadow 0.4s ease, transform 0.15s ease;
}

.btn:hover {
    background: #d35400;
    box-shadow: 0 0 20px #d35400dd;
    transform: translateY(-3px);
}

.btn.red {
    background: #c0392b;
    color: #f7f7f7;
    box-shadow: 0 0 15px #c0392bcc;
}

.btn.red:hover {
    background: #992d22;
    box-shadow: 0 0 25px #992d22ff;
}

/* Form inputs */
form select,
form input[type="text"],
form input[type="date"] {
    padding: 9px 14px;
    border-radius: 15px;
    border: none;
    font-size: 14px;
    background: #2c3e50;
    color: #eee;
    box-shadow: inset 2px 2px 8px #1b2733, inset -2px -2px 8px #3d526a;
    transition: box-shadow 0.3s ease;
}

form select:focus,
form input[type="text"]:focus,
form input[type="date"]:focus {
    outline: none;
    box-shadow: 0 0 12px #f39c12aa;
    background: #34495e;
}

/* Filter Form */
form[method="get"] {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin-bottom: 35px;
}

form[method="get"] input[type="text"] {
    flex: 1 1 220px;
    max-width: 280px;
}

form[method="get"] select,
form[method="get"] input[type="date"] {
    flex: 0 0 160px;
}

form[method="get"] button,
form[method="get"] a.btn {
    flex: 0 0 auto;
    align-self: center;
}

/* Table */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
}

thead tr {
    background: transparent;
}

th, td {
    padding: 16px 20px;
    font-size: 14px;
    text-align: center;
    vertical-align: middle;
    color: #ddd;
}

th {
    background: #2c3e50;
    font-weight: 700;
    letter-spacing: 0.05em;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
    user-select: none;
    box-shadow: inset 0 -2px 6px #1b2733;
}

tbody tr {
    background: #1e272e;
    border-radius: 14px;
    box-shadow: 0 8px 12px #0d1215aa;
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

tbody tr:hover {
    background: #34495e;
    box-shadow: 0 10px 18px #f39c12aa;
}

td {
    border-bottom: none !important;
}

tbody tr td:first-child {
    padding-left: 24px;
}

tbody tr td:last-child {
    padding-right: 24px;
}

input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    filter: drop-shadow(0 0 1px #f39c12aa);
}

/* Green color for Paid status */
td.status-paid {
    color: #2ecc71;  /* bright green */
    font-weight: 700;
    text-shadow: 0 0 4px #27ae60aa;
}

/* Pagination */
div.pagination {
    text-align: center;
    margin-top: 30px;
}

div.pagination a {
    display: inline-block;
    margin: 0 7px;
    padding: 9px 18px;
    border-radius: 30px;
    background: #2c3e50;
    color: #f39c12;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    box-shadow: 0 0 15px #f39c12bb;
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

div.pagination a:hover {
    background: #f39c12;
    color: #121212;
    box-shadow: 0 0 25px #f39c12ff;
}

div.pagination a.active {
    background: #f39c12;
    color: #121212;
    box-shadow: 0 0 30px #f39c12ff;
}

/* Responsive */
@media (max-width: 768px) {
    form[method="get"] {
        flex-direction: column;
        gap: 12px;
    }

    form[method="get"] input[type="text"],
    form[method="get"] select,
    form[method="get"] input[type="date"],
    form[method="get"] button,
    form[method="get"] a.btn {
        flex: 1 1 100%;
        max-width: none;
    }

    table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
    }

    thead {
        display: none;
    }

    tbody tr {
        margin-bottom: 18px;
        border-radius: 14px;
        box-shadow: 0 6px 14px #0d1215cc;
    }

    tbody tr td {
        text-align: right;
        padding: 12px 20px;
        position: relative;
        border-bottom: 1px solid #2c3e50;
        border-radius: 0;
        color: #eee;
    }

    tbody tr td::before {
        content: attr(data-label);
        position: absolute;
        left: 20px;
        font-weight: 700;
        color: #f39c12;
        text-transform: uppercase;
        font-size: 11px;
    }

    tbody tr td:last-child {
        border-bottom: none;
    }
}
td.status-paid {
    color: #2ecc71;  /* bright green */
    font-weight: 700;
    text-shadow: 0 0 4px #27ae60aa;
}

    </style>
</head>
<body>
<h2>Admin Dashboard</h2>
<div class="nav">
    <a href="?tab=orders" class="<?= $tab === 'orders' ? 'active' : '' ?>">Orders</a>
    <a href="?tab=messages" class="<?= $tab === 'messages' ? 'active' : '' ?>">Messages</a>
    <a href="?tab=reports" class="<?= $tab === 'reports' ? 'active' : '' ?>">Reports</a>
    <a href="logout.php" class="btn">Logout</a>
    <a href="add_product.php" class="btn">Add Product</a>
</div>


<?php if ($tab === 'orders'): ?>
    <!-- Orders Table -->
    <form method="post" onsubmit="return confirmBulkAction();">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <select name="bulk_action" id="bulk_action" required>
            <option value="">Bulk actions</option>
            <option value="mark_paid">Mark as Paid</option>
            <option value="remove">Remove</option>
        </select>
        <button type="submit" class="btn">
            <!-- Mark Paid icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" viewBox="0 0 24 24" width="18" height="18" style="fill:#f39c12;">
                <circle cx="12" cy="12" r="10" stroke="#f39c12" stroke-width="2" fill="none"/>
                <path d="M7 12l3 3 7-7" stroke="#f39c12" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Apply
        </button>
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>ID</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Date</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows === 0): ?>
                    <tr><td colspan="8">No orders found</td></tr>
                <?php else: ?>
                    <?php while($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="order_ids[]" value="<?= $row['id'] ?>"></td>
                        <td data-label="ID"><?= $row['id'] ?></td>
                        <td data-label="Customer"><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td data-label="Product"><?= htmlspecialchars($row['product_name']) ?></td>
                        <td data-label="Qty"><?= $row['quantity'] ?></td>
                        <td data-label="Total">$<?= number_format($row['total_price'],2) ?></td>
                        <td data-label="Date"><?= $row['order_date'] ?></td>
                        <td data-label="Status" class="<?= ($row['pay'] === 'Paid') ? 'status-paid' : '' ?>">
                <?= htmlspecialchars($row['pay']) ?>
</td>

                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
<?php elseif ($tab === 'messages'): ?>
    <!-- Messages Table -->
    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php if ($messages->num_rows === 0): ?>
                <tr><td colspan="6">No messages found</td></tr>
            <?php else: ?>
                <?php while($msg = $messages->fetch_assoc()): ?>
                <tr>
                    <td data-label="ID"><?= $msg['id'] ?></td>
                    <td data-label="Name"><?= htmlspecialchars($msg['name']) ?></td>
                    <td data-label="Email"><?= htmlspecialchars($msg['email']) ?></td>
                    <td data-label="Message"><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                    <td data-label="Date"><?= $msg['created_at'] ?></td>
                    <td data-label="Action">
                        <form method="post" onsubmit="return confirm('Delete this message?');" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                            <button type="submit" name="delete_message" class="btn red" title="Delete message">
                                <!-- Trash icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#fff" viewBox="0 0 24 24" width="16" height="16" style="fill:#f39c12;">
                                    <path d="M3 6h18v2H3V6zm2 3h14v11a2 2 0 01-2 2H7a2 2 0 01-2-2V9zm5 3v5h2v-5H10zm4 0v5h2v-5h-2zM9 4h6v2H9V4z"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php elseif ($tab === 'reports'): ?>
    <h3 style="color:#f39c12; text-align:center; margin-bottom:20px;">Issue Reports</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Issue Type</th>
                <th>Details</th>
                <th>Reported At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($reports->num_rows === 0): ?>
                <tr><td colspan="5">No reports found.</td></tr>
            <?php else: ?>
                <?php while ($r = $reports->fetch_assoc()): ?>
                    <tr>
                        <td data-label="ID"><?= $r['id'] ?></td>
                        <td data-label="Order ID"><?= htmlspecialchars($r['order_id'] ?: 'N/A') ?></td>
                        <td data-label="Issue Type"><?= htmlspecialchars($r['issue_type']) ?></td>
                        <td data-label="Details"><?= nl2br(htmlspecialchars($r['details'])) ?></td>
                        <td data-label="Reported At"><?= $r['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

<?php endif; ?>



<script>
document.getElementById('checkAll')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="order_ids[]"]').forEach(cb => cb.checked = this.checked);
});


function confirmBulkAction() {
    const action = document.getElementById('bulk_action').value;
    if (!action) {
        alert('Please select a bulk action.');
        return false;
    }
    const checkedBoxes = document.querySelectorAll('input[name="order_ids[]"]:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one order.');
        return false;
    }
    if (action === 'remove') {
        return confirm('Are you sure you want to remove the selected orders? This action cannot be undone.');
    }
    return true;
}
</script>
</body>
</html>
