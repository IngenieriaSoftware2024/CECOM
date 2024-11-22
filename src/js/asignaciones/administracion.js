import { Dropdown } from "bootstrap";
import DataTable from "datatables.net-bs5";
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";


const SelectDestino = document.getElementById('asi_dependencia');
const InputCatalogo = document.getElementById('catalogo_oficial');
const NombreOficial = document.getElementById('Nombre_Oficial')
const GradoOficial = document.getElementById('Grado_Oficial');
const FotoOficial = document.getElementById('FotoOficial');
const PlazaOficial = document.getElementById('Plaza_Oficial');
const TextAreaMotivo = document.getElementById('MotivoCambio');
const icon_chek = document.getElementById('icon-check');
const icon_error = document.getElementById('icon-error');


const BtnGuardar = document.getElementById('BtnGuardar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

BtnGuardar.disabled = true;

const MostrarEquipos = async () => {

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se cargan los equipos',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const url = '/CECOM/API/mostrarequipos/buscar';
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

const datatable = new DataTable('#EquiposRegistrados', {
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
            title: '<input type="checkbox" title="Seleccionar todo" id="seleccionar_todo">All',
            data: 'asi_id',
            render: (data, type, row) => {
              
                const esDeshabilitado = row.estatus === "PENDIENTE DE RECIBIDO" || row.estatus === "MANTENIMIENTO";
                return `<input type="checkbox" class="fila-seleccionada" data-asiID="${data}" data-idEquipo="${row.eqp_id}" ${esDeshabilitado ? 'disabled' : ''}>`;
            },
            orderable: false,
            searchable: false
        },
        {
            title: 'No.',
            data: 'eqp_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Clase', data: 'clase' },
        { title: 'Gama', data: 'gama' },
        { title: 'Marca', data: 'marca' },
        { title: 'No. Serie', data: 'serie' },
        { title: 'Responsable', data: 'responsable' },
        { title: 'Estatus', data: 'estatus' },
        { title: 'Ubicado en', data: 'ubi_nombre' }
    ]
});

document.getElementById('seleccionar_todo').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('.fila-seleccionada');
    checkboxes.forEach(checkbox => {

        if (checkbox.disabled) {
            return;
        }
        checkbox.checked = this.checked;
        alternarSeleccion(checkbox);
    });
    verificarCondiciones();
});


document.querySelector('#EquiposRegistrados tbody').addEventListener('change', e => {
    if (e.target && e.target.classList.contains('fila-seleccionada')) {

        if (!e.target.disabled) {
            alternarSeleccion(e.target);
        }
    }
    verificarCondiciones();
});


const filasSeleccionadas = [];


const alternarSeleccion = checkbox => {
    const eqp_id = checkbox.getAttribute('data-idEquipo');
    const asi_id = checkbox.getAttribute('data-asiID');

    const equipo = { eqp_id, asi_id };


    if (checkbox.checked) {
        if (!filasSeleccionadas.some(e => e.eqp_id === eqp_id && e.asi_id === asi_id)) {
            filasSeleccionadas.push(equipo);
        }
    } else {

        const index = filasSeleccionadas.findIndex(e => e.eqp_id === eqp_id && e.asi_id === asi_id);
        if (index > -1) {
            filasSeleccionadas.splice(index, 1);
        }
    }
    verificarCondiciones();
};



let valorPrevioDestino = SelectDestino.value;

const VerificarSelect = async () => {
    if (SelectDestino.value === 'mantenimiento') {
        let confirmacion = await Swal.fire({
            title: '¿Está seguro de que desea enviar este equipo a Mantenimiento a la Brigada de Comunicaciones, Zona 13?',
            text: "Esta acción es irreversible.",
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Sí, Enviar',
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
                const url = '/CECOM/API/datosusuario/buscar';
                const config = { method: 'GET' };

                const respuesta = await fetch(url, config);
                const datos = await respuesta.json();
                // console.log(datos);

                if (datos.length > 0) {
                    icon_chek.style.display = 'inline';
                    icon_error.style.display = 'none';
                    GradoOficial.value = datos[0].grado_arma;
                    NombreOficial.value = datos[0].nombre_completo;
                    InputCatalogo.value = datos[0].per_catalogo;
                    InputCatalogo.disabled = true;
                    FotoOficial.innerHTML = `<img src="https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${datos[0].per_catalogo}.jpg" class="rounded-circle shadow" style="width: 125px; height: 125px;">`;
                    FotoOficial.style.backgroundColor = '';
                    PlazaOficial.value = datos[0].per_plaza;
                    InputCatalogo.classList.remove('border-danger');
                    verificarCondiciones();
                }
            } catch (error) {
                console.log(error);
            }
        } else {
            SelectDestino.value = '';
        }
    } else {

        if (valorPrevioDestino === 'mantenimiento') {
            HabilitarFormulario();
        }

        verificarCondiciones();
    }


    valorPrevioDestino = SelectDestino.value;
};


const HabilitarFormulario = () => {

    GradoOficial.value = "";
    NombreOficial.value = "";
    FotoOficial.innerHTML = `<i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>`;
    FotoOficial.style.backgroundColor = '#f0f0f0';
    PlazaOficial.value = "";
    InputCatalogo.classList.add('border-danger')
    InputCatalogo.value = "";
    InputCatalogo.disabled = false;
    icon_chek.style.display = 'none';
    icon_error.style.display = 'none';
    verificarCondiciones();
};

const llenarFormulario = async () => {
    const per_catalogo = InputCatalogo.value.trim();

    try {
        const body = new FormData();
        body.append('per_catalogo', per_catalogo);

        const url = `/CECOM/API/fotografia_ofical`;
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos && datos.length > 0) {
      
            icon_chek.style.display = 'inline';
            icon_error.style.display = 'none';

            GradoOficial.value = datos[0].grado_arma;
            NombreOficial.value = datos[0].nombre_completo;
            FotoOficial.innerHTML = `<img src="https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${per_catalogo}.jpg" class="rounded-circle shadow" style="width: 125px; height: 125px;">`;
            FotoOficial.style.backgroundColor = '';
            PlazaOficial.value = datos[0].per_plaza;
            InputCatalogo.classList.remove('border-danger');
            return true; 
        } else {
   
            icon_chek.style.display = 'none';
            icon_error.style.display = 'inline';

            Swal.fire({
                title: '¡Error!',
                text: 'El usuario no existe o no está asignado a esta Dependencia',
                icon: 'error',
                showConfirmButton: true,
                background: '#e0f7fa',
                customClass: {
                    title: 'custom-title-class',
                    text: 'custom-text-class'
                }
            });
            return false; 
        }
    } catch (error) {
        console.log(error);
        return false; 
    }
};

InputCatalogo.addEventListener('input', async () => {

    const valorCatalogo = InputCatalogo.value.trim();

    if (valorCatalogo.length === 6) {

        const exito = await llenarFormulario(); 
        if (exito) {
            BtnGuardar.disabled = false; 
        } else {
            BtnGuardar.disabled = true; 
        }
    } else {
        BtnGuardar.disabled = true; 
       
        GradoOficial.value = "";
        NombreOficial.value = "";
        FotoOficial.innerHTML = `<i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>`;
        FotoOficial.style.backgroundColor = '#f0f0f0';
        PlazaOficial.value = "";
        InputCatalogo.classList.add('border-danger');
        icon_chek.style.display = 'none';
        icon_error.style.display = 'none';
    }

    verificarCondiciones(); 
});



const verificarCondiciones = () => {

    const catalogoValido = InputCatalogo.value.length === 6;
    const equiposSeleccionados = filasSeleccionadas.length > 0;
    const DestinoSeleccionado = SelectDestino.value !== "" && SelectDestino.value !== "0";
    const plazaLlenada = PlazaOficial.value.trim() !== "";
    const motivoCambioLlenado = TextAreaMotivo.value.length > 9;



    if (catalogoValido && equiposSeleccionados && DestinoSeleccionado && plazaLlenada && motivoCambioLlenado) {

        BtnGuardar.disabled = false;

    } else {

        BtnGuardar.disabled = true;
    }


};

const TextAreaLleno = () => {

    if (TextAreaMotivo.value.length > 9) {
        verificarCondiciones();
        TextAreaMotivo.classList.remove('border-danger');
    } else {
        TextAreaMotivo.classList.add('border-danger');
        BtnGuardar.disabled = true;
    }
}


const LimpiarTodo = () =>{


    InputCatalogo.value = "";

    GradoOficial.value = "";
    NombreOficial.value = "";
    FotoOficial.innerHTML = `<i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>`;
    FotoOficial.style.backgroundColor = '#f0f0f0';
    PlazaOficial.value = "";
    InputCatalogo.classList.add('border-danger');
    icon_chek.style.display = 'none';
    icon_error.style.display = 'none';

    TextAreaMotivo.value = "";
    TextAreaMotivo.classList.add('border-danger');

    SelectDestino.value = "";


    const checkboxes = document.querySelectorAll('.fila-seleccionada');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    filasSeleccionadas.length = 0;
    verificarCondiciones();

};


const Guardar = async (e) => {
    e.preventDefault();

    if (!filasSeleccionadas.length || BtnGuardar.disabled) {
        return;
    }

    BtnGuardar.disabled = true;

    const Destino = SelectDestino.value;
    const plaza = PlazaOficial.value;
    const motivo = TextAreaMotivo.value.trim();

    const equiposData = [];

    try {

        for (const equipo of filasSeleccionadas) {
            equiposData.push({
                idEquipo: equipo.eqp_id,
                asi_id: equipo.asi_id, 
                destino: Destino,
                plaza: plaza,
                motivo: motivo
            });
        }

        const formData = new FormData();
        formData.append('equipos', JSON.stringify(equiposData));

        const url = '/CECOM/API/asignacion_destino/guardar';
        const config = {
            method: 'POST',
            body: formData
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
            MostrarEquipos();
            LimpiarTodo();
            
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
        console.error('Error al enviar los datos:', error);
    } finally {
        BtnGuardar.disabled = false;
    }
};


BtnGuardar.addEventListener('click', Guardar);
BtnLimpiar.addEventListener('click', LimpiarTodo);
TextAreaMotivo.addEventListener('input', TextAreaLleno);
SelectDestino.addEventListener('change', VerificarSelect)
MostrarEquipos();