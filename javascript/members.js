var addMember = document.getElementById('addMember');
var regForm = document.getElementById('regForm');

var closeRegForm = document.getElementById('closeRegForm');

addMember.onclick = function() {
    regForm.style.display = 'flex';
}

closeRegForm.onclick = function() {
    regForm.style.display = 'none';
}

document.getElementById("closeUpdateForm").onclick = function() {
    document.getElementById("updateForm").style.display = "none";
};

function capitalizeFirstLetter(event) {
    const inputField = event.target;
    const inputValue = inputField.value;

    // Capitalize first letter and keep the rest as is
    if (inputValue.length > 0) {
        inputField.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
    }
}

// Add event listeners to the input fields
document.addEventListener("DOMContentLoaded", function() {
    const textFields = document.querySelectorAll('#fname, #lname, #addy');
    textFields.forEach(function(field) {
        field.addEventListener('input', capitalizeFirstLetter);
    });
});


let originalValues = {};

function openUpdateForm(memberID, fname, lname, dob, gender, address, email, phone, regDate) {
    // Store original values when opening the form
    originalValues = { 
        memberID: memberID, 
        fname: fname, 
        lname: lname, 
        dob: dob, 
        gender: gender, 
        address: address, 
        email: email, 
        phone: phone, 
        regDate: regDate 
    };

    // Populate form fields with the current values
    document.getElementById('update_member_id').value = memberID;
    document.getElementById('update_fname').value = fname;
    document.getElementById('update_lname').value = lname;
    document.getElementById('update_dob').value = dob;
    document.getElementById('update_email').value = email;
    document.getElementById('update_ph').value = phone;
    document.getElementById('update_addy').value = address;
    document.getElementById('update_membership_start').value = regDate;

    // Set gender
    if (gender === 'male') {
        document.getElementById('update_male').checked = true;
    } else if (gender === 'female') {
        document.getElementById('update_female').checked = true;
    } else {
        document.getElementById('update_nonbinary').checked = true;
    }

    document.getElementById('updateForm').style.display = 'flex';
}

function resetForm() {
    // Reset form fields to their original values
    document.getElementById('update_member_id').value = originalValues.memberID;
    document.getElementById('update_fname').value = originalValues.fname;
    document.getElementById('update_lname').value = originalValues.lname;
    document.getElementById('update_dob').value = originalValues.dob;
    document.getElementById('update_email').value = originalValues.email;
    document.getElementById('update_ph').value = originalValues.phone;
    document.getElementById('update_addy').value = originalValues.address;
    document.getElementById('update_membership_start').value = originalValues.regDate;

    // Set gender
    if (originalValues.gender === 'male') {
        document.getElementById('update_male').checked = true;
    } else if (originalValues.gender === 'female') {
        document.getElementById('update_female').checked = true;
    } else {
        document.getElementById('update_nonbinary').checked = true;
    }
}

// Set the transaction date to the current date when the page loads
window.onload = function() {
    const membershipDate = document.getElementById('membership_start');
    const today = new Date();

    // Format the date as YYYY-MM-DD
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Month is 0-based, so add 1
    const day = String(today.getDate()).padStart(2, '0');

    membershipDate.value = `${year}-${month}-${day}`; // Set the date input value
};

document.addEventListener('DOMContentLoaded', function () {
    const regForm = document.getElementById('regForm');
    const addMember = document.getElementById('addMember');
    const closeRegForm = document.getElementById('closeRegForm');
    const updateForm = document.getElementById('updateForm');
    const membershipDate = document.getElementById('membership_start');
    const searchType = document.getElementById('searchType');
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.querySelector('.search-button');
    const tableRows = document.querySelectorAll('tbody tr');
    const membersData = {};
    const notification = document.querySelector('.notification');
    const errorMessages = document.querySelector('.error-messages');
    const errorUpMessages = document.querySelector('.uperror-messages');

    // Show and hide registration form
    addMember.onclick = function () {
        regForm.style.display = 'flex';
    };

    closeRegForm.onclick = function () {
        regForm.style.display = 'none';
    };

    document.getElementById("closeUpdateForm").onclick = function () {
        updateForm.style.display = "none";
    };

    // Capitalize first letter of text inputs
    const textFields = document.querySelectorAll('#fname, #lname, #addy');
    textFields.forEach(function (field) {
        field.addEventListener('input', function (event) {
            const inputField = event.target;
            const inputValue = inputField.value;
            if (inputValue.length > 0) {
                inputField.value = inputValue.charAt(0).toUpperCase() + inputValue.slice(1);
            }
        });
    });

    // Form validation
    regForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const fname = document.getElementById('fname').value.trim();
        const lname = document.getElementById('lname').value.trim();
        const dob = document.getElementById('dob').value.trim();
        const gender = document.querySelector('input[name="gender"]:checked');
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('ph').value.trim();
        const address = document.getElementById('addy').value.trim();
        let isValid = true;
        let messages = [];

        const nameRegex = /^[A-Za-z]{1,30}$/;
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^\d{12}$/;
        const addressRegex = /^[A-Za-z0-9\s,]*$/;

        // Validate first name
        if (!nameRegex.test(fname)) {
            messages.push("First name must contain only letters and be up to 30 characters.");
            isValid = false;
        }

        // Validate last name
        if (!nameRegex.test(lname)) {
            messages.push("Last name must contain only letters and be up to 30 characters.");
            isValid = false;
        }

        // Validate date of birth
        if (dateRegex.test(dob)) {
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;

            if (age < 20 || age > 65) {
                messages.push("You must be between the age of 20 and 65 to register.");
                isValid = false;
            }
        } else {
            messages.push("Please enter a valid date of birth.");
            isValid = false;
        }

        // Validate gender
        if (!gender) {
            messages.push("Please select a gender.");
            isValid = false;
        }

        // Validate email
        if (!emailRegex.test(email)) {
            messages.push("Please enter a valid email address.");
            isValid = false;
        }

        // Validate phone number
        if (!phoneRegex.test(phone)) {
            messages.push("Please enter a valid phone number (12 digits).");
            isValid = false;
        }

        // Validate address
        if (!addressRegex.test(address)) {
            messages.push("Address can only contain letters, numbers, spaces, and commas.");
            isValid = false;
        }

        if (isValid) {
            errorMessages.style.display = 'none';
            document.querySelector('#regForm form').submit(); // Ensure the form is submitted
        } else {
            errorMessages.innerHTML = messages.join("<br>");
            errorMessages.style.display = 'block';
            errorMessages.style.opacity = '1';
            fadeOut(errorMessages);
        }  
    });

    updateForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const updateEmail = document.getElementById('update_email').value.trim();
        const updatePhone = document.getElementById('update_ph').value.trim();
        const updateAddress = document.getElementById('update_addy').value.trim();
        let isValid = true;
        let upMessages = [];

        const updateEmailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const updatePhoneRegex = /^\d{12}$/;
        const updateAddressRegex = /^[A-Za-z0-9\s,]*$/;

        // Validate email
        if (!updateEmailRegex.test(updateEmail)) {
            upMessages.push("Please enter a valid email address.");
            isValid = false;
        }

        // Validate phone number
        if (!updatePhoneRegex.test(updatePhone)) {
            upMessages.push("Please enter a valid phone number (12 digits).");
            isValid = false;
        }

        // Validate address
        if (!updateAddressRegex.test(updateAddress)) {
            upMessages.push("Address can only contain letters, numbers, spaces, and commas.");
            isValid = false;
        }

        if (isValid) {
            errorUpMessages.style.display = 'none';
            document.querySelector('#updateForm form').submit(); // Ensure the form is submitted
        } else {
            errorUpMessages.innerHTML = upMessages.join("<br>");
            errorUpMessages.style.display = 'block';
            errorUpMessages.style.opacity = '1';
            fadeOut(errorUpMessages);
        }  
    });

    // Set the membership start date to today's date
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    membershipDate.value = `${year}-${month}-${day}`;

    function fadeOut(element) {
        setTimeout(() => {
            element.style.opacity = '0'; // Fade out by reducing opacity
        }, 3000);
        
        setTimeout(() => {
            element.style.display = 'none';
        }, 3500);
    }

    if (notification) fadeOut(notification);
    if (errorMessages) fadeOut(errorMessages);

    // Search member logic
    tableRows.forEach(row => {
        const memberID = row.cells[0].innerText.trim();
        const memberName = row.cells[1].innerText.trim();
        membersData[memberID] = { name: memberName, row: row };
    });

    function searchMember() {
        const searchTypeValue = searchType.value;
        const searchTerm = searchInput.value.trim().toLowerCase();

        if (!searchTypeValue) {
            alert('Please select a search type.');
            return;
        }
        if (!searchTerm) {
            alert('Please enter a search term.');
            return;
        }

        let found = false;
        tableRows.forEach(row => row.style.backgroundColor = '');
        tableRows.forEach(row => {
            const memberID = row.cells[0].innerText.toLowerCase();
            const memberName = row.cells[1].innerText.toLowerCase();
            if (
                (searchTypeValue === 'id' && memberID === searchTerm) ||
                (searchTypeValue === 'name' && memberName.includes(searchTerm))
            ) {
                found = true;
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                row.style.backgroundColor = '#FFE5CF';
                setTimeout(() => {
                    row.style.transition = 'background-color 0.5s ease-out';
                    row.style.backgroundColor = '';
                }, 2000);
            }
        });

        if (!found) {
            alert('Member not found.');
        }
    }

    searchButton.addEventListener('click', searchMember);
    searchInput.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            searchMember();
        }
    });
});

window.onclick = function(event) {
    if (event.target === regForm) {
        regForm.style.display = 'none';
    }
    if (event.target === updateForm) {
        updateForm.style.display = 'none';
    }
}