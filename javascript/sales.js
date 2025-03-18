// Wait for the document to fully load before running scripts
document.addEventListener("DOMContentLoaded", function() {
    const notification = document.querySelector('.notification');
    const memberIDField = document.getElementById("memberID");

    // If a notification element is present, hide it after a delay
    if (notification) {
        setTimeout(function() {
            // Start fading out notification after 3 seconds
            notification.style.opacity = '0'; 
        }, 3000);

        setTimeout(function() {
            // Remove notification from display after an additional 0.5 seconds
            notification.style.display = 'none';
        }, 3500);
    }

    // Prevent users from deleting the "M" prefix
    memberIDField.addEventListener("input", function() {
        if (!memberIDField.value.startsWith("M")) {
            // Keep only numeric characters after "M"
            memberIDField.value = "M" + memberIDField.value.replace(/[^0-9]/g, '');
        }
    });

    // Prevent backspace or delete from removing "M"
    memberIDField.addEventListener("keydown", function(event) {
        if ((event.key === "Backspace" || event.key === "Delete") && memberIDField.selectionStart <= 1) {
            event.preventDefault();
        }
    });
});

// Function to remove "M" before form submission
function prepareMemberID() {
    const memberIDField = document.getElementById("memberID");
    if (memberIDField.value.startsWith("M")) {
        memberIDField.value = memberIDField.value.slice(1); // Remove "M" prefix before submitting
    }
}

// Function to dynamically generate product input fields based on user input
function generateProductFields() {
    const numProducts = document.getElementById("numProducts").value;
    const productFields = document.getElementById("productFields");
    productFields.innerHTML = '';  // Clear any existing product fields

    // Loop to create new input fields for each product
    for (let i = 1; i <= numProducts; i++) {
        productFields.innerHTML += `
            <div class="product-entry">
                <h3>Product ${i}</h3>
                <br>
                <p>
                    <label for="productID${i}">Product ID </label>
                    <input type="text" name="productID${i}" id="productID${i}" required>
                </p>
                <p>
                    <label for="quantity${i}">Product Quantity</label>
                    <input type="number" name="quantity${i}" id="quantity${i}" required min="1">
                </p>
            </div>
        `;
    }
}

// Set the transaction date to the current date when the page loads
window.onload = function() {
    const transactionDate = document.getElementById('transactionDate');
    const today = new Date();

    // Format the date as YYYY-MM-DD
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Month is 0-based, so add 1
    const day = String(today.getDate()).padStart(2, '0');

    transactionDate.value = `${year}-${month}-${day}`; // Set the date input value
};

// Preserve the transaction date value when the form is reset
document.getElementById('salesForm').addEventListener('reset', function (e) {
    var transactionDateField = document.getElementById('transactionDate'); 
    var savedtransactionDate = transactionDateField.value; // Save current transaction date

    setTimeout(function () {
        // Reapply saved transaction date to prevent resetting to default
        transactionDateField.value = savedtransactionDate;
    }, 0);
});