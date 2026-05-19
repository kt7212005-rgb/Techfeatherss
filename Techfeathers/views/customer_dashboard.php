<?php include __DIR__ . '/../includes/header.php'; ?>

<div style="text-align: center; padding: 40px 20px;">
    <h1>Welcome to Our Farm!</h1>
    <p style="font-size: 1.2rem; color: var(--muted); margin-bottom: 30px;">Fresh products straight from our poultry farm</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; max-width: 800px; margin: 0 auto;">
        <div style="background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; padding: 30px; border-radius: 12px; text-align: center;">
            <h2 style="margin: 0 0 10px;">Fresh Eggs</h2>
            <p style="margin: 0 0 20px;">Farm-fresh eggs from healthy chickens</p>
            <a href="order.php" class="button" style="background: white; color: #2ecc71; border: none;">Order Now</a>
        </div>

        <div style="background: linear-gradient(135deg, #e67e22, #d35400); color: white; padding: 30px; border-radius: 12px; text-align: center;">
            <h2 style="margin: 0 0 10px;">Quality Feed</h2>
            <p style="margin: 0 0 20px;">Nutritious feed for your poultry</p>
            <a href="order.php" class="button" style="background: white; color: #e67e22; border: none;">Order Now</a>
        </div>

        <div style="background: linear-gradient(135deg, #9b59b6, #8e44ad); color: white; padding: 30px; border-radius: 12px; text-align: center;">
            <h2 style="margin: 0 0 10px;">Natural Fertilizer</h2>
            <p style="margin: 0 0 20px;">Organic manure from our farm</p>
            <a href="order.php" class="button" style="background: white; color: #9b59b6; border: none;">Order Now</a>
        </div>
    </div>

    <div style="margin-top: 40px;">
        <a href="order.php" class="button" style="font-size: 1.2rem; padding: 15px 30px;">Browse All Products</a>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>