<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php';

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'th'; // ภาษาไทยเป็นค่าเริ่มต้น
}
$lang_file = 'lang/' . $_SESSION['lang'] . '.php';
if (file_exists($lang_file)) {
    require_once($lang_file);
} else {
    require_once('lang/th.php'); // Fallback to Thai
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang']); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Beef Export' : 'Beef Export'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" alt="Beef Export Logo"></a>
                <h1><a href="index.php">Beef Export</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php"><?php echo $lang['home']; ?></a></li>
                    <li><a href="products.php"><?php echo $lang['products']; ?></a></li>
                    <li><a href="contact.php"><?php echo $lang['contact_us']; ?></a></li>
                    <li><a href="order_status.php"><?php echo $lang['order_status']; ?></a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? 'user') === 'user'): ?>
                    <?php
                    $cart_item_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                    ?>
                    <a href="order.php" class="cart-button-nav">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count-badge"><?php echo $cart_item_count; ?></span>
                    </a>
                    
                    <div class="user-welcome-container">
                        <span class="welcome-text"><?php echo $lang['welcome']; ?>,</span>
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle btn">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown-menu">
                                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <?php echo $lang['dashboard_title']; ?></a>
                                <a href="order_status.php"><i class="fas fa-history"></i> <?php echo $lang['order_status']; ?></a>
                                <a href="profile.php"><i class="fas fa-user-edit"></i> <?php echo $lang['edit_profile']; ?></a>
                                <hr class="dropdown-divider">
                                <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> <?php echo $lang['logout']; ?></a>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <a href="login.php" class="btn btn-primary"><?php echo $lang['login']; ?></a>
                <?php endif; ?>

                <div class="lang-switcher">
                    <a href="switch_lang.php?lang=th" class="<?php echo ($_SESSION['lang'] == 'th') ? 'active' : ''; ?>">TH</a>
                    <a href="switch_lang.php?lang=en" class="<?php echo ($_SESSION['lang'] == 'en') ? 'active' : ''; ?>">EN</a>
                </div>
            </div>
            </div>
        </div>
    </header>
    <main>