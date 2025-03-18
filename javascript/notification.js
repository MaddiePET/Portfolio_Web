document.addEventListener('DOMContentLoaded', function () {
    // Attach event listeners for buttons
    document.querySelector('.refresh-button').addEventListener('click', refreshNotifications);
    document.querySelector('.clear-button').addEventListener('click', clearNotifications);

    // Add hover functionality for notifications
    addHoverEffectToNotifications();
});

// Refresh notifications via AJAX
function refreshNotifications() {
    fetch('notification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'refresh_notifications=true'
    })
    .then(response => response.text())
    .then(html => {
        const notificationContainer = document.getElementById('notification-container');
        notificationContainer.innerHTML = html;

        // Attach click event listeners to newly created notifications
        const notificationElements = notificationContainer.querySelectorAll('.notification');
        notificationElements.forEach(notification => {
            const productID = notification.dataset.productId;
            notification.addEventListener('click', function () {
                redirectAndScrollToProduct(productID);
            });
        });

        // Add hover effect to new notifications
        addHoverEffectToNotifications();
    })
    .catch(error => console.error('Error refreshing notifications:', error));
}

// Clear notifications via AJAX
function clearNotifications() {
    fetch('notification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'clear_notifications=true'
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('notification-container').innerHTML = html;
    })
    .catch(error => console.error('Error clearing notifications:', error));
}

// Hover effect logic
function addHoverEffectToNotifications() {
    const notificationElements = document.querySelectorAll('.notification');
    notificationElements.forEach(notification => {
        notification.addEventListener('mouseenter', function () {
            notification.style.transform = 'scale(1.05)';
            notification.style.transition = 'transform 0.3s ease';
            notification.style.backgroundColor = '#f5f5f5'; // Optional: Add hover color
        });

        notification.addEventListener('mouseleave', function () {
            notification.style.transform = 'scale(1)';
            notification.style.backgroundColor = '#fefefe'; // Revert to original background color
        });
    });
}

// Redirect and scroll logic
function redirectAndScrollToProduct(productId) {
    console.log('Redirecting to product', productId);
    window.location.href = 'inventory.php#product-' + productId;

    setTimeout(function() {
        highlightProduct(productId);
    }, 100);
}

function highlightProduct(productId) {
    var productElement = document.getElementById('product-' + productId);
    if (productElement) {
        productElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        productElement.classList.add('highlighted');

        setTimeout(function() {
            productElement.classList.remove('highlighted');
        }, 5000);
    }
}

// Highlight product on page load
window.addEventListener('load', function () {
    if (window.location.hash.startsWith('#product-')) {
        const productId = window.location.hash.replace('#product-', '');
        highlightProduct(productId);
    }
});
