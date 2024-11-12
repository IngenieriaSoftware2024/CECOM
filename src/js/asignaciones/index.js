import { Dropdown } from "bootstrap";
import DataTable from "datatables.net-bs5";
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";


const InputCatalogo = document.getElementById('catalogo_oficial');
const NombreOficial = document.getElementById('Nombre_Oficial')
const GradoOficial = document.getElementById('Grado_Oficial');
const FotoOficial = document.getElementById('FotoOficial');
const PlazaOficial = document.getElementById('Plaza_Oficial');
const TextAreaMotivo = document.getElementById('MotivoCambio');
const icon_chek = document.getElementById('icon-check');
const icon_error = document.getElementById('icon-error');
const BtnGuardar = document.getElementById('BtnGuardar');
const SelectDependencia = document.getElementById('asi_dependencia');

BtnGuardar.disabled = true;

const Buscar = async () => {

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se cargan los equipos',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const url = '/CECOM/API/equiposingresados/buscar';
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
            render: (data, type, row) => `<input type="checkbox" class="fila-seleccionada" data-asiID="${data}" data-idEquipo="${row.eqp_id}">`,
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
        { title: 'Asignado a:', data: 'dependencia' }
    ]
});


document.getElementById('seleccionar_todo').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('.fila-seleccionada');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
        alternarSeleccion(checkbox);
    });
    verificarCondiciones();
});

document.querySelector('#EquiposRegistrados tbody').addEventListener('change', e => {
    if (e.target && e.target.classList.contains('fila-seleccionada')) {
        alternarSeleccion(e.target);
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
};




const llenarFormulario = async () => {

    const per_catalogo = InputCatalogo.value.trim();
    const dependencia = SelectDependencia.value.trim();

    try {
        const body = new FormData();
        body.append('per_catalogo', per_catalogo);
        body.append('dependencia', dependencia);

        const url = `/CECOM/API/fotografia_ofical`;
        const config = {
            method: 'POST',
            body
        };

        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();

        if (datos) {

            icon_chek.style.display = 'inline';
            icon_error.style.display = 'none';

            GradoOficial.value = datos[0].grado_arma
            NombreOficial.value = datos[0].nombre_completo
            FotoOficial.innerHTML = `<img src="https://sistema.ipm.org.gt/sistema/fotos_afiliados/ACTJUB/${per_catalogo}.jpg" class="rounded-circle shadow" style="width: 125px; height: 125px;">`;
            FotoOficial.style.backgroundColor = '';
            PlazaOficial.value = datos[0].per_plaza
            InputCatalogo.classList.remove('border-danger')
            verificarCondiciones();

        } else {
            icon_chek.style.display = 'none';
            icon_error.style.display = 'inline';

            Swal.fire({
                title: '¡Error!',
                text: 'El usuario no existe o no esta asignado a esta Dependencia',
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
}



InputCatalogo.addEventListener('input', () => {

    // console.log(PlazaOficial.value)
    if (InputCatalogo.value.length === 6) {
        llenarFormulario();
    } else {

        GradoOficial.value = "";
        NombreOficial.value = "";
        FotoOficial.innerHTML = `<i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>`;
        FotoOficial.style.backgroundColor = '#f0f0f0';
        PlazaOficial.value = "";
        InputCatalogo.classList.add('border-danger')
        icon_chek.style.display = 'none';
        icon_error.style.display = 'none';
        return;
    }
    verificarCondiciones();
});


const verificarCondiciones = () => {
    const catalogoValido = InputCatalogo.value.length === 6;
    const equiposSeleccionados = filasSeleccionadas.length > 0;
    const dependenciaSeleccionada = SelectDependencia.value !== "";
    const plazaLlenada = PlazaOficial.value.trim() !== "";
    const motivoCambioLlenado = TextAreaMotivo.value.length > 9;

    if (dependenciaSeleccionada) {

        InputCatalogo.disabled = false
        

        if (catalogoValido && equiposSeleccionados && dependenciaSeleccionada && plazaLlenada && motivoCambioLlenado) {

            BtnGuardar.disabled = false;

        } else {

            BtnGuardar.disabled = true;
        }

    } else {
        GradoOficial.value = "";
        NombreOficial.value = "";
        FotoOficial.innerHTML = `<i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>`;
        FotoOficial.style.backgroundColor = '#f0f0f0';
        PlazaOficial.value = "";
        InputCatalogo.classList.add('border-danger')
        icon_chek.style.display = 'none';
        icon_error.style.display = 'none';
        InputCatalogo.value = "";
        InputCatalogo.disabled = true
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


const Guardar = async (e) => {
    e.preventDefault();

    if (!filasSeleccionadas.length || BtnGuardar.disabled) {
        return;
    }

    BtnGuardar.disabled = true;

    const dependencia = SelectDependencia.value;
    const plaza = PlazaOficial.value;
    const motivo = TextAreaMotivo.value.trim();

    const equiposData = [];
    try {

        for (const equipo of filasSeleccionadas) {
            equiposData.push({
                idEquipo: equipo.eqp_id,
                asi_id: equipo.asi_id, 
                dependencia: dependencia,
                plaza: plaza,
                motivo: motivo
            });
        }

        const formData = new FormData();
        formData.append('equipos', JSON.stringify(equiposData));

        const url = '/CECOM/API/asignacion_dependencia/guardar';
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
            Buscar();
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

SelectDependencia.addEventListener('change', () =>{

    GradoOficial.value = "";
    NombreOficial.value = "";
    FotoOficial.innerHTML = `<i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>`;
    FotoOficial.style.backgroundColor = '#f0f0f0';
    PlazaOficial.value = "";
    InputCatalogo.classList.add('border-danger')
    icon_chek.style.display = 'none';
    icon_error.style.display = 'none';
    InputCatalogo.value = "";
    verificarCondiciones();
} );
TextAreaMotivo.addEventListener('input', TextAreaLleno);
BtnGuardar.addEventListener('click', Guardar)
Buscar();

