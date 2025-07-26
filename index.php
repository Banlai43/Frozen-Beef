<?php include 'header.php'; ?>

    <main>
        <section class="hero-section">
            <div class="container">
                <div class="hero-text">
                    <h2><?php echo $lang['hero_title']; ?></h2>
                    <p><?php echo $lang['hero_subtitle']; ?></p>
                    <div class="hero-buttons">
                        <a href="products.php" class="btn btn-gold"><?php echo $lang['view_products']; ?></a>
                        <a href="contact.php" class="btn btn-outline-gold"><?php echo $lang['contact_us']; ?></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="quality-section">
            <div class="container">
                <h3><?php echo $lang['quality_title']; ?></h3>
                <div class="quality-grid">
                    <div class="quality-item">
                        <i class="fas fa-seedling icon-quality"></i> <h4><?php echo $lang['quality_1_title']; ?></h4>
                        <p><?php echo $lang['quality_1_desc']; ?></p>
                    </div>
                    <div class="quality-item">
                        <i class="fas fa-snowflake icon-quality"></i> <h4><?php echo $lang['quality_2_title']; ?></h4>
                        <p><?php echo $lang['quality_2_desc']; ?></p>
                    </div>
                    <div class="quality-item">
                        <i class="fas fa-boxes icon-quality"></i> <h4><?php echo $lang['quality_3_title']; ?></h4>
                        <p><?php echo $lang['quality_3_desc']; ?></p>
                    </div>
                    <div class="quality-item">
                        <i class="fas fa-award icon-quality"></i> <h4><?php echo $lang['quality_4_title']; ?></h4>
                        <p><?php echo $lang['quality_4_desc']; ?></p>
                    </div>
                </div>
            </div>
        </section>
    </main>

   <?php include 'footer.php'; ?>

</body>
</html>