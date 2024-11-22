import { Dropdown } from "bootstrap";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import Swal from "sweetalert2";


const accesoriosContainer = document.getElementById('accesorios-container');
const accesoriosDiv = document.getElementById('accesorios');
const Formulario = document.getElementById('formularioUsuario');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnModificar = document.getElementById('BtnModificar');

const InputClase = document.getElementById('eqp_clase');
const InputSerie = document.getElementById('eqp_serie');
const InputGama = document.getElementById('eqp_gama');
const InputMarca = document.getElementById('eqp_marca');
const InputEstado = document.getElementById('eqp_estado');


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

    const url = '/CECOM/API/mantenimientos/buscartodos';
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


const datatable = new DataTable('#TablaEquipos', {
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
                  <button class='btn btn-warning modificar mx-1' 
                    data-id="${data}"  
                    data-clase="${row.eqp_clase}"  
                    data-serie="${row.eqp_serie}"  
                    data-gama="${row.eqp_gama}"  
                    data-marca="${row.eqp_marca}"  
                    data-estado="${row.eqp_estado}"  
                    <i class="bi bi-pencil-square"></i> Modificar
                  </button>
                </div>`;
            }
        }
    ]
});

const llenarChechbox = async (e) => {
    const datos = e.currentTarget.dataset;

    // Llenar los campos principales con la información del equipo
    InputClase.value = datos.clase;
    InputSerie.value = datos.serie;
    InputGama.value = datos.eqp_gama;
    InputMarca.value = datos.marca;
    InputEstado.value = datos.estado;

    const tipo = datos.clase;
    const idEquipo = datos.id;

    accesoriosDiv.innerHTML = '';

    if (tipo && idEquipo) {
        try {
            const body = new FormData();
            body.append('tipo', tipo);
            body.append('idEquipo', idEquipo);

            const url = `/CECOM/API/equipo/tipo-accesorios`;
            const config = {
                method: 'POST',
                body,
            };

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();

            const accesoriosDisponibles = data.accesorios_disponibles || [];
            const accesoriosAsignados = data.accesorios_asignados || [];

            if (accesoriosDisponibles.length > 0) {
                accesoriosContainer.style.display = 'block';

                accesoriosDisponibles.forEach((accesorio) => {
                    // Crear elementos para cada accesorio disponible
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `accesorio-${accesorio.acc_id}`;
                    checkbox.name = 'asig_accesorio';
                    checkbox.value = accesorio.acc_id;

                    // Verificar si el accesorio está asignado y marcarlo
                    const accesorioAsignado = accesoriosAsignados.find(
                        (asignado) => asignado.asig_accesorio == accesorio.acc_id
                    );
                    if (accesorioAsignado) {
                        checkbox.checked = true;
                    }

                    // Escuchar cambios en el estado del checkbox
                    checkbox.addEventListener('change', async () => {
                        if (!checkbox.checked && accesorioAsignado) {
                            // Confirmación antes de proceder
                            let confirmacion = await Swal.fire({
                                title: '¿Está seguro que desea eliminar este accesorio al Equipo?',
                                text: "Esta acción no se puede deshacer.",
                                icon: 'warning',
                                showDenyButton: true,
                                showCancelButton: false,
                                confirmButtonText: 'Sí, eliminar',
                                denyButtonText: 'No, cancelar',
                                confirmButtonColor: '#3085d6',
                                denyButtonColor: '#d33',
                                background: '#fff3e0',
                                customClass: {
                                    title: 'custom-title-class',
                                    text: 'custom-text-class',
                                    confirmButton: 'custom-confirm-button',
                                    denyButton: 'custom-deny-button',
                                },
                            });
                    
                            if (confirmacion.isConfirmed) {
                                const eliminarBody = new FormData();
                                eliminarBody.append('idEquipo', idEquipo);
                                eliminarBody.append('idAccesorio', accesorio.acc_id);
                    
                                const eliminarUrl = `/CECOM/API/accesorios/eliminar`;
                                const eliminarConfig = {
                                    method: 'POST',
                                    body: eliminarBody,
                                };
                    
                                try {
                                    const eliminarRespuesta = await fetch(eliminarUrl, eliminarConfig);
                                    const eliminarResultado = await eliminarRespuesta.json();
                                    if (eliminarResultado.success) {
                                        await Swal.fire({
                                            title: 'Accesorio eliminado',
                                            text: `El accesorio ${accesorio.acc_id} fue eliminado correctamente.`,
                                            icon: 'success',
                                            confirmButtonText: 'Aceptar',
                                        });
                                    } else {
                                        console.error(`Error al desmarcar accesorio ${accesorio.acc_id}.`);
                                        await Swal.fire({
                                            title: 'Error',
                                            text: `No se pudo eliminar el accesorio ${accesorio.acc_id}.`,
                                            icon: 'error',
                                            confirmButtonText: 'Aceptar',
                                        });
                                    }
                                } catch (error) {
                                    console.error("Error en la solicitud para eliminar el accesorio:", error);
                                    await Swal.fire({
                                        title: 'Error',
                                        text: 'Ocurrió un problema al procesar la solicitud.',
                                        icon: 'error',
                                        confirmButtonText: 'Aceptar',
                                    });
                                }
                            } else {
                                // Si el usuario cancela, marcar nuevamente el checkbox
                                checkbox.checked = true;
                            }
                        }
                    });
                    
                    const label = document.createElement('label');
                    label.htmlFor = `accesorio-${accesorio.acc_id}`;
                    label.innerText = accesorio.acc_nombre;
                    label.classList.add('ms-1');

                    const cantidadInput = document.createElement('input');
                    cantidadInput.type = 'number';
                    cantidadInput.classList.add('form-control', 'form-control-sm', 'ms-2');
                    cantidadInput.name = `asig_cantidad`;
                    cantidadInput.id = `asig_cantidad_${accesorio.acc_id}`;
                    cantidadInput.placeholder = 'Cantidad';
                    cantidadInput.min = 1;
                    cantidadInput.style.width = '70px';

                    // Si el accesorio está asignado, rellenar la cantidad
                    if (accesorioAsignado) {
                        cantidadInput.value = accesorioAsignado.asig_cantidad;
                    }

                    const estadoSelect = document.createElement('select');
                    estadoSelect.classList.add('form-control', 'form-control-sm', 'ms-2');
                    estadoSelect.name = `asig_estado`;
                    estadoSelect.id = `asig_estado_${accesorio.acc_id}`;

                    const estados = [
                        { value: "", text: 'SELECCIONE' },
                        { value: 1, text: 'Buen Estado' },
                        { value: 2, text: 'Regular Estado' },
                        { value: 3, text: 'Mal Estado' },
                    ];

                    estados.forEach((estado) => {
                        const option = document.createElement('option');
                        option.value = estado.value;
                        option.innerText = estado.text;
                        estadoSelect.appendChild(option);
                    });

                    // Si el accesorio está asignado, seleccionar el estado
                    if (accesorioAsignado) {
                        estadoSelect.value = accesorioAsignado.asig_estado;
                    }

                    const div = document.createElement('div');
                    div.classList.add(
                        'col-12',
                        'col-sm-6',
                        'col-md-4',
                        'd-flex',
                        'align-items-center',
                        'mb-2',
                        'border',
                        'border-dark',
                        'p-2',
                        'rounded'
                    );
                    div.appendChild(checkbox);
                    div.appendChild(label);
                    div.appendChild(cantidadInput);
                    div.appendChild(estadoSelect);

                    accesoriosDiv.appendChild(div);
                });
            } else {
                accesoriosContainer.style.display = 'none';
            }
        } catch (error) {
            console.error("Error obteniendo los accesorios:", error);
        }
    } else {
        accesoriosContainer.style.display = 'none';
    }
};




const VerificarSerie = async () => {

    const ValorSerie = InputSerie.value.trim();

    if (ValorSerie === "") {

        InputSerie.classList.remove('is-valid', 'is-invalid');
        return;
    }

    const url = `/CECOM/API/verficar/serie?serie=${ValorSerie}`;
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const cantidadValor = datos.cantidad[0]?.cantidad || 0;

        if (cantidadValor > 0) {

            InputSerie.classList.remove('is-valid');
            InputSerie.classList.add('is-invalid');
            BtnModificar.disabled = true;

            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Este número de serie ya existe. Por favor, ingrese uno diferente.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                backdrop: true,
                showCloseButton: true,
            });
        } else {

            InputSerie.classList.add('is-valid');
            InputSerie.classList.remove('is-invalid');
            BtnModificar.disabled = false;
        }
    } catch (error) {
        console.error("Error en la verificación de serie:", error);
    }
};


let temporizador;

InputSerie.addEventListener('input', () => {
    clearTimeout(temporizador);
    temporizador = setTimeout(VerificarSerie, 100);
});
datatable.on('click', '.modificar', llenarChechbox);
Buscar();