console.log("Script initialized.");

document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const selectedTable = urlParams.get("table");

  if (selectedTable) {
    const tableSelect = document.getElementById("table");
    tableSelect.value = selectedTable;
    updateCheckboxes();
  }

  init();
});

function init() {
  console.log("Form setup initialized."); // Test message
  const form = document.getElementById("reportForm");
  const startDate = document.getElementById("startDate");
  const endDate = document.getElementById("endDate");
  const checkboxesContainer = document.getElementById("checkboxes");

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    let isValid = validateForm(startDate, endDate, checkboxesContainer);
    if (isValid) {
      submitForm(form);
    }
  });
}

const tableColumns = {
  Members: [
    "First Name",
    "Last Name",
    "Date of Birth",
    "Gender",
    "Email",
    "Phone Number",
    "Address",
    "Registration Date",
  ],
  Products: [
    "Product Name",
    "Product Price",
    "Category",
    "Inventory Quantity",
    "Last Restock Date",
  ],
  Sales: [
    "Transaction ID",
    "Member ID",
    "Total Price",
    "Payment Method",
    "Date of Transaction",
  ],
};

function updateCheckboxes() {
  const table = document.getElementById("table").value;
  const checkboxesDiv = document.getElementById("checkboxes");
  checkboxesDiv.innerHTML = ""; // Clear existing checkboxes

  if (tableColumns[table]) {
    checkboxesDiv.style.display = "flex";
    
    tableColumns[table].forEach((column) => {
      // Create a wrapper for each checkbox item
      const checkboxItem = document.createElement("div");
      checkboxItem.classList.add("checkbox-item");

      const checkboxLabel = document.createElement("label");
      const checkbox = document.createElement("input");

      checkbox.type = "checkbox";
      checkbox.name = "columns[]";
      checkbox.value = column;

      checkboxLabel.appendChild(checkbox);
      checkboxLabel.appendChild(document.createTextNode(column));
      
      checkboxItem.appendChild(checkboxLabel); // Append label to the item
      checkboxesDiv.appendChild(checkboxItem); // Append item to the container
    });
  } else {
    checkboxesDiv.style.display = "none"; // Hide checkboxes container if no table selected
  }
}

// Form validation function
function validateForm(startDate, endDate, checkboxesContainer) {
  let isValid = true;

  // Date validation
  if (!startDate.value || !endDate.value) {
    showAlert("Please enter both start and end dates.");
    isValid = false;
  } else {
    const currentDate = new Date();
    const selectedStartDate = new Date(startDate.value);
    const selectedEndDate = new Date(endDate.value);

    // Check if end date is earlier than start date
    if (selectedEndDate < selectedStartDate) {
      showAlert("End Date cannot be earlier than Start Date.");
      isValid = false;
    }

    // Check if end date is later than current date
    if (selectedEndDate > currentDate) {
      showAlert("End Date must not be later than the current date.");
      isValid = false;
    }
  }

  // Checkbox validation
  const checkboxes = checkboxesContainer.querySelectorAll(
    "input[type='checkbox']"
  );
  const isColumnSelected = Array.from(checkboxes).some(
    (checkbox) => checkbox.checked
  );

  if (!isColumnSelected) {
    showAlert("Please select at least one column.");
    isValid = false;
  }

  return isValid;
}

function downloadCSV(data, filename = "report.csv") {
  const blob = new Blob([data], { type: "text/csv" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

function submitForm(form) {
  const formData = new FormData(form);
  fetch("php/export.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      // Check if the response is JSON or CSV
      const contentType = response.headers.get("Content-Type");

      // If it's JSON, handle it
      if (contentType && contentType.includes("application/json")) {
        return response.json();
      }

      // If it's CSV, handle it as text
      return response.text();
    })
    .then((data) => {
      const contentType = data && typeof data === "object" ? "json" : "csv";

      if (contentType === "json") {
        // Handle JSON response (error or info)
        if (data.status === "error") {
          showAlert(data.message);
        } else if (data.status === "info") {
          showAlert(data.message, "info");
        }
      } else if (contentType === "csv") {
        // Handle CSV response
        console.log("CSV Response:", data); // Optional: Log the CSV data
        downloadCSV(data); // Download CSV as a file
        showAlert("Report generated successfully.", "info");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showAlert("An error occurred. Please try again.");
    });
}

// Alert messages using SweetAlert2
function showAlert(message, icon = "warning") {
  Swal.fire({
    icon: icon,
    title: "Notice",
    text: message,
    confirmButtonText: "Okay",
  });
}