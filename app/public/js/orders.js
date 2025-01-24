statuses=  {
    1: "Ordered",
    2: "Received",
    3: "In return",
    4: "Returned"


}

function toggleCart() {
    const cart = document.getElementById('cart');
    cart.classList.toggle('active');
}

function updateCartView() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = ''; // Wyczyszczenie listy

    cartItems.forEach(item => {
        const div = document.createElement('div');
        const removeButton = document.createElement('span');
        const li = document.createElement('li');
        li.textContent = `${item.name} by ${item.author}`;

        div.classList.add('cart-item');
        div.appendChild(li);
        removeButton.textContent = 'X';
        removeButton.classList.add('remove-btn');
        removeButton.addEventListener('click', () => {
            removeFromCart(item.id);
        });
        div.appendChild(removeButton);

        cartItemsContainer.appendChild(div);

    });
}


function removeFromCart(bookId) {
    // Pobierz aktualny koszyk z LocalStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id != bookId);
    // Zapisz koszyk w LocalStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    // Zaktualizuj widok koszyka
    updateCartView();
}



async function createOrder(user_id) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Cart is empty');
        return;
    }
    const order = {
        user_id: user_id,
        bookIds: cart.map(book => book.id),
    };
    console.log(JSON.stringify(order));
    try {
        const response = await fetch('/order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(order),
        });
        console.log(await response.text());
        console.log("133");
        if (!response.ok) {
            // console.log(response.data);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        console.log("response", response);
        // const data = await response.json();
        // console.log(data);
        alert('Order created successfully');
        localStorage.removeItem('cart');
        updateCartView();
    } catch (error) {
        console.log(error.message);
        alert('Order creation failed');


    }
}


async function updateOrdersView() {

    const ordersContainer = document.getElementById('orders-list');
    ordersContainer.innerHTML = ''; // Wyczyszczenie listy
    const user_id = document.getElementById('user-id').innerHTML;

    try {
        const response = await fetch('/order?user_id='+user_id);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        console.log("response");
        const data = await response.json();
        console.log(data);
        data.forEach(order => {
            const div_header = document.createElement('div');
            div_header.classList.add('orders-list-header');
            div_header.innerHTML = `Order`;
            const div_status = document.createElement('div');
            div_status.classList.add('order-header-status');
            div_status.innerHTML = `status: ${statuses[order.statusId]}`;
            div_header.appendChild(div_status);

            const div_button = document.createElement('div');
            div_button.classList.add('received-order-button');
            div_button.innerHTML = `Return`;
            if(order.statusId === 2) {
            div_header.appendChild(div_button);
        }

            ordersContainer.appendChild(div_header);
            div_button.addEventListener('click', async () => {
                const response = await fetch('/order/' + order.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({orderId: order.id, statusId: 2}),
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                updateOrdersView();
            });
            order.books.forEach(book => {
                const div = document.createElement('div');
                div.classList.add('order-book-line');
                const div_image = document.createElement('div');
                div_image.classList.add('image');
                const img = document.createElement('img');
                img.classList.add('img');
                img.classList.add('bot-scrl-img');
                img.src = book.img_path;
                div_image.appendChild(img);
                div.appendChild(div_image);
                const div_name = document.createElement('div');
                div_name.classList.add('order-book-name');
                const a = document.createElement('a');
                a.href = 'http://google.com';
                const span = document.createElement('span');
                span.classList.add('bot-scrl-name');
                span.textContent = book.name;
                a.appendChild(span);
                div_name.appendChild(a);
                div.appendChild(div_name);
                const div_author = document.createElement('div');
                div_author.classList.add('order-book-author');
                const a2 = document.createElement('a');
                a2.href = 'http://google.com';
                const div_text = document.createElement('div');
                div_text.classList.add('text-wrapper');
                div_text.classList.add('bot-scrl-author');
                div_text.textContent = book.author;
                a2.appendChild(div_text);
                div_author.appendChild(a2);
                div.appendChild(div_author);
                ordersContainer.appendChild(div);
            
            })});

    }
    catch (error) {
        console.log(error.message);
        throw new Error('Failed to fetch orders');
    }

}



document.addEventListener('DOMContentLoaded', async () => {
    const cart = document.getElementById('cart');
    cart.classList.remove('active');
    updateCartView();
    document.getElementById("counter").textContent = JSON.parse(localStorage.getItem('cart')).length;

    await updateOrdersView();
});