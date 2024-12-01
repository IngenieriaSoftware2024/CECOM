import { Dropdown, Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";

const modalElement = document.getElementById('ModalMantenimiento');
const Formulario = document.getElementById('FormMantenimiento')
const modalBSMantenimiento = new Modal(modalElement)


const InputRecibe = document.getElementById('rep_recibido');
const InputEntrega = document.getElementById('rep_entrego');
const InputResponsable = document.getElementById('rep_responsable');

const EstadoAcutal = document.getElementById('rep_estado_actual');
const EstadoAnterior = document.getElementById('rep_estado_ant');
const BtnEntregarEquipo = document.getElementById('BtnEntregar');
const InputObs = document.getElementById('rep_obs');
const InputMotivo = document.getElementById('rep_desc');
const Rep_id = document.getElementById('rep_id');
const IdEquipoSeleccionado = document.getElementById('rep_equipo');
const FechaEntrada = document.getElementById('rep_fecha_entrada');
const BtnGuardar = document.getElementById('BtnCrear');



const FormularioEntregar = () => {

    InputEntrega.disabled = false;
    InputRecibe.disabled = true;
    InputResponsable.disabled = false;
    EstadoAnterior.disabled = false;
    EstadoAcutal.disabled = true;
    InputObs.disabled = true;

    BtnEntregarEquipo.parentElement.classList.add('d-none');
    BtnGuardar.parentElement.classList.remove('d-none');

};

const FormularioSalida = () => {

    InputEntrega.disabled = true;
    InputRecibe.disabled = false;
    InputResponsable.disabled = true;
    EstadoAnterior.disabled = true;
    EstadoAcutal.disabled = false;
    InputObs.disabled = false;

    BtnEntregarEquipo.parentElement.classList.remove('d-none');
    BtnGuardar.parentElement.classList.add('d-none');

};

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

    const url = '/CECOM/API/mantenimientos/buscar';
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

const BuscarEntrega = async () => {

    const url = '/CECOM/API/mantenimientos/BuscarEntrega';
    const config = {
        method: 'GET'
    };

    const respuesta = await fetch(url, config);
    const datos = await respuesta.json();

    const { codigo, mensaje, data } = datos

    if (codigo === 1) {

        datatable2.clear().draw();

        if (data) {

            datatable2.rows.add(data).draw();
        }
    } else {
        console.log(mensaje)
    }
};


const datatable = new DataTable('#EquiposEnviados', {
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
            data: '',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Clase', data: 'clase' },
        { title: 'Marca', data: 'marca' },
        { title: 'Gama', data: 'gama' },
        { title: 'No. Serie', data: 'serie' },
        { title: 'Enviado por:', data: 'dependencia' },
        {
            title: 'Acciones',
            data: 'eqp_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                  <button class='btn btn-success recibido mx-1' 
                    data-equipoId="${data}"  
                    data-asiId="${row.asi_id}"  
                    data-bs-toggle="modal" 
                    data-bs-target="#ModalMantenimiento">
                    <i class="bi bi-check-circle"></i> Recibir
                  </button>
                </div>`;
            }
        }
    ]
});


const datatable2 = new DataTable('#EquiposEntregar', {
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
            data: '',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Clase', data: 'clase' },
        { title: 'Marca', data: 'marca' },
        { title: 'Gama', data: 'gama' },
        { title: 'No. Serie', data: 'serie' },
        { title: 'Enviado por:', data: 'dependencia' },
        {
            title: 'Acciones',
            data: 'eqp_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                  <button class='btn btn-warning entregar mx-1' 
                    data-equipoId="${data}"  
                    data-asiId="${row.asi_id}"  
                    data-bs-toggle="modal" 
                    data-bs-target="#ModalMantenimiento">
                    <i class="bi bi-arrow-right"></i>
                        Entregar
                  </button>
                </div>`;
            }
        }
    ]
});



const Guardar = async (e) => {
    e.preventDefault();

    BtnGuardar.disabled = true

    if (!validarFormulario(Formulario, ['rep_id', 'rep_recibido', 'rep_estado_actual', 'rep_obs', 'rep_fecha_entrada'])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnGuardar.disabled = false
        return;
    }
    try {
        const body = new FormData(Formulario);
        const url = '/CECOM/API/mantenimiento/guardar';

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();
        const { codigo, mensaje } = data;

        if (codigo === 1) {
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
    modalBSMantenimiento.hide();
    Formulario.reset();
    Buscar();
    BuscarEntrega();
    BtnGuardar.disabled = false

};


const ValidarCatalogoEntrega = async () => {

    const Catalogo = InputEntrega.value.trim();

    if (Catalogo === "") {

        InputEntrega.classList.remove('is-valid', 'is-invalid');
        return;
    }

    try {

        const url = `/CECOM/API/mantenimiento/validarcatalogo?catalogo=${Catalogo}`;
        const config = {
            method: 'GET'
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.data == true) {

            InputEntrega.classList.add('is-valid');
            InputEntrega.classList.remove('is-invalid');


        } else {

            InputEntrega.classList.add('is-invalid');
            InputEntrega.classList.remove('is-valid');

            Swal.fire({
                title: '¡Error!',
                text: 'El usuario no existe en la Base de Datos',
                icon: 'error',
                showConfirmButton: true,
                timer: null,
                timerProgressBar: false,
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

const ValidarCatalogoResponsable = async () => {

    const Catalogo = InputResponsable.value.trim();

    if (Catalogo === "") {

        InputResponsable.classList.remove('is-valid', 'is-invalid');
        return;
    }

    try {

        const url = `/CECOM/API/mantenimiento/validarcatalogo?catalogo=${Catalogo}`;
        const config = {
            method: 'GET'
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos.data == true) {

            InputResponsable.classList.add('is-valid');
            InputResponsable.classList.remove('is-invalid');


        } else {

            InputResponsable.classList.add('is-invalid');
            InputResponsable.classList.remove('is-valid');

            Swal.fire({
                title: '¡Error!',
                text: 'El usuario no existe en la Base de Datos',
                icon: 'error',
                showConfirmButton: true,
                timer: null,
                timerProgressBar: false,
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

const ValidarCatalogoRecibe = async () => {

    const Catalogo = InputRecibe.value.trim();

    if (Catalogo === "") {

        InputRecibe.classList.remove('is-valid', 'is-invalid');
        return;
    }

    try {

        const url = `/CECOM/API/mantenimiento/validarcatalogo?catalogo=${Catalogo}`;
        const config = {
            method: 'GET'
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();


        if (datos.data == true) {

            InputRecibe.classList.add('is-valid');
            InputRecibe.classList.remove('is-invalid');


        } else {

            InputRecibe.classList.add('is-invalid');
            InputRecibe.classList.remove('is-valid');

            Swal.fire({
                title: '¡Error!',
                text: 'El usuario no existe en la Base de Datos',
                icon: 'error',
                showConfirmButton: true,
                timer: null,
                timerProgressBar: false,
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

modalElement.addEventListener('hidden.bs.modal', () => {

    Formulario.reset();

    const inputs = Formulario.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    FormularioEntregar();
});

const llenarDatos = async (e) => {

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se cargan los datos',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const id = e.value

    FormularioSalida();

    const url = `/CECOM/API/mantenimientos/datosEquipo?equipo=${id}`;
    const config = {
        method: 'GET'
    };

    try {

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const data = datos.data;

        if (data) {

            Rep_id.value = data.rep_id;
            FechaEntrada.value = data.rep_fecha_entrada;
            InputEntrega.value = data.rep_entrego;
            InputMotivo.value = data.rep_desc;
            EstadoAnterior.value = data.rep_estado_ant;
            InputResponsable.value = data.rep_responsable;
            Swal.close();
        } else {
            Swal.close();

            Swal.fire({
                title: "Datos no encontrados",
                text: "Se puede obtener informacion de este equipo",
                icon: "info"
            });
        }
    } catch (error) {
        Swal.close();
        Swal.fire({
            title: "Error",
            text: error.message,
            icon: "error"
        });
    }


};



const Entregar = async (e) => {

    e.preventDefault();

    BtnEntregarEquipo.disabled = true

    if (!validarFormulario(Formulario, ['rep_obs'])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnEntregarEquipo.disabled = false
        return;
    }
    try {

        InputEntrega.disabled = false;
        InputRecibe.disabled = false;
        InputResponsable.disabled = false;
        EstadoAnterior.disabled = false;
        EstadoAcutal.disabled = false;
        InputObs.disabled = false;

        const body = new FormData(Formulario);
        const url = '/CECOM/API/mantenimiento/Entregar'; 

        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const data = await respuesta.json();

        const { codigo, mensaje } = data;

        if (codigo === 1) {
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
    modalBSMantenimiento.hide();
    Formulario.reset();
    Buscar();
    BuscarEntrega();
    BtnEntregarEquipo.disabled = false

};




let temporizador;

InputEntrega.addEventListener('input', function () {
    clearTimeout(temporizador);
    temporizador = setTimeout(ValidarCatalogoEntrega, 1000);
})

InputResponsable.addEventListener('input', function () {
    clearTimeout(temporizador);
    temporizador = setTimeout(ValidarCatalogoResponsable, 1000);
})

InputRecibe.addEventListener('input', function () {
    clearTimeout(temporizador);
    temporizador = setTimeout(ValidarCatalogoRecibe, 1000);
})


datatable.on('click', '.recibido', function (e) {
    const data = e.currentTarget.dataset;
    IdEquipoSeleccionado.value = data.equipoid;
    document.getElementById('asi_id').value = data.asiid;
});

datatable2.on('click', '.entregar', function (e) {
    const data = e.currentTarget.dataset;
    IdEquipoSeleccionado.value = data.equipoid;
    document.getElementById('asi_id').value = data.asiid;

    llenarDatos(IdEquipoSeleccionado);
});


Buscar();
FormularioEntregar();
BuscarEntrega();
Formulario.addEventListener('submit', Guardar);
BtnEntregarEquipo.addEventListener('click', Entregar);
