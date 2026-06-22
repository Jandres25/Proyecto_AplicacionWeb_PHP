export function deleteTurno(id, nombre, token, baseUrl) {
    Swal.fire({
        title: '¿Eliminar turno?',
        text: 'Se eliminará el turno de ' + nombre + '. Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar turno',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('_token', token);

            const url = baseUrl.endsWith('/') ? `${baseUrl}turnos/eliminar` : `${baseUrl}/turnos/eliminar`;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
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
                        sessionStorage.setItem('pendingToast', JSON.stringify({ type: 'success', message: data.message || 'Turno eliminado exitosamente' }));
                        window.location.reload();
                    } else {
                        window.showToastError(data.message || 'No se pudo eliminar el turno');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showToastError(error.message || 'Ocurrió un error al intentar eliminar el turno');
                });
        }
    });
}

export function initDeleteButtons(baseUrl) {
    document.addEventListener('click', function (event) {
        const btn = event.target.closest('.btn-eliminar');
        if (btn) {
            const id = btn.getAttribute('data-id');
            const nombre = btn.getAttribute('data-nombre');
            const token = btn.getAttribute('data-token');
            deleteTurno(id, nombre, token, baseUrl);
        }
    });
}
