$(document).ready(function () {
    $('#tabla_audit_log').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'desc']],
        dom: "<'row align-items-center mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" +
            "<'row mb-2'<'col-12'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'Todos']
        ],
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-file-earmark-text me-1"></i> Exportar',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="bi bi-clipboard me-1"></i> Copiar',
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="bi bi-filetype-csv me-1"></i> CSV',
                        filename: 'audit_log_' + new Date().toISOString().slice(0, 10),
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                        title: 'Log de Auditoría',
                        filename: 'audit_log_' + new Date().toISOString().slice(0, 10),
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer me-1"></i> Imprimir',
                        title: 'Log de Auditoría',
                        messageTop: 'Generado el ' + new Date().toLocaleDateString('es-ES'),
                        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                    }
                ]
            },
            {
                extend: 'colvis',
                text: '<i class="bi bi-layout-three-columns me-1"></i> Columnas'
            }
        ],
        language: {
            sProcessing: 'Procesando...',
            sLengthMenu: 'Mostrar _MENU_ registros',
            sZeroRecords: 'No se encontraron resultados',
            sEmptyTable: 'No hay registros de auditoría',
            sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
            sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
            sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
            sSearch: 'Buscar:',
            sLoadingRecords: 'Cargando...',
            sInfoThousands: ',',
            oPaginate: {
                sFirst: 'Primero',
                sLast: 'Último',
                sNext: 'Siguiente',
                sPrevious: 'Anterior'
            },
            oAria: {
                sSortAscending: ': Activar para ordenar la columna de manera ascendente',
                sSortDescending: ': Activar para ordenar la columna de manera descendente'
            }
        },
        initComplete: function () {
            $(this.api().table().node()).css('visibility', 'visible');
        }
    });
});
