/* ====================== BUCKET LIST (CART) ====================== */
let bucket = JSON.parse(localStorage.getItem('bucket')) || [];

/* Add item to bucket */
function addToBucket(name, price) {
    bucket.push({ name, price });
    localStorage.setItem('bucket', JSON.stringify(bucket));
    updateBucketUI();
    alert(`${name} added to Bucket List!`);
}

/* Clear entire bucket */
function clearBucket() {
    bucket = [];
    localStorage.setItem('bucket', JSON.stringify(bucket));
    updateBucketUI();
}

/* Update UI: badge, modal list, totals */
function updateBucketUI() {
    const countEl = document.getElementById('bucket-count');
    const itemsEl = document.getElementById('bucket-items');
    const subEl = document.getElementById('bucket-subtotal');
    const delEl = document.getElementById('bucket-delivery');
    const totalEl = document.getElementById('bucket-total');

    // Update badge
    countEl.textContent = bucket.length;

    // Update item list
    itemsEl.innerHTML = '';
    let subtotal = 0;
    bucket.forEach(item => {
        subtotal += item.price;
        const li = document.createElement('li');
        li.innerHTML = `<span>${item.name}</span><span>R${item.price.toFixed(2)}</span>`;
        itemsEl.appendChild(li);
    });

    // Delivery fee
    const deliveryOption = document.querySelector('input[name="delivery"]:checked');
    const deliveryValue = deliveryOption ? deliveryOption.value : 'pickup';
    const deliveryFee = deliveryValue === 'standard' ? 35 : deliveryValue === 'express' ? 70 : 0;
    const grandTotal = subtotal + deliveryFee;

    subEl.textContent = subtotal.toFixed(2);
    delEl.textContent = deliveryFee.toFixed(2);
    totalEl.textContent = grandTotal.toFixed(2);
}

/* ====================== BUCKET LIST MODAL CONTROLS ====================== */
const modal = document.getElementById('bucket-modal');
const openBtn = document.getElementById('bucket-btn');
const closeX = document.querySelector('.bucket-close');
const clearBtn = document.getElementById('clear-bucket');
const checkoutBtn = document.getElementById('checkout-btn');

// Open modal
openBtn.onclick = () => {
    modal.style.display = 'flex';
    updateBucketUI();
};

// Close modal
closeX.onclick = () => modal.style.display = 'none';
window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display === 'flex') {
        modal.style.display = 'none';
    }
});

// Clear bucket
clearBtn.onclick = clearBucket;

// Recalculate on delivery change
document.querySelectorAll('input[name="delivery"]').forEach(radio => {
    radio.addEventListener('change', updateBucketUI);
});

// Checkout
checkoutBtn.onclick = () => {
    const delivery = document.querySelector('input[name="delivery"]:checked').value;
    const total = document.getElementById('bucket-total').textContent;

    if (bucket.length === 0) {
        alert('Your Bucket List is empty!');
        return;
    }

    const message = `Checkout Summary:\n\n` +
        `Items: ${bucket.length}\n` +
        `Delivery: ${delivery.charAt(0).toUpperCase() + delivery.slice(1)}\n` +
        `Total: R${total}\n\n` +
        `Proceed to payment?`;

    if (confirm(message)) {
        alert('Thank you! Your order has been received.');
        clearBucket();
        modal.style.display = 'none';
    }
};

/* ====================== MENU FILTERING ====================== */
function filterProducts() {
    const selected = document.getElementById('category-select').value;
    document.querySelectorAll('.product-card').forEach(card => {
        const matches = selected === 'all' || card.classList.contains(`category-${selected}`);
        card.style.display = matches ? 'block' : 'none';
    });
}

/* Scroll to menu */
function scrollToMenu() {
    document.getElementById('menu').scrollIntoView({ behavior: 'smooth' });
}

/* ====================== INITIALIZATION ====================== */
document.addEventListener('DOMContentLoaded', () => {
    updateBucketUI();
    filterProducts();
});