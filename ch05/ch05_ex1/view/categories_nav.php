<nav>
    <ul>
        <!-- Lặp danh sách categories để tạo menu sidebar -->
        <?php foreach ($categories as $category) : ?>
        <li>
            <a href="?category_id=<?php echo $category['categoryID']; ?>">
                <?php echo htmlspecialchars($category['categoryName']); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>