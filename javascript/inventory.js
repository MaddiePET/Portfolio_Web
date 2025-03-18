var searchBar = document.getElementById('searchBar');

var addForm = document.getElementById('addForm');
var updateForm = document.getElementById('updateForm');
var viewForm = document.getElementById('viewForm');
var deleteForm = document.getElementById('deleteForm');

var AddFormButton = document.getElementById('AddButton');

var closeAddForm = document.getElementById('closeAddForm');
var closeUpdateForm = document.getElementById('closeUpdateForm');
var closeViewForm = document.getElementById('closeViewForm');
var cancelButton = document.getElementById('cancelButton');

AddFormButton.onclick = function() {
    addForm.style.display = 'flex';
}

closeAddForm.onclick = function() {
    addForm.style.display = 'none';
}

closeUpdateForm.onclick = function() {
    updateForm.style.display = 'none';
}

closeViewForm.onclick = function() {
    viewForm.style.display = 'none';
}

cancelButton.onclick = function() {
    deleteForm.style.display = 'none';
}

searchBar.addEventListener('input', function() {
    var searchValue = searchBar.value.toLowerCase();
    var productCards = document.getElementsByClassName('product-card'); 

    for (var i = 0; i < productCards.length; i++) {
        var productCard = productCards[i];
        var productName = productCard.getElementsByTagName('h3')[0].innerText.toLowerCase(); 
        var productID = productCard.getElementsByTagName('p')[0].innerText.toLowerCase();

        productID = productID.replace('product id: ', '').trim();

        if (productName.startsWith(searchValue) || productID.startsWith(searchValue)) {
            productCard.style.display = 'block'; 
        } else {
            productCard.style.display = 'none'; 
        }
    }
});

let originalData = {};  // Global object to store original data

function openUpdateForm(id, name, price, category, quantity, restockDate) {
    // Store original values in the global object
    originalData = {
        id: id,
        name: name,
        price: price,
        category: category,
        quantity: quantity,
        restockDate: restockDate
    };

    // Fill the form with the data passed into the function
    document.getElementById('update_product_id').value = id;
    document.getElementById('update_product_name').value = name;
    document.getElementById('update_inv_qty').value = quantity;
    document.getElementById('update_restock_date').value = restockDate;

    // Set the title of the form
    document.getElementById('updateFormTitle').innerText = `Update ${name}`;

    // Show the update form
    updateForm.style.display = 'flex';
}

// Function to reset the form when "Cancel" is clicked
function resetForm() {
    // Reset the form fields to the original values
    document.getElementById('update_product_id').value = originalData.id;
    document.getElementById('update_product_name').value = originalData.name;
    document.getElementById('update_inv_qty').value = originalData.quantity;
    document.getElementById('update_restock_date').value = originalData.restockDate;

    // Optionally reset the form title to reflect the original state
    document.getElementById('updateFormTitle').innerText = `Update ${originalData.name}`;
}

function openViewForm(name, updatedQuantity, restockDate) {
    document.getElementById('restockDateValue').innerText = restockDate;
    document.getElementById('stockValue').innerText = updatedQuantity; 
    
    document.getElementById('viewFormTitle').innerText = `${name} Details`;
    
    viewForm.style.display = 'flex';
}

function openDeleteForm(id, name) {
    document.getElementById('delete_product_id').value = id; 
    
    document.getElementById('deleteFormTitle').innerText = `Delete ${name}`;
    
    deleteForm.style.display = 'flex'; 
}

document.getElementById('addForm').addEventListener('reset', function (e) {
    var restockDateField = document.getElementById('last_restock_date'); 
    var savedRestockDate = restockDateField.value;

    setTimeout(function () {
        restockDateField.value = savedRestockDate; 
    }, 0);
});

const filterButton = document.getElementById("filterButton");
const filterSidebar = document.getElementById("filterSidebar");
const closeSidebar = document.getElementById("closeSidebar");

filterButton.addEventListener("click", () => {
    filterSidebar.classList.add("sidebar-active");
});

closeSidebar.addEventListener("click", () => {
    filterSidebar.classList.remove("sidebar-active");
});

function toggleCategoryCheckboxes() {
    const selectAllCheckbox = document.getElementById('selectAllCategories');
    const categoryCheckboxes = document.querySelectorAll('input[name="category[]"]');

    categoryCheckboxes.forEach((checkbox) => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function clearFilters() {
    document.getElementById('selectAllCategories').checked = false;
    const categoryCheckboxes = document.querySelectorAll('input[name="category[]"]');
    categoryCheckboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });
    
    const stockOptions = document.querySelectorAll('input[name="stock_status[]"]');
    stockOptions.forEach((checkbox) => {
        checkbox.checked = false;
    });
}

window.onload = function() {
    const lastRestockDateInput = document.getElementById('last_restock_date');
    const today = new Date();

    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); 
    const day = String(today.getDate()).padStart(2, '0');

    lastRestockDateInput.value = `${year}-${month}-${day}`;
};

document.addEventListener("DOMContentLoaded", function() {
    const notification = document.querySelector('.notification');
    const errorMessage = document.querySelector('.error-message');
    const categorySelect = document.getElementById('category');
    const formFields = ['product_name', 'product_price', 'inv_qty', 'last_restock_date'].map(id => document.getElementById(id));
    
    function fadeOut(element) {
        setTimeout(() => {
            element.style.opacity = '0'; // Fade out by reducing opacity
        }, 3000);
        
        setTimeout(() => {
            element.style.display = 'none';
        }, 3500);
    }

    if (notification) fadeOut(notification);
    if (errorMessage) fadeOut(errorMessage);

    function toggleFormFields(enabled) {
        formFields.forEach(field => field.disabled = !enabled);
    }

    categorySelect.addEventListener('change', function() {
        toggleFormFields(this.value !== ""); 
    });
});

window.onclick = function(event) {
    if (event.target === addForm) {
        addForm.style.display = 'none';
    }
    if (event.target === updateForm) {
        updateForm.style.display = 'none';
    }
    if (event.target === deleteForm) {
        deleteForm.style.display = 'none';
    }
    if (event.target === viewForm) {
        viewForm.style.display = 'none';
    }
}