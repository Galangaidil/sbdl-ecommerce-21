<!-- SHOPPINGCART
 ****************************************************
 * Developer Team : Galang aidil akbar, Mardha Yuda Kurniawan, Ahmad Sofiyan Alfandi. 
 * Release Date   : 24 May 2021
 * Twitter        : https://twitter.com/galang_aidil, https://twitter.com/alfandi04_ 
 * E-mail         : galangaidil45@gmail.com, yumardha@gmail.com, alfafandi0@gmail.com.
-->

<?php
// If the user clicked the add to cart button on the product page we can check for the form data
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {

  // Set the post variables so we easily identify them, also make sure they are integer
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];

  // we basically are checking if the product exists in our database, using oop technique we create new class.
  require_once "manage-product.php";
  $conn = new product();

  // Fetch the product from the database using check_product function
  $product = $conn->check_product($_POST['product_id']);

  // Check if the product exists (array is not empty)
  if ($product && $quantity > 0) {
    // Product exists in database, now we can create/update the session variable for the cart
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
      if (array_key_exists($product_id, $_SESSION['cart'])) {

        // Product exists in cart so just update the quanity
        $_SESSION['cart'][$product_id] += $quantity;
      } else {
        // Product is not in cart so add it
        $_SESSION['cart'][$product_id] = $quantity;
      }
    } else {
      // There are no products in cart, this will add the first product to cart
      $_SESSION['cart'] = array($product_id => $quantity);
    }
  }
  // Prevent form resubmission...
  header('location: index.php?page=cart');
  exit;
}
// Remove product from cart, check for the URL param "remove", this is the product id, make sure it's a number and check if it's in the cart
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {

  // Remove the product from the shopping cart
  unset($_SESSION['cart'][$_GET['remove']]);
}

// Update product quantities in cart if the user clicks the "Update" button on the shopping cart page
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
  // Loop through the post data so we can update the quantities for every product in cart
  foreach ($_POST as $k => $v) {
    if (strpos($k, 'quantity') !== false && is_numeric($v)) {
      $id = str_replace('quantity-', '', $k);
      $quantity = (int)$v;

      // Always do checks and validation
      if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {

        // Update new quantity
        $_SESSION['cart'][$id] = $quantity;
      }
    }
  }

  // Prevent form resubmission...
  header('location: index.php?page=cart');
  exit;
}

// Send the user to the place order page if they click the Place Order button, also the cart should not be empty
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  // we have to require costumer to login before be abble place order.
  if (empty($_SESSION['loggedin'])) {
    //  throw to login page
    header('Location: login/.');
    exit;
  }

  // Do Something on Here. 
  $stmt = $pdo->prepare('INSERT INTO `orders` (`productID`, `accountsID`, `date_created`, `jumlah`, `total`) VALUES (?,?,?,?,?)');

  $productID = $_POST['id'];
  $accountsID = $_SESSION['id'];
  $current_date_time = date("Y-m-d H:i:s");
  $jumlah = $_POST['jumlah'];
  $total = $_POST['total'];

  try {
    $stmt->execute([$productID, $accountsID, $current_date_time, $jumlah, $total]);
  } catch (Exception $e) {
    echo "Someting went wrong happen <br>";
    exit;
  }

  header('Location: index.php?page=placeorder');
  exit;
}

// Check the session variable for products in cart
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;

// If there are products in cart
if ($products_in_cart) {

  // There are products in the cart so we need to select those products from the database
  // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
  $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
  $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $array_to_question_marks . ')');

  // We only need the array keys, not the values, the keys are the id's of the products
  $stmt->execute(array_keys($products_in_cart));

  // Fetch the products from the database and return the result as an Array
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Calculate the subtotal
  foreach ($products as $product) {
    $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
  }
}
?>

<!-- 
EN : to find out if the customer has logged in or not, we have to check session 'loggedin'. if it isset and has a value of true, then use costumer_template_header(?,?).if not, use template_header().

ID : juntuk mengetahui pelanggan sudah login atau belum, kita bisa mengeceknya menggunakan session 'loginin'. jika sudah diatur dan memiliki nilai true, maka gunakan costumer_template_header(?,?). kalau belum, gunakan template_header()
-->
<?php if (isset($_SESSION['loggedin'])) : ?>
  <?= costumer_template_header('Cart', $_SESSION['name']) ?>
<?php else : ?>
  <?= template_header('Cart') ?>
<?php endif; ?>

<div class="cart content-wrapper">
  <h1>Shopping Cart</h1>
  <form action="index.php?page=cart" method="post">
    <table>
      <thead>
        <tr>
          <td colspan="2">Product</td>
          <td>Price</td>
          <td>Quantity</td>
          <td>Total</td>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)) : ?>
          <tr>
            <td colspan="5" style="text-align:center;">You have no products added in your Shopping Cart</td>
          </tr>
        <?php else : ?>
          <?php foreach ($products as $product) : ?>
            <tr>
              <td class="img">
                <a href="index.php?page=product&id=<?= $product['id'] ?>">
                  <img src="assets/products/<?= $product['img'] ?>" width="50" height="50" alt="<?= $product['name'] ?>">
                </a>
              </td>
              <td>
                <a href="index.php?page=product&id=<?= $product['id'] ?>"><?= $product['name'] ?></a>
                <br>
                <a href="index.php?page=cart&remove=<?= $product['id'] ?>" class="remove">Remove</a>
              </td>
              <td class="price">&dollar;<?= $product['price'] ?></td>
              <td class="quantity">
                <input type="number" name="quantity-<?= $product['id'] ?>" value="<?= $products_in_cart[$product['id']] ?>" min="1" max="<?= $product['quantity'] ?>" placeholder="Quantity" required>
              </td>
              <td class="price">&dollar;<?= $product['price'] * $products_in_cart[$product['id']] ?></td>
            </tr>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="jumlah" value="<?= $products_in_cart[$product['id']] ?>">
            <input type="hidden" name="total" value="<?= $subtotal ?>">
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="subtotal">
      <span class="text">Subtotal</span>
      <span class="price">&dollar;<?= $subtotal ?></span>
    </div>
    <div class="buttons">
      <input type="submit" value="Update" name="update">
      <input type="submit" value="Place Order" name="placeorder">
    </div>
  </form>
</div>

<?= template_footer() ?>