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
});