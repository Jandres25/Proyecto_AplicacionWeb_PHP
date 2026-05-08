(() => {
    const defaultConfig = {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    };

    const createToast = (config = {}) => Swal.mixin({ ...defaultConfig, ...config });

    window.Toast = createToast();
    window.showToast = (type, title, options = {}) =>
        window.Toast.fire({ icon: type, title, ...options });
    window.showToastSuccess = (title, options = {}) =>
        window.showToast('success', title, options);
    window.showToastError = (title, options = {}) =>
        window.showToast('error', title, options);
    const pending = sessionStorage.getItem('pendingToast');
    if (pending) {
        sessionStorage.removeItem('pendingToast');
        try {
            const { type, message } = JSON.parse(pending);
            window.showToast(type, message);
        } catch (_) {}
    }
})();
