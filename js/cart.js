// Cart Management Utility
class CartManager {
  constructor() {
    this.cart = this.loadCart();
  }

  loadCart() {
    const savedCart = localStorage.getItem('fontCart');
    return savedCart ? JSON.parse(savedCart) : [];
  }

  saveCart() {
    localStorage.setItem('fontCart', JSON.stringify(this.cart));
    this.updateBadge();
  }

  addItem(font, licenseType = 'personal') {
    // Check if already in cart
    const exists = this.cart.find(item => 
      item.id === font.id && item.licenseType === licenseType
    );

    if (exists) {
      return { success: false, message: 'This font is already in your cart' };
    }

    const price = licenseType === 'personal' ? 
      (font.price_personal || font.pricePersonal || font.price || 0) : 
      (font.price_commercial || font.priceCommercial || font.price || 0);

    const cartItem = {
      id: font.id,
      name: font.name,
      designer: font.designer,
      script: font.script || 'Unknown',
      category: font.category || 'Display',
      licenseType: licenseType,
      price: price,
      fileUrl: font.file_url || font.fileUrl || null
    };

    this.cart.push(cartItem);
    this.saveCart();
    return { success: true, message: `"${font.name}" added to cart!` };
  }

  removeItem(index) {
    this.cart.splice(index, 1);
    this.saveCart();
  }

  clearCart() {
    this.cart = [];
    this.saveCart();
  }

  getCart() {
    return this.cart;
  }

  getCount() {
    return this.cart.length;
  }

  getTotal() {
    return this.cart.reduce((sum, item) => sum + item.price, 0);
  }

  updateBadge() {
    const badges = document.querySelectorAll('#cart-badge');
    const count = this.getCount();
    
    badges.forEach(badge => {
      badge.textContent = count;
      badge.classList.toggle('hidden', count === 0);
    });
  }
}

// Global cart instance
window.cartManager = new CartManager();

// Update badge on page load
document.addEventListener('DOMContentLoaded', () => {
  if (window.cartManager) {
    window.cartManager.updateBadge();
  }
});
