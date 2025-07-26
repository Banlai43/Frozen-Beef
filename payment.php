<?php
$page_title = "ชำระเงิน";
include 'header.php';

// ตรวจสอบว่ามี order_id ส่งมาหรือไม่
if (!isset($_GET['order_id'])) {
    echo "<p class='text-center'>ไม่พบข้อมูลคำสั่งซื้อ</p>";
    include 'footer.php';
    exit();
}
$order_id = (int)$_GET['order_id'];

// ดึงข้อมูลยอดรวมของออเดอร์จาก DB
$stmt = $connect->prepare("SELECT total_price FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "<p class='text-center'>ไม่พบข้อมูลคำสั่งซื้อ</p>";
    include 'footer.php';
    exit();
}

$total_price = $order['total_price'];

// *** แก้ไขเป็นเบอร์พร้อมเพย์ของคุณ ***
$promptpay_id = "0934457164"; 
$qr_code_url = "https://promptpay.io/{$promptpay_id}/{$total_price}.png";

?>

<style>
    .payment-container { max-width: 600px; margin: 40px auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); background: #fff; text-align: center; }
    .payment-container h2 { color: #8B0000; margin-bottom: 15px; }
    .payment-container .price { font-size: 2.5em; font-weight: bold; color: #DAA520; margin-bottom: 25px; }
    .payment-container img { max-width: 250px; height: auto; margin-bottom: 25px; border: 1px solid #ddd; padding: 5px; border-radius: 8px; }
    .upload-form label { font-weight: bold; display: block; margin-bottom: 10px; font-size: 1.1em; }
    .upload-form input[type="file"] { margin-bottom: 20px; }
    .upload-form .btn-submit-slip { background-color: #28a745; color: white; padding: 12px 25px; border-radius: 8px; border:none; cursor: pointer; font-size: 1.1em; }
</style>

<div class="payment-container">
    <h2>ชำระเงินสำหรับคำสั่งซื้อ #<?php echo $order_id; ?></h2>
    <p>ยอดที่ต้องชำระ</p>
    <div class="price">฿<?php echo number_format($total_price, 2); ?></div>
    
    <p>สแกน QR Code นี้เพื่อชำระเงิน</p>
    <img src="<?php echo $qr_code_url; ?>" alt="PromptPay QR Code">

    <hr style="margin: 30px 0;">

    <form action="upload_slip.php" method="POST" enctype="multipart/form-data" class="upload-form">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <label for="slip_image">เมื่อชำระเงินแล้ว กรุณาอัปโหลดสลิปเพื่อยืนยัน</label>
        <input type="file" id="slip_image" name="slip_image" accept="image/*" required>
        <br>
        <button type="submit" class="btn-submit-slip">ยืนยันการชำระเงิน</button>
    </form>
</div>


<?php include 'footer.php'; ?>