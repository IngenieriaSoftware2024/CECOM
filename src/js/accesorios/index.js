import { Dropdown, Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";
import { validarFormulario } from "../funciones";
import { data } from "jquery";



const modalElement = document.querySelector('#ModalAccesorios');
const Formulario = document.getElementById('FormAccesorios')
const modalBSMarcas = new Modal(modalElement);
const BtnCrear = document.getElementById('BtnCrear');
const BtnModificar = document.getElementById('BtnModificar');
const ModalTitle1 = document.getElementById('ModalTitle1');
const ModalTitle2 = document.getElementById('ModalTitle2');




BtnModificar.classList.add('d-none');

const Buscar = async () => {

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se cargan los datos',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const url = '/CECOM/API/accesorios/buscar';
    const config = {
        method: 'GET'
    };

    const respuesta = await fetch(url, config);
    const datos = await respuesta.json();

    const { codigo, mensaje, data } = datos

    if (codigo === 1) {

        datatable.clear().draw();

        if (data) {
            datatable.rows.add(data).draw();
        }
        Swal.close();
    } else {
        Swal.close();
        console.log(mensaje)
    }
};


const datatable = new DataTable('#TablaAccesorios', {
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
            data: 'acc_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Nombre', data: 'acc_nombre' },
        { title: 'Accesorio para...', data: 'car_nombre' },
        {
            title: 'Acciones',
            data: 'acc_id', 
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning modificar mx-1' 
                        data-id="${data}" 
                        data-nombre="${row.acc_nombre}" 
                        data-tipo="${row.acc_tipo}" 
                        data-bs-toggle="modal" 
                        data-bs-target="#ModalAccesorios">
                        <i class='bi bi-pencil-square'></i> Modificar
                    </button>
                </div>`;
            }
        }
    ]
});


const Guardar = async (e) => {
    e.preventDefault();

    BtnCrear.disabled = true

    if (!validarFormulario(Formulario, ['acc_id'])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnCrear.disabled = false
        return;
    }
    try {
        const body = new FormData(Formulario);
        const url = '/CECOM/API/accesorios/guardar';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje } = data;

        if (codigo === 2) {
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
    modalBSMarcas.hide();
    Formulario.reset();
    Buscar();
    BtnCrear.disabled = false

};

const llenarDatos = async (e) => {

    ModalTitle1.classList.add('d-none');
    ModalTitle2.classList.remove('d-none');

    const datos = e.currentTarget.dataset;

    document.getElementById('acc_id').value = datos.id
    document.getElementById('acc_nombre').value = datos.nombre;
    document.getElementById('acc_tipo').value = datos.tipo;

    BtnCrear.classList.add('d-none');
    BtnModificar.classList.remove('d-none');
};

modalElement.addEventListener('hidden.bs.modal', () => {
 
    Formulario.reset();

    BtnCrear.classList.remove('d-none');
    BtnModificar.classList.add('d-none');

    ModalTitle1.classList.remove('d-none');
    ModalTitle2.classList.add('d-none');
});

const Modificar = async (e) => {
    e.preventDefault();

    BtnModificar.disabled = true;

    if (!validarFormulario(Formulario, [])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnModificar.disabled = false;
        return;
    }

    let confirmacion = await Swal.fire({
        title: '¿Está seguro que la información es correcta?',
        text: "Desea guardar los cambios realizados.",
        icon: 'warning',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'Sí, modificar',
        denyButtonText: 'No, cancelar',
        confirmButtonColor: '#3085d6',
        denyButtonColor: '#d33',
        background: '#fff3e0',
        customClass: {
            title: 'custom-title-class',
            text: 'custom-text-class',
            confirmButton: 'custom-confirm-button',
            denyButton: 'custom-deny-button'
        }
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData(Formulario);
            const url = '/CECOM/API/accesorios/modificar';

            const config = {
                method: 'POST',
                body
            };

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            const { codigo, mensaje } = data;

            if (codigo === 3) {
               
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
            console.log(error);
        }
    }
    
    modalBSMarcas.hide();
    Formulario.reset();
    BtnModificar.disabled = false;
    Buscar();
};


Buscar();
Formulario.addEventListener('submit', Guardar);
BtnModificar.addEventListener('click', Modificar);
datatable.on('click', '.modificar', llenarDatos);
