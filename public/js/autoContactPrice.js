        function updateContactNumber() {
            var customerSelect = document.getElementById('customer');
            var contactNumberInput = document.getElementById('contact_num');

            var selectedOption = customerSelect.options[customerSelect.selectedIndex];
            var contactNumber = selectedOption.getAttribute('data-contact');

            contactNumberInput.value = contactNumber;
        }

        function updateUnitPrice() {
            var productName = document.getElementById('product_name').value;
            var unitPriceField = document.getElementById('selling_price');

            // Find the product with the selected name in the products list
            var selectedProduct = @json($products);

            for (var i = 0; i < selectedProduct.length; i++) {
                if (selectedProduct[i].name === productName) {
                    unitPriceField.value = selectedProduct[i].purchase_price;
                    break;
                }
            }
        }