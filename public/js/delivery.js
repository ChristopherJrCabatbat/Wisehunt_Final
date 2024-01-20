document.addEventListener("DOMContentLoaded", function () {
    // Get references to the elements for the Product modal
    const nextButton = document.getElementById("nextButton");
    const backButton = document.getElementById("backButton");
    const productModal = document.getElementById("productModal");
    const closeModalButtons = document.querySelectorAll(".closeModal-delivery");
    const newModal = document.getElementById("newModal");

    // Attach click event handlers to close buttons inside productModal
    closeModalButtons.forEach(function (closeModalButton) {
        closeModalButton.addEventListener("click", function () {
            closeProductModal();
        });
    });

    // Close the productModal
    function closeProductModal() {
        const form = document.querySelector(".modal-form");
        // Loop through each form element except submit button and reset its value
        form.querySelectorAll(
            "input:not([type='submit']):not([type='button']), select, textarea"
        ).forEach((element) => {
            element.value = "";
        });

        // Clear validation messages
        const validationMessages = document.querySelectorAll(".text-danger");
        validationMessages.forEach((message) => {
            message.innerHTML = "";
        });

        productModal.style.display = "none";
    }

    // Attach click event handler for the Next button
    nextButton.addEventListener("click", function () {
        showProductModal();
        newModal.style.display = "none";
    });

    backButton.addEventListener("click", function () {
        productModal.style.display = "none";
        newModal.style.display = "block";

    })

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
});
