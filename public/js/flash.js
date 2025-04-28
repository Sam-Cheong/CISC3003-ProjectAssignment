document.addEventListener("DOMContentLoaded", () => {
    const flash = document.querySelector(".form-message");
    if (!flash) return;

    // only auto‑dismiss green (success) messages
    if (flash.classList.contains("form-message-green")) {
        setTimeout(() => {
            flash.classList.add("hide");
        }, 2000); // 2 seconds
    }
});
