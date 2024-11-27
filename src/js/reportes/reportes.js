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


const NombreOficial = document.getElementById('Nombre_Oficial');
const GradoOficial = document.getElementById('Grado_Oficial');
const FotoOficial = document.getElementById('FotoOficial');
const InputClase = document.getElementById('eqp_clase1');
const InputSerie = document.getElementById('eqp_serie1');
const InputGama = document.getElementById('eqp_gama1');
const InputMarca = document.getElementById('eqp_marca1');
const InputEstado = document.getElementById('eqp_estado1'); 
const InputDestacamento = document.getElementById('eqp_ubicacion1');
const InputStatus = document.getElementById('eqp_status');
const FormularioInformacion = document.getElementById('InformacionEquipo');

const SelectDestacamento = document.getElementById('ubi_id');
const SelectDependencia = document.getElementById('asi_dependencia');

const modalElement = document.querySelector('#modalEquipo');

const Formulario = document.getElementById('FormularioBusqueda');
const BtnBuscar = document.getElementById('BtnBuscar');

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

    e.preventDefault();

    BtnBuscar.disabled = true

    try {
        const body = new FormData(Formulario);
        const url = '/CECOM/API/reportes/buscar';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        const { codigo, mensaje, data } = datos;

        if (codigo === 1) {

            Swal.close();

            datatable.clear().draw();

            if (data && Array.isArray(data) && data.length > 0) {

                datatable.rows.add(data).draw();

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

                Swal.fire({
                    title: '¡Error!',
                    text: 'No se encontraron registros en la Base de Datos',
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
    BtnBuscar.disabled = false;
    Formulario.reset();
};

const datatable = new DataTable('#EquiposEcontrados', {
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
        { title: 'Clase', data: 'clase' },
        { title: 'Marca', data: 'marca' },
        { title: 'Gama', data: 'gama' },
        { title: 'No. Serie', data: 'eqp_serie' },
        { title: 'Asignado a:', data: 'dependencia' },
        {
            title: 'Acciones',
            data: 'eqp_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                  <button class='btn btn-warning informacion mx-1' 
                    data-equipoId="${data}"   
                    data-equipoEstatus="${row.asi_status}"   
                    data-bs-toggle="modal" 
                    data-bs-target="#modalEquipo">
                    <i class="bi bi-eye-fill"></i> Información
                  </button>
                </div>`;
            }
        }
    ]
});

// Actualización del código

const llenarDatos = async (e) => {
    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se carga la información',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const idEquipo = e.currentTarget.dataset.equipoid;
    const Status = e.currentTarget.dataset.equipoestatus;

    try {
        const body = new FormData();
        body.append('idEquipo', idEquipo);
        body.append('Status', Status);

        const url = '/CECOM/API/informaciongeneral/buscar';
        const config = { 
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        const { codigo, mensaje, Accesorios, Informacion, Mantenimientos, Movimientos } = datos;

        if (codigo === 1) {

            TablaAccesorios.clear().draw();
            TablaMovimientos.clear().draw();
            TablaMantenimientos.clear().draw();

   
            if (Informacion) {

                GradoOficial.value = Informacion.grado || "";
                NombreOficial.value = Informacion.responsable || "";
                FotoOficial.innerHTML = Informacion.per_catalogo 
                    ? `<img src="https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${Informacion.per_catalogo}.jpg" class="rounded-circle shadow" style="width: 125px; height: 125px;">`
                    : "";
                InputClase.value = Informacion.clase || "Sin clase";
                InputSerie.value = Informacion.serie || "Sin serie";
                InputGama.value = Informacion.gama || "Sin gama";
                InputMarca.value = Informacion.marca || "Sin marca";
                InputEstado.value = Informacion.estado || "Sin estado"; 
                InputStatus.value = Informacion.estatus || "Sin estatus";
                InputDestacamento.value = Informacion.ubi_nombre || "No asignado";
            }


            if (Accesorios && Array.isArray(Accesorios)) {
                TablaAccesorios.rows.add(Accesorios).draw();
            }


            if (Movimientos && Array.isArray(Movimientos)) {
                TablaMovimientos.rows.add(Movimientos).draw();
            }


            if (Mantenimientos && Array.isArray(Mantenimientos)) {
                TablaMantenimientos.rows.add(Mantenimientos).draw();
            }

            Swal.close(); 
        } else {

            await Swal.fire({
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
        console.error("Error al llenar los datos:", error);
        Swal.fire({
            title: '¡Error!',
            text: 'Ocurrió un error al procesar los datos.',
            icon: 'error',
            showConfirmButton: true
        });
    }
};


const TablaAccesorios = new DataTable('#AccesoriosEquipo', {
    language: lenguaje,
    data: [],
    columns: [
        { title: 'No.', data: 'asig_equipo', render: (data, type, row, meta) => meta.row + 1 },
        { title: 'Nombre del Accesorio', data: 'acc_nombre' },
        { title: 'Estado', data: 'sit_descripcion' },
        { title: 'Cantidad', data: 'asig_cantidad' }
    ]
});

const TablaMovimientos = new DataTable('#MovimientosEquipo', {
    language: lenguaje,
    data: [],
    columns: [
        { title: 'No.', data: '', render: (data, type, row, meta) => meta.row + 1 },
        { title: 'Dependencia', data: 'dependencia' },
        { title: 'Fecha', data: 'asi_fecha' },
        { title: 'Motivo', data: 'asi_motivo' },
        { title: 'Destacamento', data: 'ubi_nombre' || 'No asignado' },
        { title: 'Estatus', data: 'sit_descripcion' }
    ]
});


const TablaMantenimientos = new DataTable('#ReparacionesEquipo', {
    language: lenguaje,
    data: [],
    columns: [
        { title: 'No.', data: 'eqp_id', render: (data, type, row, meta) => meta.row + 1 },
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


modalElement.addEventListener('hidden.bs.modal', () => {

    FormularioInformacion.reset();
    TablaAccesorios.clear().draw();
    TablaMovimientos.clear().draw();
    TablaMantenimientos.clear().draw();


    GradoOficial.value = '';
    NombreOficial.value = '';
    FotoOficial.innerHTML = '<i class="bi bi-person-fill text-muted" style="font-size: 50px;"></i>'; // Icono por defecto
    InputClase.value = '';
    InputSerie.value = '';
    InputGama.value = '';
    InputMarca.value = '';
    InputEstado.value = '';
    InputStatus.value = '';
    InputDestacamento.value = '';
});

const mostrarDestacamentos = async () => {
    const Dependencia = SelectDependencia.value.trim();

    try {
        const url = `/CECOM/API/reportes/buscarDestacamentos?dependencia=${Dependencia}`;
        const config = { method: 'GET' };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        SelectDestacamento.innerHTML = '<option value="">SELECCIONE...</option>';

        if (datos && datos.length > 0) {

            datos.forEach(destacamento => {
                const option = document.createElement('option');
                option.value = destacamento.ubi_id;
                option.textContent = destacamento.ubi_nombre;
                SelectDestacamento.appendChild(option);
            });
        } else {

            Swal.fire({
                title: '¡Error!',
                text: 'No se encontraron destacamentos para esta dependencia',
                icon: 'warning',
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
        console.error('Error al obtener los destacamentos:', error);

        Swal.fire({
            title: '¡Error!',
            text: 'Error al obtener los destacamentos',
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
};

// Añadir el evento para que la función se ejecute cuando el valor del select de dependencia cambie
SelectDependencia.addEventListener('change', mostrarDestacamentos);



datatable.on('click', '.informacion', llenarDatos);
SelectDependencia.addEventListener('change', mostrarDestacamentos)

Formulario.addEventListener('submit', Buscar);