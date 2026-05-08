/**
 * Propietario deletion module
 */

/**
 * Deletes a propietario via AJAX
 * @param {number|string} id - The propietario ID
 * @param {string} token - CSRF token
 * @param {string} baseUrl - Application base URL
 */
export function deletePropietario(id, token, baseUrl) {
    Swal.fire({
        title: "¿Está seguro de borrar el registro?",
        text: "¡Una vez borrado no se puede recuperar!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: "Sí, elimínelo",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('_token', token);

            const url = baseUrl.endsWith('/') ? `${baseUrl}propietarios/eliminar` : `${baseUrl}/propietarios/eliminar`;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; }).catch(() => {
                            throw new Error('Error en la respuesta del servidor');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        sessionStorage.setItem('pendingToast', JSON.stringify({ type: 'success', message: data.message || 'Registro eliminado' }));
                        window.location.reload();
                    } else {
                        window.showToastError(data.message || 'No se pudo eliminar el registro');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const message = error.message || 'Ocurrió un error al intentar eliminar el registro';
                    window.showToastError(message);
                });
        }
    });
}

/**
 * Initializes delete listeners for propietarios
 * @param {string} baseUrl 
 */
export function initDeleteButtons(baseUrl) {
    document.addEventListener('click', function (event) {
        const btn = event.target.closest('.btn-eliminar');
        if (btn) {
            const id = btn.getAttribute('data-id');
            const token = btn.getAttribute('data-token');
            deletePropietario(id, token, baseUrl);
        }
    });
}
