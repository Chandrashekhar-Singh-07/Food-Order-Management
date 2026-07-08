<?php
require 'config.php';
require 'includes/helpers.php';

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY sort_order ASC");
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tadka Express — Order Food Online</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="hero">
    <div class="container">
        <div class="hero-eyebrow">Fresh &middot; Homestyle &middot; Delivered Hot</div>
        <h1>Ghar jaisa khana, seedha <em>aapke darwaze</em> tak.</h1>
        <p>Har dish taaza banti hai order milne ke baad — koi shortcut nahi, sirf asli tadka.</p>
        <div class="hero-cats">
            <?php
            mysqli_data_seek($categories, 0);
            while ($cat = mysqli_fetch_assoc($categories)) {
                $anchor = strtolower(str_replace([' ', '&'], ['-', 'n'], $cat['name']));
                echo '<a href="#' . h($anchor) . '">' . h($cat['name']) . '</a>';
            }
            ?>
        </div>
    </div>
</section>

<div class="container">
<?php
mysqli_data_seek($categories, 0);
while ($cat = mysqli_fetch_assoc($categories)):
    $anchor = strtolower(str_replace([' ', '&'], ['-', 'n'], $cat['name']));
    $style = category_style($cat['name']);
    $items = mysqli_query($conn, "SELECT * FROM menu_items WHERE category_id = " . (int)$cat['id'] . " AND is_available = 1");
    $itemCount = mysqli_num_rows($items);
    if ($itemCount === 0) continue;
?>
    <div class="tadka-line"><span></span><span></span><span></span><span></span><span></span></div>
    <section class="menu-section" id="<?php echo h($anchor); ?>">
        <h2><?php echo h($cat['name']); ?></h2>
        <div class="count"><?php echo $itemCount; ?> dishes</div>
        <div class="dish-grid">
        <?php while ($item = mysqli_fetch_assoc($items)):
            $qtyInCart = $_SESSION['cart'][$item['id']] ?? 0;
        ?>
            <article class="dish-card" data-id="<?php echo (int)$item['id']; ?>">
                <div class="dish-thumb" style="background: <?php echo $style['bg']; ?>;">
                    <span class="dish-veg <?php echo $item['is_veg'] ? '' : 'non-veg'; ?>"></span>
                    <?php echo $style['icon']; ?>
                </div>
                <div class="dish-body">
                    <h3><?php echo h($item['name']); ?></h3>
                    <p class="dish-desc"><?php echo h($item['description']); ?></p>
                    <div class="spice-row" aria-label="Spice level <?php echo (int)$item['spice_level']; ?> of 3">
                        <?php for ($i = 1; $i <= 3; $i++): ?>
                            <i class="<?php echo $i <= $item['spice_level'] ? 'active' : ''; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="dish-foot">
                        <span class="price"><?php echo number_format($item['price'], 0); ?></span>
                        <?php if ($qtyInCart > 0): ?>
                            <div class="qty-stepper">
                                <button type="button" class="qty-minus" aria-label="Ghatayein">-</button>
                                <span class="qty-count"><?php echo $qtyInCart; ?></span>
                                <button type="button" class="qty-plus" aria-label="Badhayein">+</button>
                            </div>
                        <?php else: ?>
                            <button type="button" class="add-btn">Add</button>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
        </div>
    </section>
<?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
