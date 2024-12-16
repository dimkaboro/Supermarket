// cart.js

// Инициализация корзины из localStorage или как пустой массив
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Функция для добавления товара в корзину
function addToCart(name, image, quantity, price) {
    // Проверка, существует ли товар уже в корзине
    const existingItemIndex = cart.findIndex(item => item.name === name);
    
    if (existingItemIndex >= 0) {
        // Если товар существует, обновляем количество
        cart[existingItemIndex].quantity += quantity;
    } else {
        // Если товара нет, добавляем новый объект
        cart.push({ name, image, quantity, price });
    }
    
    // Сохранение обновленной корзины в localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Обновление отображения количества товаров в корзине
    updateCartCount();
    
    alert(`${name} Succesfuly added to cart`);
}

// Функция для обновления количества товаров в корзине в заголовке
function updateCartCount() {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
        cartCountElement.textContent = totalItems;
    }
}

// Функция для расчета и отображения общей суммы
function updateTotal() {
    const totalElement = document.getElementById('cart-total');
    if (totalElement) {
        const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
        totalElement.textContent = total.toFixed(2);
    }
}

// Функция для отображения товаров в корзине на странице оформления заказа
function displayCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = ''; // Очищаем существующие элементы
        
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>Free cart.</p>';
            document.getElementById('cart-total').textContent = '0.00';
            return;
        }
        
        cart.forEach((item, index) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex items-center justify-between';
            
            itemDiv.innerHTML = `
                <div class="flex items-center">
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md mr-4">
                    <div>
                        <h4 class="font-semibold">${item.name}</h4>
                        <p>Price: $${item.price.toFixed(2)}</p>
                        <p>Quantity: ${item.quantity}</p>
                    </div>
                </div>
                <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">Delete</button>
            `;
            
            cartItemsContainer.appendChild(itemDiv);
        });
        
        updateTotal();
    }
}

// Функция для удаления товара из корзины
function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    displayCartItems();
    updateTotal();
}

// Обработчик отправки формы оформления заказа
function handleCheckoutFormSubmit(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы

    if (cart.length === 0) {
        alert('Free cart');
        return;
    }

    // Здесь можно добавить дополнительную обработку данных формы, если необходимо

    alert('Uspech Nakupu'); // Сообщение о успешном заказе

    // Очистка корзины
    cart = [];
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    displayCartItems();
    updateTotal();
}

// Инициализация количества товаров и отображение корзины при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    displayCartItems();

    // Добавляем обработчик события для формы оформления заказа
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', handleCheckoutFormSubmit);
    }
});
