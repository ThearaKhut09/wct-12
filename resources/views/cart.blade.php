@extends('layouts.app')

@section('title', 'Shopping Cart - E-smooth Online')

@section('content')
<style>
    .cart-page {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-title {
        font-size: 2.5rem;
        margin-bottom: 2rem;
        color: var(--text-primary);
    }

    .cart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
    }

    .cart-items {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--box-shadow);
    }

    [data-theme="dark"] .cart-items {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto auto;
        gap: 1rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 0.5rem;
        background: var(--light-color);
    }

    .item-details h3 {
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .item-price {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid var(--border-color);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .quantity-btn {
        background: var(--light-color);
        border: none;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        color: var(--text-primary);
        transition: var(--transition);
    }

    [data-theme="dark"] .quantity-btn {
        background: var(--dark-color);
    }

    .quantity-btn:hover {
        background: var(--primary-color);
        color: white;
    }

    .quantity-input {
        border: none;
        padding: 0.5rem;
        text-align: center;
        width: 3rem;
        background: white;
        color: var(--text-primary);
    }

    [data-theme="dark"] .quantity-input {
        background: var(--dark-color);
    }

    .item-total {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .remove-btn {
        background: none;
        border: none;
        color: var(--danger-color);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: var(--transition);
    }

    .remove-btn:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    .cart-summary {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: var(--box-shadow);
        height: fit-content;
        position: sticky;
        top: 2rem;
    }

    [data-theme="dark"] .cart-summary {
        background: var(--dark-color);
        border: 1px solid var(--border-color);
    }

    .summary-title {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .summary-row.total {
        font-size: 1.25rem;
        font-weight: 700;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
        margin-top: 1.5rem;
    }

    .checkout-section {
        margin-top: 2rem;
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-cart i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }

    .continue-shopping {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }    @media (max-width: 768px) {
        .cart-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
            position: relative;
        }

        .item-details {
            grid-column: 2;
        }

        .quantity-controls {
            grid-column: 1 / -1;
            justify-self: start;
            margin-top: 1rem;
        }

        .item-total {
            grid-column: 1 / -1;
            justify-self: end;
            margin-top: 0.5rem;
            font-weight: bold;
        }

        .remove-btn {
            position: absolute;
            top: 0;
            right: 0;
        }

        .cart-page {
            padding: 0 1rem;
        }

        .page-title {
            font-size: 2rem;
        }
    }

    .loading {
        opacity: 0.6;
        pointer-events: none;
    }
</style>

<div class="cart-page">
    <h1 class="page-title">Shopping Cart</h1>
    
    <div id="cartContent">
        <!-- Cart content will be loaded here by JavaScript -->
    </div>
</div>

@push('scripts')
<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];    function renderCart() {
        const cartContent = document.getElementById('cartContent');
        
        if (cart.length === 0) {
            cartContent.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some products to get started!</p>
                    <a href="{{ route('products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                    </a>
                </div>
            `;
            return;
        }

        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = subtotal * 0.08; // 8% tax
        const shipping = subtotal > 50 ? 0 : 9.99;
        const total = subtotal + tax + shipping;

        cartContent.innerHTML = `
            <div class="cart-container">
                <div class="cart-items">
                    ${cart.map(item => `
                        <div class="cart-item" data-id="${item.id}">
                            <img src="${item.image}" alt="${item.name}" class="item-image" 
                                 onerror="this.src='https://via.placeholder.com/100x100?text=Product'">
                            <div class="item-details">
                                <h3>${item.name}</h3>
                                <div class="item-price">$${parseFloat(item.price).toFixed(2)}</div>
                            </div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1" 
                                       onchange="updateQuantity(${item.id}, this.value)">
                                <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                            </div>
                            <div class="item-total">$${(parseFloat(item.price) * item.quantity).toFixed(2)}</div>
                            <button class="remove-btn" onclick="removeItem(${item.id})" title="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `).join('')}
                    
                    <div class="continue-shopping">
                        <a href="{{ route('products') }}" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <button class="btn btn-secondary" onclick="clearCart()">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </div>
                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal (${cart.reduce((sum, item) => sum + item.quantity, 0)} items)</span>
                        <span>$${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (8%)</span>
                        <span>$${tax.toFixed(2)}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>${shipping === 0 ? 'FREE' : '$' + shipping.toFixed(2)}</span>
                    </div>
                    ${shipping === 0 ? '' : `
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin: 0.5rem 0;">
                            <i class="fas fa-info-circle"></i> Free shipping on orders over $50
                        </div>
                    `}
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$${total.toFixed(2)}</span>
                    </div>
                    
                    <div class="checkout-section">
                        <button class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;" onclick="proceedToCheckout()">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </button>
                        <button class="btn btn-outline" style="width: 100%;" onclick="saveForLater()">
                            <i class="fas fa-bookmark"></i> Save for Later
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function updateQuantity(productId, newQuantity) {
        newQuantity = parseInt(newQuantity);
        
        if (newQuantity < 1) {
            removeItem(productId);
            return;
        }

        const item = cart.find(item => item.id === productId);
        if (item) {
            item.quantity = newQuantity;
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            renderCart();
            showNotification('Cart updated!', 'success');
        }
    }

    function removeItem(productId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            cart = cart.filter(item => item.id !== productId);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            renderCart();
            showNotification('Item removed from cart', 'success');
        }
    }

    function clearCart() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            cart = [];
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            renderCart();
            showNotification('Cart cleared', 'success');
        }
    }

    function proceedToCheckout() {
        if (cart.length === 0) {
            showNotification('Your cart is empty!', 'warning');
            return;
        }

        // For demo purposes, show a checkout simulation
        showNotification('Checkout feature coming soon! This is a demo.', 'info');
        
        // In a real application, you would redirect to checkout:
        // window.location.href = '/checkout';
    }    function saveForLater() {
        localStorage.setItem('savedCart', JSON.stringify(cart));
        showNotification('Cart saved for later!', 'success');
    }

    // Add test products to cart for demonstration
    function addTestProducts() {
        const testProducts = [
            {
                id: 1,
                name: "iPhone 14 Pro",
                price: 999.99,
                image: "https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=200&h=200&fit=crop",
                quantity: 1
            },
            {
                id: 2,
                name: "Samsung Galaxy Watch",
                price: 299.99,
                image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200&h=200&fit=crop",
                quantity: 2
            }
        ];
        
        testProducts.forEach(product => {
            const existingItem = cart.find(item => item.id === product.id);
            if (!existingItem) {
                cart.push(product);
            }
        });
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        renderCart();
        showNotification('Test products added to cart!', 'success');
    }

    // Initialize cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderCart();
        
        // Add test products button if cart is empty
        if (cart.length === 0) {
            setTimeout(() => {
                const emptyCart = document.querySelector('.empty-cart');
                if (emptyCart) {
                    emptyCart.innerHTML += `
                        <div style="margin-top: 1rem;">
                            <button class="btn btn-outline" onclick="addTestProducts()">
                                <i class="fas fa-plus"></i> Add Test Products
                            </button>
                        </div>
                    `;
                }
            }, 100);
        }
    });
</script>
@endpush
@endsection
