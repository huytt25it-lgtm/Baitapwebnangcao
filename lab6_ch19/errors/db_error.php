<?php include 'view/header.php'; ?>
<main>
    <h1>Database Error</h1>
    <p>A database error occurred.</p>
    <p>Error message: <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
</main>
<?php include 'view/footer.php'; ?>
