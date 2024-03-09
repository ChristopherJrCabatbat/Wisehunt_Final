document.addEventListener("DOMContentLoaded", function () {
    // Get references to the elements for the Product modal
    const nextButton = document.getElementById("nextButton");
    const backButton = document.getElementById("backButton");
    const productModal = document.getElementById("productModal");
    const closeModalButtons = document.querySelectorAll(".closeModal-delivery");
    const newModal = document.getElementById("newModal");
    const addDeliveryForm = document.getElementById("addDeliveryForm");

    // Attach click event handlers to close buttons inside productModal
    closeModalButtons.forEach(function (closeModalButton) {
        closeModalButton.addEventListener("click", function () {
            closeProductModal();
        });
    });

    // Close the productModal
    function closeProductModal() {
        const productModal = document.getElementById("productModal"); // Ensure this ID matches your modal's ID

        // Do not reset the form values, only hide the modal
        productModal.style.display = "none";
    }
    // Attach click event handler for the Next button
    nextButton.addEventListener("click", function () {
        console.log("Next button clicked");
        if (!checkFormCompleteness()) {
            console.log("Fields not complete. Showing alert.");
            alert("Please fill in all fields before proceeding.");
        } else {
            // Validate the form before proceeding
            if (addDeliveryForm.reportValidity()) {
                console.log(
                    "Fields complete. Opening Product Selection Modal."
                );
                showProductModal(); // Show the productModal only if the form is complete
                newModal.style.display = "none";
            } else {
                console.log("Form validation failed. Keeping newModal open.");
            }
        }
    });

    // Handle form submission
    addDeliveryForm.addEventListener("submit", function (event) {
        if (!isAtLeastOneCheckboxChecked()) {
            event.preventDefault();
            alert("Please select at least one product before submitting.");
        }
    });

    backButton.addEventListener("click", function () {
        productModal.style.display = "none";
        newModal.style.display = "block";
    });

    // Show the productModal
    function showProductModal() {
        productModal.style.display = "block";
        // Autofocus
        setTimeout(() => {
            const autofocus = document.getElementById("autofocus");
            if (autofocus) {
                autofocus.focus();
            }
        }, 0);
    }

    // Define closeModal in the global scope
    window.closeModal = closeProductModal;

    // Function to check if at least one checkbox is checked
    function isAtLeastOneCheckboxChecked() {
        const checkboxes = document.querySelectorAll('input[name="product[]"]');
        return Array.from(checkboxes).some((checkbox) => checkbox.checked);
    }
});
