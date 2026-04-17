/**
 * Taxi deletion module
 */

/**
 * Deletes a taxi via AJAX
 * @param {number|string} placa - The taxi Placa
 * @param {string} token - CSRF token
 * @param {string} baseUrl - Application base URL
 */
export function deleteTaxi(placa, token, baseUrl) {
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
            formData.append('placa', placa);
            formData.append('_token', token);

            const url = baseUrl.endsWith('/') ? `${baseUrl}taxis/eliminar` : `${baseUrl}/taxis/eliminar`;

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
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: data.message,
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar el registro', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const message = error.message || 'Ocurrió un error al intentar eliminar el registro';
                    Swal.fire('Error', message, 'error');
                });
        }
    });
}

/**
 * Initializes delete listeners for taxis
 * @param {string} baseUrl 
 */
export function initDeleteButtons(baseUrl) {
    document.addEventListener('click', function (event) {
        const btn = event.target.closest('.btn-eliminar');
        if (btn) {
            const placa = btn.getAttribute('data-placa');
            const token = btn.getAttribute('data-token');
            deleteTaxi(placa, token, baseUrl);
        }
    });
}
