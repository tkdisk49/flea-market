const transactionId = window.transactionId ?? null;
const textarea = document.getElementById("chat-input");
const storageKey = transactionId ? `chat_input_${transactionId}` : "chat_input";

if (textarea) {
    if (localStorage.getItem(storageKey)) {
        textarea.value = localStorage.getItem(storageKey);
    }

    textarea.addEventListener("input", () => {
        localStorage.setItem(storageKey, textarea.value);
    });

    if (textarea.form) {
        textarea.form.addEventListener("submit", () => {
            localStorage.removeItem(storageKey);
        });
    }
}
