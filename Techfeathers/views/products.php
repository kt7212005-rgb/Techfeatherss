<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$products = $products ?? [];
?>

<?php if ($message): ?>
    <div class="message" style="background: #2ecc71; color: #fff;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card-panel">
    <h2>Add New Product</h2>
    <form method="post" style="display:grid; gap:12px; max-width:480px;">
        <input type="hidden" name="add_product" value="1" />
        <div class="field">
            <label for="name">Product Name</label>
            <input id="name" name="name" type="text" required />
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>
        <div class="field">
            <label for="price">Price (₱)</label>
            <input id="price" name="price" type="number" step="0.01" min="0" required />
        </div>
        <div class="field">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select category</option>
                <option value="eggs">Eggs</option>
                <option value="feed">Feed</option>
                <option value="fertilizer">Fertilizer</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="field">
            <label for="quantity">Available Quantity</label>
            <input id="quantity" name="quantity" type="number" min="0" required />
        </div>
        <button class="button" type="submit">Add Product</button>
    </form>
</div>

<div class="card-panel">
    <h2>Existing Products</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['description'] ?? '') ?></td>
                    <td>₱<?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars(ucfirst($product['category'])) ?></td>
                    <td><?= htmlspecialchars($product['available_quantity']) ?></td>
                    <td>
                        <button class="button" onclick="editProduct(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', '<?= htmlspecialchars(addslashes($product['description'] ?? '')) ?>', <?= $product['price'] ?>, '<?= $product['category'] ?>', <?= $product['available_quantity'] ?>)" style="background: #f39c12; color: white; padding: 4px 8px; font-size: 0.8rem;">Edit</button>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            <input type="hidden" name="delete_product" value="1" />
                            <input type="hidden" name="id" value="<?= $product['id'] ?>" />
                            <button class="button" type="submit" style="background: #e74c3c; color: white; padding: 4px 8px; font-size: 0.8rem;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 500px; width: 90%;">
        <h3>Edit Product</h3>
        <form method="post">
            <input type="hidden" name="update_product" value="1" />
            <input type="hidden" id="edit_id" name="id" />
            <div style="display: grid; gap: 12px;">
                <div>
                    <label>Name</label>
                    <input id="edit_name" name="name" type="text" required />
                </div>
                <div>
                    <label>Description</label>
                    <textarea id="edit_description" name="description" rows="3"></textarea>
                </div>
                <div>
                    <label>Price (₱)</label>
                    <input id="edit_price" name="price" type="number" step="0.01" min="0" required />
                </div>
                <div>
                    <label>Category</label>
                    <select id="edit_category" name="category" required>
                        <option value="eggs">Eggs</option>
                        <option value="feed">Feed</option>
                        <option value="fertilizer">Fertilizer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label>Available Quantity</label>
                    <input id="edit_quantity" name="quantity" type="number" min="0" required />
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeEditModal()" class="button" style="background: #95a5a6;">Cancel</button>
                    <button type="submit" class="button">Update Product</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editProduct(id, name, description, price, category, quantity) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>