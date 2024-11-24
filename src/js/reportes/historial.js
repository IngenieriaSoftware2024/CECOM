import { Dropdown } from "bootstrap";
import DataTable from "datatables.net-bs5";
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";


import pdfmake from 'pdfmake';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import jszip from 'jszip';
window.JSZip = require('jszip')



const Buscar = async (e) => {

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se cargan los datos',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });


    try {
        const url = '/CECOM/API/historial/buscar';

        const config = {
            method: 'GET'
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        console.log(datos)

        const { codigo, mensaje, data } = datos;

        if (codigo === 1) {

            Swal.close();

            datatable.clear().draw();

            if (data) {
                datatable.rows.add(data).draw();
            }

            await Swal.fire({
                title: '¡Éxito!',
                text: mensaje,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }
            });

        } else {
            Swal.close();
            
            Swal.fire({
                title: '¡Error!',
                text: mensaje,
                icon: 'error',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }
            });
        }
    } catch (error) {
        console.log(error);
    }
};


const datatable = new DataTable('#EquiposHistorial', {
    dom: `
        <"row mb-3"
            <"col-md-2 d-flex align-items-center"l>
            <"col-md-8 d-flex justify-content-center gap-2"B>
            <"col-md-2 d-flex justify-content-end"f>
        >
        t
        <"row mt-3"
            <"col-md-3 d-flex align-items-center"i>
            <"col-md-8 d-flex justify-content-end"p>
        >
    `,
    buttons: [
        {
            extend: 'colvis',
            text: 'Mostrar/Ocultar',
            titleAttr: "Hagá click sobre el nombre",
            className: 'btn-primary'
        },
        {
            extend: 'excelHtml5',
            className: 'btn-success btn-excel',
            titleAttr: "Descargar Excel",
            title: '',
            text: '<i class="bi bi-file-earmark-excel me-2"> Descargar</i>',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'print',
            className: 'btn-danger btn-imprimir',
            text: '<i class="bi bi-printer-fill me-2"> Imprimir</i>',
            titleAttr: "Imprimir PDF",
            title: '',
            exportOptions: {
                columns: ':visible'
            }
        }
    ],
    language: lenguaje,
    data: [],
    columns: [
        {
            title: 'No.',
            data: 'eqp_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Equipo', data: 'equipo_descripcion' },
        { title: 'Dependencia', data: 'dependencia' },
        { title: 'Fecha Ingreso', data: 'rep_fecha_entrada' },
        { title: 'Entrego a Almacen', data: 'entregado_nombre_completo' },
        { title: 'Fecha Salida', data: 'rep_fecha_salida' },
        { title: 'Entregado a', data: 'recibido_nombre_completo' },
        { title: 'Motivo Mantenimiento', data: 'rep_desc' },
        { title: 'Estado al ingresar', data: 'rep_estado_ant_desc' },
        { title: 'Estado al egresar', data: 'rep_estado_actual_desc' },
        { title: 'Responsable Mantenimiento', data: 'responsable_nombre_completo' },
        { title: 'Estatus', data: 'rep_status_desc' },
        { title: 'Observaciones', data: 'rep_obs' }
    ]
});

Buscar();