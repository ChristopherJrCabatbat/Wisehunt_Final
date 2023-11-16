const addBtn = document.getElementById("addBtn");
const myModal = document.getElementById("myModal");
const closeBtn = document.getElementsByClassName("close")[0];

addBtn.addEventListener("click", function () {
    myModal.style.display = "block";
});

closeBtn.addEventListener("click", function () {
    const form = document.querySelector(".modal-form");

    // Reset the form
    form.reset();
    
    myModal.style.display = "none";
});

addBtn.addEventListener("click", function () {
    myModal.style.display = "block";

     // Autofocus
     setTimeout(() => {
        const autofocus = document.getElementById('autofocus');
        if (autofocus) {
            autofocus.focus();
        }
    }, 0);
});
