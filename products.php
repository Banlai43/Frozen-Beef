<?php include 'header.php'; ?>

    <main>
        <section class="products-header">
            <div class="container">
                <h2>Our Meats</h2>
                <div class="filter-sort-controls">
                    <div class="search-box">
                        <input type="text" id="searchProduct" placeholder="Search for products" onkeyup="filterProducts()">
                    </div>
                    <div class="filter-group">
                        <label for="meatType">ประเภทเนื้อ/Meat type:</label>
                        <select id="meatType" onchange="filterProducts()">
                            <option value="all">ALL</option>
                            <?php
                            $type_query = "SELECT DISTINCT product_type FROM products WHERE product_type IS NOT NULL AND product_type != '' ORDER BY product_type";
                            $type_result = $connect->query($type_query);
                            while($type_row = $type_result->fetch_assoc()){
                                echo '<option value="' . htmlspecialchars($type_row['product_type']) . '">' . htmlspecialchars($type_row['product_type']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="products-grid-section">
            <div class="container">
                <div class="products-grid" id="productsGrid">
                    <?php
                    $sql = "SELECT * FROM products ORDER BY id ASC";
                    $result = $connect->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                            <div class="product-card" data-type="<?php echo htmlspecialchars(strtolower($row['product_type'])); ?>" data-name="<?php echo htmlspecialchars(strtolower($row['name'])); ?>">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p class="product-id">Product code: <?php echo htmlspecialchars($row['product_code']); ?></p>
                                <p class="product-price">Price: ฿<?php echo number_format($row['price'], 2); ?>/KG.</p>
                                <div class="product-actions">
                                    <form action="cart_actions.php" method="POST" class="add-to-cart-form">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                                        <button type="submit" class="btn btn-add-cart">
                                        <i class="fas fa-cart-plus"></i> Add to cart</button>
                                    </form>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>ไม่พบสินค้าในขณะนี้</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>