import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";

const claseSelect = document.getElementById('eqp_clase');
const NoSerie = document.getElementById('eqp_serie');
const accesoriosContainer = document.getElementById('accesorios-container');
const accesoriosDiv = document.getElementById('accesorios');
const BtnModificar = document.getElementById('BtnModificar');
const icon_chek = document.getElementById('icon-chek');
const icon_error = document.getElementById('icon-error');
const BtnGuardar = document.getElementById('BtnGuardar');
const Formulario = document.getElementById('formularioUsuario');
const BtnLimpiar = document.getElementById('BtnLimpiar');



BtnModificar.classList.add('d-none');

const llenarChechbox = async () => {
    const ValorSeleccionado = claseSelect.value;
    accesoriosDiv.innerHTML = '';

    if (ValorSeleccionado) {
        try {
            const url = `/CECOM/API/equipo/seleccionado?tipo=${ValorSeleccionado}`;
            const respuesta = await fetch(url);
            const accesorios = await respuesta.json();

            const accesoriosData = accesorios.accesorios || [];

            if (accesoriosData.length > 0) {
                accesoriosContainer.style.display = 'block';

                accesoriosData.forEach(accesorio => {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `accesorio-${accesorio.acc_id}`;
                    checkbox.name = 'asig_accesorio';
                    checkbox.value = accesorio.acc_id;

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

                    const estadoSelect = document.createElement('select');
                    estadoSelect.classList.add('form-control', 'form-control-sm', 'ms-2');
                    estadoSelect.name = `asig_estado`;
                    estadoSelect.id = `asig_estado_${accesorio.acc_id}`;

                    const estados = [
                        { value: "", text: 'SELECCIONE' },
                        { value: 1, text: 'Buen Estado' },
                        { value: 2, text: 'Regular Estado' },
                        { value: 3, text: 'Mal Estado' }
                    ];

                    estados.forEach(estado => {
                        const option = document.createElement('option');
                        option.value = estado.value;
                        option.innerText = estado.text;
                        estadoSelect.appendChild(option);
                    });

                    const div = document.createElement('div');
                    div.classList.add('col-12', 'col-sm-6', 'col-md-4', 'd-flex', 'align-items-center', 'mb-2', 'border', 'border-dark', 'p-2', 'rounded');
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

let temporizador;

const VerificarSerie = async () => {
    const ValorSerie = NoSerie.value.trim();

    if (ValorSerie === "") {
        icon_chek.style.display = 'none';
        icon_error.style.display = 'none';
        return;
    }

    const url = `/CECOM/API/verficar/serie?serie=${ValorSerie}`;
    try {
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        const cantidadValor = datos.cantidad[0]?.cantidad || 0;

        if (cantidadValor > 0) {
            icon_chek.style.display = 'none';
            icon_error.style.display = 'inline';
            BtnGuardar.disabled = true;
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
            icon_chek.style.display = 'inline';
            icon_error.style.display = 'none';
            BtnGuardar.disabled = false;
        }
    } catch (error) {
        console.error("Error en la verificación de serie:", error);
    }
};

NoSerie.addEventListener('input', () => {
    clearTimeout(temporizador);
    temporizador = setTimeout(VerificarSerie, 500);
});

const Guardar = async (e) => {
    e.preventDefault();

    BtnGuardar.disabled = true;
    const accesoriosValidos = [];

    // Recoger todos los campos de accesorios no seleccionados
    document.querySelectorAll('#accesorios input[type="checkbox"]').forEach((checkbox) => {
        const id = checkbox.value;
        const cantidadInput = document.getElementById(`asig_cantidad_${id}`);
        const estadoSelect = document.getElementById(`asig_estado_${id}`);

        if (!checkbox.checked) {  
      
            if (cantidadInput && estadoSelect) {
                accesoriosValidos.push(cantidadInput.id, estadoSelect.id);
            }
        }
    });

    if (!validarFormulario(Formulario, ['eqp_id', 'eqp_serie', ...accesoriosValidos])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnGuardar.disabled = false;
        return;
    }

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se registra el equipo',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const accesoriosArray = [];
    document.querySelectorAll('#accesorios input[type="checkbox"]:checked').forEach((checkbox) => {
        const id = checkbox.value;
        const cantidadInput = document.getElementById(`asig_cantidad_${id}`);
        const estadoSelect = document.getElementById(`asig_estado_${id}`);

        if (cantidadInput && estadoSelect) {
            accesoriosArray.push({
                id,
                cantidad: cantidadInput.value,
                estado: estadoSelect.value
            });
        }
    });

    const payload = {
        eqp_id: document.getElementById("eqp_id").value,
        eqp_clase: claseSelect.value,
        eqp_serie: NoSerie.value,
        eqp_gama: document.getElementById("eqp_gama").value,
        eqp_marca: document.getElementById("eqp_marca").value,
        eqp_estado: document.getElementById("eqp_estado").value,
        accesorios: accesoriosArray
    };


    try {
        const response = await fetch('/CECOM/API/equipo/guardar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        const { codigo, mensaje } = data;
        
        Swal.close();

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
            Formulario.reset();
            icon_chek.style.display = 'none';
            icon_error.style.display = 'none';
            accesoriosDiv.innerHTML = ''; 
            accesoriosContainer.style.display = 'none'; 

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
        Swal.close();
        console.log("Error al enviar los datos:", error);
    }

    BtnGuardar.disabled = false;
};

const LimpiarFormulario = () =>{

    Formulario.reset();
    icon_chek.style.display = 'none';
    icon_error.style.display = 'none';
    accesoriosDiv.innerHTML = ''; 
    accesoriosContainer.style.display = 'none'; 
};

Formulario.addEventListener('submit', Guardar);
claseSelect.addEventListener('change', llenarChechbox);
BtnLimpiar.addEventListener('click', LimpiarFormulario)
