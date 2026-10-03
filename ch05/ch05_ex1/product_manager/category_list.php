<?php include '../view/header.php'; ?>
<main>
    <h1>Category List</h1>

    <!-- Bảng danh sách Category -->
    <table>
        <tr>
            <th>Name</th>
            <th>&nbsp;</th>
        </tr>
        <?php foreach ($categories as $category) : ?>
        <tr>
            <td><?php echo htmlspecialchars($category['categoryName']); ?></td>
            <td>
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="delete_category">
                    <input type="hidden" name="category_id" value="<?php echo $category['categoryID']; ?>">
                    <input type="submit" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Form Thêm Category -->
    <h2>Add Category</h2>
    <form action="index.php" method="post" id="add_category_form">
        <input type="hidden" name="action" value="add_category">
        
        <label>Name:</label>
        <input type="text" name="name">
        <input id="add_category_button" type="submit" value="Add"><br>
    </form>
    
    <p><a href="index.php?action=list_products">List Products</a></p>
</main>
<?php include '../view/footer.php'; ?>