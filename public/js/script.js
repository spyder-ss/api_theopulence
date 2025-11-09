document.addEventListener("DOMContentLoaded", function () {
    const logoutLinks = document.querySelectorAll(".logout-link");
    logoutLinks.forEach((link) => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            this.closest("form").submit();
        });
    });
});