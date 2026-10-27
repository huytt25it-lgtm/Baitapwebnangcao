<?php include 'view/header.php'; ?>
<main>
    <h1>Selected Product List</h1>
    <h2><?php echo htmlspecialchars($category_name, ENT_QUOTES, 'UTF-8'); ?></h2>
    <ul>
    <?php foreach ($products as $product) : ?>
        <li> <?php echo htmlspecialchars($product['productName'], ENT_QUOTES, 'UTF-8'); ?></li>
    <?php endforeach; ?>
    </ul>

    <h1>Delete Message</h1>
    <p> <?php echo htmlspecialchars($delete_message, ENT_QUOTES, 'UTF-8'); ?></p>

    <h1>Insert Message</h1>
    <p> <?php echo htmlspecialchars($insert_message, ENT_QUOTES, 'UTF-8'); ?></p>
</main><!-- end content -->
<?php include 'view/footer.php'; ?>
