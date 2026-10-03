<!DOCTYPE html>
<html>
<head>
    <title>My Shopping Cart</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>My Shopping Cart</h1>
    </header>
    <main>
        <!-- Yêu cầu 7: Hiển thị Session ID -->
        <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>

        <?php if (empty($_SESSION['cart12']) || count($_SESSION['cart12']) == 0) : ?>
            <p>There are no items in your cart.</p>
        <?php else: ?>
            <form action="." method="post">
                <input type="hidden" name="action" value="update">
                <table>
                    <tr id="cart_header">
                        <th class="left">Item</th>
                        <th class="right">Item Cost</th>
                        <th class="right">Quantity</th>
                        <th class="right">Item Total</th>
                    </tr>
                    <?php 
                    $subtotal = 0;
                    foreach ($_SESSION['cart12'] as $key => $item) :
                        $cost = $products[$key]['cost'];
                        $total = $cost * $item;
                        $subtotal += $total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($products[$key]['name']); ?></td>
                            <td class="right">$<?php echo number_format($cost, 2); ?></td>
                            <td class="right">
                                <input type="text" class="cart_qty"
                                    name="newqty[<?php echo $key; ?>]"
                                    value="<?php echo $item; ?>">
                            </td>
                            <td class="right">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="right"><b>Subtotal:</b></td>
                        <td class="right">$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="right">
                            <input type="submit" value="Update Cart">
                        </td>
                    </tr>
                </table>
            </form>
        <?php endif; ?>

        <p><a href=".?action=show_add_item">Add Item</a></p>
        <p><a href=".?action=empty_cart">Empty Cart</a></p>
        
        <!-- Yêu cầu 9: Link End Session and Delete Cookie -->
        <p><a href=".?action=end_session">End Session and Delete Cookie</a></p>
    </main>
</body>
</html>