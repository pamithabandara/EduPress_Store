// main.js

// --- existing cart functions ---
function getCart() {
  const json = localStorage.getItem('edupress_cart');
  return json ? JSON.parse(json) : {};
}
function saveCart(cart) {
  localStorage.setItem('edupress_cart', JSON.stringify(cart));
}
function updateCartCount() {
  const cart = getCart();
  let totalQty = 0;
  for (let id in cart) totalQty += cart[id];
  document.getElementById('cart-count').innerText = totalQty;
}
function addToCart(productId) {
  const cart = getCart();
  cart[productId] = (cart[productId]||0) + 1;
  saveCart(cart);
  updateCartCount();
  alert('Added to cart!');
}

// --- new: render cart page ---
async function renderCart() {
  const cart = getCart();
  const tbody = document.getElementById('cart-items');
  let total = 0;
  tbody.innerHTML = '';
  for (const id in cart) {
    const qty = cart[id];
    const res = await fetch(`/edupress/api/product.php?id=${id}`);
    if (!res.ok) continue;
    const p = await res.json();
    const subtotal = p.price * qty;
    total += subtotal;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${p.name}</td>
      <td>Rs. ${p.price.toFixed(2)}</td>
      <td>${qty}</td>
      <td>Rs. ${subtotal.toFixed(2)}</td>
    `;
    tbody.appendChild(tr);
  }
  document.getElementById('cart-total').innerText = `Rs. ${total.toFixed(2)}`;
}

// navigate to checkout
function goToCheckout() {
  window.location.href = 'checkout.php';
}

// init
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
  if (document.getElementById('cart-items')) {
    renderCart();
  }
});
