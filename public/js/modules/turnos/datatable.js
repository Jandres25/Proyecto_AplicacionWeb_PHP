$(document).ready(function () {
    var table = $('#tabla_turnos').DataTable({
        responsive: true,
        autoWidth: false,
        dom: "<'row align-items-center mb-2'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" +
            "<'row mb-2'<'col-12'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        pageLength: 5,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'Todos']
        ],
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-file-earmark-text me-1"></i> Reportes',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="bi bi-clipboard me-1"></i> Copiar',
                        exportOptions: { columns: [0, 1, 2, 3, 4] }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
                        title: 'Gestión de Turnos',
                        filename: 'turnos_' + new Date().toISOString().slice(0, 10),
                        pageSize: 'LETTER',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4],
                            format: {
                                body: function (data, row, column, node) {
                                    return $(node).text().trim() || data;
                                }
                            }
                        },
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 11;
                            doc.styles.tableHeader.fillColor = '#4b545c';
                            doc.styles.tableHeader.color = '#ffffff';
                            doc.content.splice(0, 1, {
                                text: 'GESTIÓN DE TURNOS',
                                style: { fontSize: 16, alignment: 'center', bold: true, margin: [0, 10, 0, 10] }
                            });
                            doc.content.splice(1, 0, {
                                text: 'Listado de turnos registrados en el sistema',
                                style: { fontSize: 11, alignment: 'center', italics: true, margin: [0, 0, 0, 10] }
                            });
                            doc.content.splice(2, 0, {
                                text: 'Generado el: ' + new Date().toLocaleString('es-ES'),
                                style: { fontSize: 9, alignment: 'right', margin: [0, 0, 0, 10] }
                            });
                            doc.footer = function (currentPage, pageCount) {
                                return {
                                    columns: [
                                        { text: 'Sistema de Gestión', alignment: 'left', fontSize: 8 },
                                        { text: 'Página ' + currentPage + ' de ' + pageCount, alignment: 'center', fontSize: 8 },
                                        { text: 'Confidencial', alignment: 'right', fontSize: 8 }
                                    ],
                                    margin: [40, 0]
                                };
                            };
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                        title: 'Gestión de Turnos',
                        messageTop: 'Listado de turnos registrados',
                        messageBottom: 'Generado el ' + new Date().toLocaleDateString('es-ES'),
                        filename: 'turnos_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4],
                            format: {
                                body: function (data, row, column, node) {
                                    return $(node).text().trim() || data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="bi bi-filetype-csv me-1"></i> CSV',
                        filename: 'turnos_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4],
                            format: {
                                body: function (data, row, column, node) {
                                    return $(node).text().trim() || data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="bi bi-printer me-1"></i> Imprimir',
                        title: 'Gestión de Turnos',
                        messageTop: 'Reporte generado el ' + new Date().toLocaleDateString('es-ES'),
                        exportOptions: { columns: [0, 1, 2, 3, 4] },
                        customize: function (win) {
                            $(win.document.body).find('table').addClass('table-striped').css('font-size', '12px');
                        }
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
            sEmptyTable: 'Ningún dato disponible en esta tabla',
            sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ turnos',
            sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 turnos',
            sInfoFiltered: '(filtrado de un total de _MAX_ turnos)',
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
