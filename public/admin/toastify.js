function toastifySuccess(message) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
            padding: "var(--Radius-radius-card-sm, 16px)",
            borderRadius: "var(--Radius-radius-12, 12px)",
            border: "1px solid var(--Success-300, #6CE9A6)",
            background: "var(--Success-25, #F6FEF9)",
            color: "#027A48",
            boxShadow:"unset",
        },
        onClick: function() {} // Callback after click
    }).showToast();
}

function toastifyError(message) {
    Toastify({
        text: message,
        duration: 3000,
        escapeMarkup: false,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
            padding: "var(--Radius-radius-card-sm, 16px)",
            borderRadius: "var(--Radius-radius-12, 12px)",
            border: "1px solid #f5c2c7",
            background: "#f8d7da",
            color: "#842029",
            boxShadow:"unset",
        },
        onClick: function() {} // Callback after click
    }).showToast();
}