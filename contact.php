<?php
// ตั้งชื่อหน้าสำหรับแสดงผลใน header.php
$page_title = "ติดต่อเรา";
?>
<?php include 'header.php'; // เรียกใช้ Header กลาง ?>

<main>
    <section class="contact-section">
        <div class="container">
            <h2>ติดต่อเรา</h2>
            <div class="contact-info-grid">
                <div class="contact-item">
                    <h3><i class="fas fa-map-marker-alt"></i> ที่อยู่</h3>
                    <p>คณะบัญชีและการจัดการ มหาวิทยาลัยมหาสารคาม</p>
                    <p>ตำบลขามเรียง อำเภอกันทรวิชัย</p>
                    <p>จังหวัดมหาสารคาม 44150</p>
                    <div class="map-embed">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3840.048151970258!2d103.2459956153639!3d16.24682498877065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3122a57361453267%3A0x2f33f3333467f10b!2sFaculty%20of%20Engineering%2C%20Mahasarakham%20University!5e0!3m2!1sen!2sth!4v1628854432098!5m2!1sen!2sth"
                            width="100%"
                            height="300"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
                <div class="contact-item">
                    <h3><i class="fas fa-phone-alt"></i> ข้อมูลติดต่อ</h3>
                    <p><strong>เบอร์โทรศัพท์:</strong> <a href="tel:+66987654321">+66 (0) 98 765 4321</a></p>
                    <p><strong>อีเมล:</strong> <a href="mailto:contact@beefexportmsu.com">contact@beefexportmsu.com</a></p>
                    <p><strong>LINE Official:</strong> @BeefExport</p>
                    <p><strong>เวลาทำการ:</strong> จันทร์ - ศุกร์, 09:00 - 17:00 น.</p>
                </div>
            </div>

            <div class="contact-form-section">
                <h3>ส่งข้อความถึงเรา</h3>
                 <?php if(isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success" style="color: green; border: 1px solid green; padding: 15px; margin-bottom: 20px; background-color: #d4edda;">
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                 <?php if(isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger" style="color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; background-color: #f8d7da;">
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>
                <form id="contactForm" action="submit_contact.php" method="POST">
                    <div class="form-group">
                        <label for="contactName">ชื่อของคุณ:</label>
                        <input type="text" id="contactName" name="contactName" required>
                    </div>
                    <div class="form-group">
                        <label for="contactEmail">อีเมล:</label>
                        <input type="email" id="contactEmail" name="contactEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="contactSubject">เรื่อง:</label>
                        <input type="text" id="contactSubject" name="contactSubject" required>
                    </div>
                    <div class="form-group">
                        <label for="contactMessage">ข้อความของคุณ:</label>
                        <textarea id="contactMessage" name="contactMessage" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">ส่งข้อความ</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; // เรียกใช้ Footer กลาง ?>