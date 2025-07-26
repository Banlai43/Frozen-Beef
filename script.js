// ฟังก์ชันนี้จะทำงานเมื่อหน้าเว็บโหลดเสร็จสมบูรณ์
document.addEventListener('DOMContentLoaded', () => {

    // --- ส่วนที่ทำงานเฉพาะในหน้า "สินค้า" (products.php) ---
    const productsGrid = document.getElementById('productsGrid');
    if (productsGrid) {
        const searchInput = document.getElementById('searchProduct');
        const meatTypeSelect = document.getElementById('meatType');

        // สร้างฟังก์ชัน filterProducts ให้เรียกใช้ได้จาก HTML
        window.filterProducts = () => {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = meatTypeSelect.value.toLowerCase();
            const products = productsGrid.querySelectorAll('.product-card');

            products.forEach(product => {
                const productName = product.dataset.name.toLowerCase();
                const productType = product.dataset.type.toLowerCase();

                const matchesSearch = productName.includes(searchTerm);
                const matchesType = (selectedType === 'all') || (productType === selectedType);

                if (matchesSearch && matchesType) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });
        };
    }

    // --- ส่วนที่ทำงานเฉพาะในหน้า "สั่งซื้อ" (order.php) ---
    const paymentMethodSelect = document.getElementById('paymentMethod');
    if (paymentMethodSelect) {
        const bankTransferDetails = document.getElementById('bankTransferDetails');

        // ฟังก์ชันสำหรับซ่อน/แสดงรายละเอียดการโอนเงิน
        const toggleBankDetails = () => {
            if (paymentMethodSelect.value === 'bank_transfer' && bankTransferDetails) {
                bankTransferDetails.style.display = 'block';
            } else if (bankTransferDetails) {
                bankTransferDetails.style.display = 'none';
            }
        };

        // เรียกใช้ฟังก์ชันเมื่อมีการเปลี่ยนแปลง
        paymentMethodSelect.addEventListener('change', toggleBankDetails);
        
        // เรียกใช้ตอนโหลดหน้าเว็บครั้งแรก เผื่อมีการเลือกค่าไว้แล้ว
        toggleBankDetails();
    }
  document.addEventListener('DOMContentLoaded', () => {

    // --- Add to Cart with AJAX ---
    // ใช้ event delegation เพื่อให้แน่ใจว่าฟอร์มที่ถูกสร้างขึ้นมาทีหลังก็ทำงานได้
    document.body.addEventListener('submit', function(event) {
        // ตรวจสอบว่าเป็นฟอร์มเพิ่มสินค้าหรือไม่
        if (event.target.matches('.add-to-cart-form')) {
            // ป้องกันไม่ให้ฟอร์มทำงานแบบปกติ
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const actionUrl = form.action;

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // อัปเดตตัวเลขบนไอคอนตะกร้า
                    const cartBadges = document.querySelectorAll('.cart-count-badge');
                    if (cartBadges.length > 0) {
                        cartBadges.forEach(badge => {
                            badge.textContent = data.cart_item_count;
                        });
                    }
                    
                    // แสดงข้อความแจ้งเตือน
                    showToast(data.message);
                } else {
                    // แสดงข้อความ error ถ้า server ส่ง success: false
                    showToast(data.message || 'เกิดข้อผิดพลาด', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            });
        }
    });

});

// ฟังก์ชันสำหรับแสดง Toast Notification (ไม่ต้องแก้ไข)
function showToast(message, type = 'success') {
    // สร้าง container ถ้ายังไม่มี
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.textContent = message;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 100);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 3000);
}
});