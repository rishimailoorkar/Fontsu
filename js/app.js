// Namma Fontsu - Shared JavaScript

// --- MOCK DATABASE ---
const db = {
  fonts: [
    { 
      id: 1, name: "Hombe Display", designer: "R. Designer", script: "Kannada", category: "Display", 
      pricePersonal: 499, priceCommercial: 2499, glyphs: 540, 
      tags: ["Retro", "Bold"], fontClass: "font-kannada", previewText: "ಕರ್ನಾಟಕ" 
    },
    { 
      id: 2, name: "Malgudi Serif", designer: "Type Studio", script: "Devanagari", category: "Serif", 
      pricePersonal: 0, priceCommercial: 1299, glyphs: 420, 
      tags: ["Classic", "Book"], fontClass: "font-hindi", previewText: "मालगुडी" 
    },
    { 
      id: 3, name: "Bengal Tiger", designer: "Kolkata Types", script: "Bengali", category: "Display", 
      pricePersonal: 699, priceCommercial: 3000, glyphs: 600, 
      tags: ["Aggressive", "Brush"], fontClass: "font-bengali", previewText: "বাঘ" 
    },
    { 
      id: 4, name: "Tech Bengaluru", designer: "Pixel Arts", script: "Kannada", category: "Sans", 
      pricePersonal: 899, priceCommercial: 4000, glyphs: 300, 
      tags: ["Modern", "Tech"], fontClass: "font-kannada", previewText: "ತಂತ್ರಜ್ಞಾನ" 
    },
    { 
      id: 5, name: "Chai Shop", designer: "Desi Art", script: "Devanagari", category: "Handwritten", 
      pricePersonal: 299, priceCommercial: 1500, glyphs: 200, 
      tags: ["Casual", "Menu"], fontClass: "font-hindi", previewText: "चाय" 
    },
  ],
  cart: [],
  user: { name: "Umesh", role: "designer" }
};

// --- CART FUNCTIONS ---
function updateCartBadge() {
  const badge = document.getElementById('cart-badge');
  if (badge) {
    badge.innerText = db.cart.length;
    badge.classList.remove('hidden');
    if(db.cart.length === 0) badge.classList.add('hidden');
  }
}

function addToCart(font, licenseType) {
  const price = licenseType === 'personal' ? font.pricePersonal : font.priceCommercial;
  
  db.cart.push({
    id: font.id,
    name: font.name,
    license: licenseType,
    price: price
  });
  
  updateCartBadge();
  alert("Added to cart!");
}

function removeFromCart(index) {
  db.cart.splice(index, 1);
  updateCartBadge();
  if (typeof renderCart === 'function') {
    renderCart();
  }
}

// --- HELPER FUNCTIONS ---
function getScriptPreviewText(script) {
  const previews = {
    'Kannada': 'ನಮ್ಮ ಫಾಂಟ್',
    'Devanagari': 'नमस्ते',
    'Bengali': 'বাংলা',
    'Tamil': 'தமிழ்',
    'Telugu': 'తెలుగు',
    'Malayalam': 'മലയാളം',
    'Gujarati': 'ગુજરાતી',
    'Punjabi': 'ਪੰਜਾਬੀ'
  };
  return previews[script] || 'Abc';
}

function getScriptFontClass(script) {
  const classes = {
    'Kannada': 'font-kannada',
    'Devanagari': 'font-hindi',
    'Bengali': 'font-bengali',
    'Tamil': 'font-kannada',
    'Telugu': 'font-kannada',
    'Malayalam': 'font-kannada',
    'Gujarati': 'font-hindi',
    'Punjabi': 'font-hindi'
  };
  return classes[script] || 'font-display';
}

function createFontCard(font) {
  const previewText = font.previewText || font.preview || getScriptPreviewText(font.script);
  const fontClass = font.fontClass || getScriptFontClass(font.script);
  const price = font.pricePersonal || font.price || 0;
  const designer = font.designer || 'Unknown Designer';
  
  return `
    <div class="bg-white border-2 border-text shadow-raw hover:shadow-raw-teal transition-all group cursor-pointer" onclick="window.location.href='font-detail.html?id=${font.id}'">
      <div class="h-48 bg-card border-b-2 border-text flex items-center justify-center p-4 overflow-hidden relative">
         <div class="absolute top-2 left-2 text-[10px] font-bold uppercase tracking-widest bg-white border border-text px-1">${font.script}</div>
         <h3 class="text-6xl font-bold text-text group-hover:scale-110 transition-transform duration-300 ${fontClass} text-center dynamic-preview">${previewText}</h3>
      </div>
      <div class="p-5">
        <div class="flex justify-between items-start mb-2">
          <h4 class="font-display text-xl font-bold group-hover:text-teal transition-colors">${font.name}</h4>
          <span class="font-bold text-sm">₹${price}</span>
        </div>
        <p class="text-xs font-bold text-text/50 uppercase tracking-widest mb-4">By ${designer}</p>
        <button class="w-full btn-outline py-2 text-xs">View Details</button>
      </div>
    </div>
  `;
}

// --- LOAD CART BADGE ON PAGE LOAD ---
document.addEventListener('DOMContentLoaded', function() {
  updateCartBadge();
});
