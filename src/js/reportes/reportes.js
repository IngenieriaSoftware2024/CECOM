import { Dropdown } from "bootstrap";
import DataTable from "datatables.net-bs5";
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";

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
    BtnBuscar.disabled = false;
    Formulario.reset();
};

const datatable = new DataTable('#EquiposEcontrados', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
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
        { title: 'Asignado a:', data: 'dependencia' }
    ]
});

Formulario.addEventListener('submit', Buscar)