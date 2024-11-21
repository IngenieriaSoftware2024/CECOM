
import { Dropdown, Modal } from "bootstrap";
import DataTable from "datatables.net-bs5";
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";

let mapa;

// Función para generar un marcador de un color específico
const generarIcono = (color) => {
    return L.divIcon({
        className: 'custom-div-icon',
        html: `<div style='background-color:${color};' class='marker-pin'></div>`,
        iconSize: [30, 42],
        iconAnchor: [15, 42]
    });
};


const estilosMarcadores = `
<style>
.custom-div-icon {
    border: none;
    background: none;
}
.marker-pin {
    width: 30px;
    height: 30px;
    border-radius: 50% 50% 50% 0;
    background: #89849b;
    position: absolute;
    transform: rotate(-45deg);
    left: 50%;
    top: 50%;
    margin: -15px 0 0 -15px;
    border: 4px solid #FFFFFF;
    box-shadow: 0 2px 5px rgba(0,0,0,0.5);
}
.marker-pin::after {
    content: '';
    width: 10px;
    height: 10px;
    margin: 8px 0 0 8px;
    background: #fff;
    position: absolute;
    border-radius: 50%;
}
</style>
`;
document.head.insertAdjacentHTML('beforeend', estilosMarcadores);

const iconosEquipos = {
    Radios: generarIcono('red'),
    Antenas: generarIcono('blue'),
    Repetidoras: generarIcono('green'),
    Enlaces: generarIcono('purple')
};


const iconoDestacamento = L.icon({
    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    shadowSize: [41, 41]
});


const grupoDestacamentos = L.featureGroup();

const gruposEquipos = {
    Radios: L.featureGroup(),
    Antenas: L.featureGroup(),
    Repetidoras: L.featureGroup(),
    Enlaces: L.featureGroup(),
};

const inicializarMapa = () => {
    const latitud = -12.0464;
    const longitud = -77.0428;

    const capaSatelital = L.tileLayer('https://api.maptiler.com/maps/satellite/{z}/{x}/{y}.jpg?key=SbjO1gT7kAZazcZCdLYj', {
        attribution: '© OpenStreetMap contributors, © MapTiler',
    });

    const capaEtiquetas = L.tileLayer('https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}@2x.jpg?key=SbjO1gT7kAZazcZCdLYj', {
        attribution: '© OpenStreetMap contributors, © MapTiler',
        tileSize: 512,
        zoomOffset: -1
    });

    mapa = L.map('map', {
        layers: [capaSatelital, capaEtiquetas],
        fullscreenControl: true
    }).setView([latitud, longitud], 6);

    const baseMaps = {
        "Satélite": capaSatelital,
        "Híbrido": capaEtiquetas
    };
    L.control.layers(baseMaps).addTo(mapa);

    grupoDestacamentos.addTo(mapa);
};

const cargarDestacamentos = async () => {
    const url = '/CECOM/API/destacamentos/mostrar-todos';
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        if (codigo === 1 && data && data.length > 0) {
            grupoDestacamentos.clearLayers();

            data.forEach(destacamento => {
                const { ubi_id, ubi_nombre, ubi_latitud, ubi_longitud } = destacamento;

                if (ubi_latitud && ubi_longitud) {
                    const marcador = L.marker(
                        [parseFloat(ubi_latitud), parseFloat(ubi_longitud)],
                        { icon: iconoDestacamento }
                    ).addTo(grupoDestacamentos);

                    marcador.on('click', () => {
                        mostrarModalDestacamento(destacamento);
                    });

                    marcador.bindTooltip(ubi_nombre, {
                        permanent: false,
                        direction: 'top',
                        offset: [0, -35]
                    });
                }
            });

            if (grupoDestacamentos.getLayers().length > 0) {
                mapa.fitBounds(grupoDestacamentos.getBounds());
            }

            Swal.close();
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Sin Destacamentos',
                text: 'No se encontraron destacamentos'
            });
        }
    } catch (error) {
        console.error('Error al cargar los destacamentos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los destacamentos'
        });
    }
};

const cargarEquipos = async (tipo) => {
    const url = `/CECOM/API/seleccion/tipo?tipo=${tipo.toLowerCase()}`;
    const config = { method: 'GET' };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, data } = datos;

        gruposEquipos[tipo].clearLayers(); 

        if (codigo === 1 && data && data.length > 0) {
            data.forEach(equipo => {
                const { ubi_latitud, ubi_longitud, cantidad_equipos } = equipo;

                if (ubi_latitud && ubi_longitud) {
                    const marcador = L.marker(
                        [parseFloat(ubi_latitud), parseFloat(ubi_longitud)],
                        { 
                            icon: iconosEquipos[tipo]
                        }
                    ).addTo(gruposEquipos[tipo]);

                    marcador.bindTooltip(`Cantidad: ${cantidad_equipos}`, {
                        permanent: false,
                        direction: 'top'
                    });
                }
            });

            gruposEquipos[tipo].addTo(mapa);
        }
    } catch (error) {
        console.error(`Error al cargar los equipos de tipo ${tipo}:`, error);
    }
};



const limpiarEquipos = () => {
    Object.values(gruposEquipos).forEach((grupo) => grupo.clearLayers());
};

const manejarSwitch = (event) => {
    const switchElement = event.target;
    const tipo = switchElement.id;

    
    if (tipo === "Todo" && switchElement.checked) {
        document.querySelectorAll('.estado').forEach((input) => {
            if (input.id !== "Todo") {
                input.checked = false;
            }
        });
        limpiarEquipos();
        grupoDestacamentos.addTo(mapa);
        return;
    }

    
    document.getElementById("Todo").checked = false;

    
    grupoDestacamentos.remove();

    
    if (switchElement.checked) {
        // Cargar equipos con el icono rojo (seleccionado)
        cargarEquipos(tipo, true);
    } else {
        // Limpiar los equipos si el switch está desactivado
        gruposEquipos[tipo].clearLayers();
    }
};


const mostrarModalDestacamento = async (destacamento) => {
    const { ubi_id, ubi_nombre } = destacamento;

    let AlertaCargando = Swal.fire({
        title: 'Cargando',
        text: 'Por favor espera mientras se cargan los equipos',
        icon: 'info',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });



    try {
        const respuesta = await fetch(`/CECOM/API/equipos/destacamento?destacamento=${ubi_id}`);
        const config = { method: 'GET' };

        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        const modalTitulo = document.getElementById('modalEquiposTitulo');
        modalTitulo.textContent = `Equipos asignados a: ${ubi_nombre}`;


        datatable.clear().draw();

        if (codigo === 1 && data && data.length > 0) {

            datatable.rows.add(data).draw();
       
        } else {

            datatable.row.add({
                acc_id: '',
                clase: 'No hay equipos asignados',
                marca: '',
                gama: '',
                serie: ''
            }).draw();
        }
        
        Swal.close();

        const ModalEqp = document.getElementById('modalEquipos')
        const ModalEquipos = new Modal(ModalEqp);
        ModalEquipos.show();

    } catch (error) {
        Swal.close();
        console.error('Error al cargar los equipos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los equipos'
        });
    }
};


const datatable = new DataTable('#EquiposDestacamento', {
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
        { title: 'Clase', data: 'clase' },
        { title: 'Marca', data: 'marca' },
        { title: 'Gama', data: 'gama' },
        { title: 'No. Serie', data: 'serie' }
    ]
});


document.querySelectorAll('.estado').forEach((switchElement) => {
    switchElement.addEventListener('change', manejarSwitch);
});

document.addEventListener('DOMContentLoaded', () => {
    const switchTodo = document.getElementById("Todo");
    switchTodo.checked = true;  
});



inicializarMapa();
cargarDestacamentos();