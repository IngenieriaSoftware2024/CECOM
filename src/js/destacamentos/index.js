// import { Dropdown } from "bootstrap";
// import DataTable from "datatables.net-bs5";
// import L from 'leaflet';
// import 'leaflet/dist/leaflet.css';
// import Swal from "sweetalert2";
// import { lenguaje } from "../lenguaje";
// import { validarFormulario } from "../funciones";

// const inputLongitud = document.getElementById('ubi_longitud');
// const inputLatitud = document.getElementById('ubi_latitud');
// const formularioDestacamentos = document.getElementById('FormDestacamentos');
// const btnBuscarCoordenadas = document.getElementById('BtnBuscarCoordenadas');
// const btnBuscarNombre = document.getElementById('BtnBuscarNombre');
// const inputBuscarLocalizacion = document.getElementById('BuscarLocalizacion');
// const DestacamentosIngresados = document.getElementById('DestacamentosIngresados');
// const BtnGuardar = document.getElementById('BtnGuardar');

// const latitudPredeterminada = 14.6349;
// const longitudPredeterminada = -90.5069;

// if (navigator.geolocation) {

//     navigator.geolocation.getCurrentPosition((posicion) => {
//         const latitudUsuario = posicion.coords.latitude;
//         const longitudUsuario = posicion.coords.longitude;
//         const precision = posicion.coords.accuracy;

//         const esUbicacionPrecisa = precision < 100;
//         const latitudAUsar = esUbicacionPrecisa ? latitudUsuario : latitudPredeterminada;
//         const longitudAUsar = esUbicacionPrecisa ? longitudUsuario : longitudPredeterminada;

//         const capaSatelital = L.tileLayer('https://api.maptiler.com/maps/satellite/{z}/{x}/{y}.jpg?key=SbjO1gT7kAZazcZCdLYj', {
//             attribution: '© OpenStreetMap contributors, © MapTiler',
//         });

//         const capaEtiquetas = L.tileLayer('https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}@2x.jpg?key=SbjO1gT7kAZazcZCdLYj', {
//             attribution: '© OpenStreetMap contributors, © MapTiler',
//             tileSize: 512,
//             zoomOffset: -1
//         });

//         const mapa = L.map('map', {
//             layers: [capaSatelital, capaEtiquetas]
//         }).setView([latitudAUsar, longitudAUsar], 13);

//         let marcadorActual = null;

//         const iconoPersonalizado = L.icon({
//             iconUrl: './images/localizacion.png',
//             iconSize: [25, 32],
//             iconAnchor: [16, 32],
//             popupAnchor: [0, -32]
//         });

//         const agregarMarcador = (lat, lng) => {
//             if (marcadorActual) {
//                 mapa.removeLayer(marcadorActual);
//             }

//             marcadorActual = L.marker([lat, lng], { icon: iconoPersonalizado }).addTo(mapa);
//             marcadorActual.bindPopup(`<b>Lat: ${lat}</b><br><b>Lng: ${lng}</b>`).openPopup();
//         };

//         agregarMarcador(latitudAUsar, longitudAUsar);

//         btnBuscarNombre.addEventListener('click', async (e) => {
//             e.preventDefault();

//             // Deshabilitar el botón mientras se realiza la búsqueda
//             btnBuscarNombre.disabled = true;

//             const nombreLugar = inputBuscarLocalizacion.value;

//             if (nombreLugar.trim() !== '') {
//                 try {

//                     const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${nombreLugar}`);
//                     const data = await response.json();

//                     if (data.length > 0) {
//                         const { lat, lon } = data[0];
//                         inputLatitud.value = lat;
//                         inputLongitud.value = lon;
//                         agregarMarcador(lat, lon);
//                         mapa.setView([lat, lon], 13);
//                     } else {
//                         Swal.fire({
//                             title: 'No se encontró el lugar',
//                             text: 'Intenta con otro nombre.',
//                             icon: 'error',
//                             showConfirmButton: false,
//                             timer: 1500,
//                             timerProgressBar: true
//                         });
//                     }
//                 } catch (error) {
//                     Swal.fire({
//                         title: 'Error',
//                         text: 'Hubo un problema al buscar el lugar.',
//                         icon: 'error',
//                         showConfirmButton: false,
//                         timer: 1500,
//                         timerProgressBar: true
//                     });
//                 } finally {

//                     btnBuscarNombre.disabled = false;
//                 }
//             } else {
//                 Swal.fire({
//                     title: 'Error',
//                     text: 'Por favor ingresa un nombre de lugar.',
//                     icon: 'error',
//                     showConfirmButton: false,
//                     timer: 1500,
//                     timerProgressBar: true
//                 });


//                 btnBuscarNombre.disabled = false;
//             }
//         });


//         btnBuscarCoordenadas.addEventListener('click', (e) => {
//             e.preventDefault();

//             const lat = parseFloat(inputLatitud.value);
//             const lng = parseFloat(inputLongitud.value);

//             if (!isNaN(lat) && !isNaN(lng)) {
//                 agregarMarcador(lat, lng);
//                 mapa.setView([lat, lng], mapa.getZoom());
//             } else {
//                 Swal.fire({
//                     title: 'Error',
//                     text: 'Ingrese coordenadas válidas',
//                     icon: 'error',
//                     showConfirmButton: false,
//                     timer: 1500,
//                     timerProgressBar: true
//                 });
//             }
//         });

//         mapa.on('click', (e) => {
//             const lat = e.latlng.lat;
//             const lng = e.latlng.lng;

//             inputLatitud.value = lat;
//             inputLongitud.value = lng;

//             agregarMarcador(lat, lng);
//         });

//     }, (error) => {
//         Swal.fire({
//             title: 'Error',
//             text: 'No se pudo obtener la ubicación. Usando ubicación predeterminada.',
//             icon: 'error',
//             showConfirmButton: false,
//             timer: 1500,
//             timerProgressBar: true
//         });

//         const capaSatelital = L.tileLayer('https://api.maptiler.com/maps/satellite/{z}/{x}/{y}.jpg?key=SbjO1gT7kAZazcZCdLYj', {
//             attribution: '© OpenStreetMap contributors, © MapTiler',
//         });

//         const capaEtiquetas = L.tileLayer('https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}@2x.jpg?key=SbjO1gT7kAZazcZCdLYj', {
//             attribution: '© OpenStreetMap contributors, © MapTiler',
//             tileSize: 512,
//             zoomOffset: -1
//         });

//         const mapa = L.map('map', {
//             layers: [capaSatelital, capaEtiquetas]
//         }).setView([latitudPredeterminada, longitudPredeterminada], 13);

//         agregarMarcador(latitudPredeterminada, longitudPredeterminada);
//     });
// } else {
//     alert("Tu navegador no soporta geolocalización.");
// }


// const BuscarDestacamentos = async () => {

//     let AlertaCargando = Swal.fire({
//         title: 'Cargando',
//         text: 'Por favor espera mientras se cargan las marcas',
//         icon: 'info',
//         allowOutsideClick: false,
//         didOpen: () => {
//             Swal.showLoading();
//         }
//     });

//     const url = '/CECOM/API/destacamentos/buscar';
//     const config = {
//         method: 'GET'
//     };

//     const respuesta = await fetch(url, config);
//     const datos = await respuesta.json();
//     console.log(datos)
//     const { codigo, mensaje, data } = datos

//     if (codigo === 1) {

//         datatable.clear().draw();

//         if (data) {
//             datatable.rows.add(data).draw();
//         }
//         Swal.close();
//     } else {
//         Swal.close();
//         console.log(mensaje)
//     }


// };


// const datatable = new DataTable('#DestacamentosIngresados', {
//     dom: `
//         <"row mt-3 justify-content-between" 
//             <"col" l> 
//             <"col" B> 
//             <"col-3" f>
//         >
//         t
//         <"row mt-3 justify-content-between" 
//             <"col-md-3 d-flex align-items-center" i> 
//             <"col-md-8 d-flex justify-content-end" p>
//         >
//     `,
//     language: lenguaje,
//     data: [],
//     columns: [
//         {
//             title: 'No.',
//             data: 'ubi_id',
//             width: '%',
//             render: (data, type, row, meta) => meta.row + 1
//         },
//         { title: 'Nombre', data: 'ubi_nombre'},
//         { title: 'Latitud', data: 'ubi_latitud'},
//         { title: 'Longitud', data: 'ubi_longitud'},
//         {
//             title: 'Acciones',
//             data: 'ubi_id',
//             searchable: false,
//             orderable: false,
//             render: (data, type, row, meta) => {
//                 return `
//                 <div class='d-flex justify-content-center'>

//                     <button class='btn btn-warning modificar mx-1' data-id="${data}" data-nombre="${row.ubi_nombre}"  data-latitud="${row.ubi_latitud}" data-longitud="${row.ubi_longitud}">
//                         <i class='bi bi-pencil-square'></i> Modificar
//                     </button>

//                     <button class='btn btn-danger eliminar mx-1' data-id="${data}">
//                        <i class="bi bi-trash3"></i>Eliminar
//                     </button>
//                 </div>`
//             }
//         }
//     ]
// });

// const Guardar = async (e) => {
//     e.preventDefault();

//     BtnGuardar.disabled = true

//     if (!validarFormulario(formularioDestacamentos, ['ubi_id']) || !validarCoordenadas()) {
//         Swal.fire({
//             title: "Campos vacíos",
//             text: "Debe llenar todos los campos",
//             icon: "info"
//         });
//         BtnGuardar.disabled = false
//         return;
//     }

//     try {
//         const body = new FormData(formularioDestacamentos);
//         const url = '/CECOM/API/destacamentos/guardar';

//         const config = {
//             method: 'POST',
//             body
//         };

//         const respuesta = await fetch(url, config);
//         const data = await respuesta.json();
//         console.log
//         const { codigo, mensaje } = data;

//         if (codigo === 2) {
//             await Swal.fire({
//                 title: '¡Éxito!',
//                 text: mensaje,
//                 icon: 'success',
//                 showConfirmButton: false,
//                 timer: 1500,
//                 timerProgressBar: true,
//                 background: '#e0f7fa',
//                 customClass: {
//                     title: 'custom-title-class',
//                     text: 'custom-text-class'
//                 }
//             });

//         } else {
//             Swal.fire({
//                 title: '¡Error!',
//                 text: mensaje,
//                 icon: 'error',
//                 showConfirmButton: false,
//                 timer: 1500,
//                 timerProgressBar: true,
//                 background: '#e0f7fa',
//                 customClass: {
//                     title: 'custom-title-class',
//                     text: 'custom-text-class'
//                 }
//             });
//         }
//     } catch (error) {
//         console.log(error);
//     }
//     formularioDestacamentos.reset();
//     BuscarDestacamentos();
//     BtnGuardar.disabled = false

// };

// const validarCoordenadas = () => {
//     const latitud = parseFloat(inputLatitud.value);
//     const longitud = parseFloat(inputLongitud.value);


//     if (isNaN(latitud) || latitud < -90 || latitud > 90) {
//         Swal.fire({
//             title: "Latitud inválida",
//             text: "La latitud debe estar entre -90 y 90.",
//             icon: "error"
//         });
//         return false;
//     }

//     if (isNaN(longitud) || longitud < -180 || longitud > 180) {
//         Swal.fire({
//             title: "Longitud inválida",
//             text: "La longitud debe estar entre -180 y 180.",
//             icon: "error"
//         });
//         return false;
//     }

//     return true;
// };


import { Dropdown } from "bootstrap";
import DataTable from "datatables.net-bs5";
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Swal from "sweetalert2";
import { lenguaje } from "../lenguaje";
import { validarFormulario } from "../funciones";

const inputLongitud = document.getElementById('ubi_longitud');
const inputLatitud = document.getElementById('ubi_latitud');
const formularioDestacamentos = document.getElementById('FormDestacamentos');
const btnBuscarCoordenadas = document.getElementById('BtnBuscarCoordenadas');
const btnBuscarNombre = document.getElementById('BtnBuscarNombre');
const inputBuscarLocalizacion = document.getElementById('BuscarLocalizacion');
const DestacamentosIngresados = document.getElementById('DestacamentosIngresados');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnCancelar = document.getElementById('BtnCancelar');
const TituloCrear = document.getElementById('TituloBuscarNombre');
const TituloPrincipal = document.getElementById('TituloCrear')

BtnModificar.parentElement.classList.add('d-none');
BtnCancelar.parentElement.classList.add('d-none');

const latitudPredeterminada = 14.6349;
const longitudPredeterminada = -90.5069;

let mapa, marcadorActual;

const iconoPersonalizado = L.icon({
    iconUrl: './images/localizacion.png',
    iconSize: [25, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
});

const agregarMarcador = (lat, lng, nombre) => {
    if (marcadorActual) {
        mapa.removeLayer(marcadorActual);
    }
    marcadorActual = L.marker([lat, lng], { icon: iconoPersonalizado }).addTo(mapa);

    marcadorActual.bindPopup(`<b style="font-size: 16px; color: #007bff;">${nombre}</b><br><b>Lat: ${lat}</b><br><b>Lng: ${lng}</b>`).openPopup();
};


const cargarMapa = (lat, lon) => {
    const capaSatelital = L.tileLayer('https://api.maptiler.com/maps/satellite/{z}/{x}/{y}.jpg?key=SbjO1gT7kAZazcZCdLYj', {
        attribution: '© OpenStreetMap contributors, © MapTiler',
    });

    const capaEtiquetas = L.tileLayer('https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}@2x.jpg?key=SbjO1gT7kAZazcZCdLYj', {
        attribution: '© OpenStreetMap contributors, © MapTiler',
        tileSize: 512,
        zoomOffset: -1
    });

    mapa = L.map('map', {
        layers: [capaSatelital, capaEtiquetas]
    }).setView([lat, lon], 13);

    mapa.on('click', (e) => {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        inputLatitud.value = lat;
        inputLongitud.value = lng;

        agregarMarcador(lat, lng, 'Ubicación seleccionada');
    });
};

const iconoDestacamento = L.icon({
    iconUrl: './images/localizacion_azul.png',
    iconSize: [48, 35],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
});



const cargarDestacamentos = async () => {
    const url = '/CECOM/API/destacamentos/buscar';
    const config = {
        method: 'GET'
    };

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo === 1 && data && data.length > 0) {
            datatable.clear().draw();

            if (data) {
                data.forEach(destacamento => {
                    const { ubi_nombre, ubi_latitud, ubi_longitud } = destacamento;

                    // Crear el marcador
                    const marcador = L.marker([ubi_latitud, ubi_longitud], { icon: iconoDestacamento }).addTo(mapa);

                    // Agregar el popup
                    marcador.bindPopup(`<b>${ubi_nombre}</b>`, {
                        closeButton: true,  // Mostrar botón de cierre
                        autoClose: false,   // No cerrar automáticamente al hacer clic en otro lugar
                        closeOnEscapeKey: true, // Cerrar con la tecla Escape
                        className: 'custom-label'
                    });
                    
                    // Abrir el popup al cargar
                    marcador.openPopup();
                });
                datatable.rows.add(data).draw();
            }
            Swal.close();
        } else {
            console.log('No se encontraron destacamentos');
        }
    } catch (error) {
        console.error('Error al cargar los destacamentos:', error);
    }
};

const datatable = new DataTable('#DestacamentosIngresados', {
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
            data: 'ubi_id',
            width: '%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { title: 'Nombre', data: 'ubi_nombre' },
        { title: 'Latitud', data: 'ubi_latitud' },
        { title: 'Longitud', data: 'ubi_longitud' },
        {
            title: 'Acciones',
            data: 'ubi_id',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center'>
                    <button class='btn btn-warning modificar mx-1' data-id="${data}" data-nombre="${row.ubi_nombre}"  data-latitud="${row.ubi_latitud}" data-longitud="${row.ubi_longitud}">
                        <i class='bi bi-pencil-square'></i> Modificar
                    </button>
                    <button class='btn btn-danger eliminar mx-1' data-id="${data}">
                       <i class="bi bi-trash3"></i>Eliminar
                    </button>
                </div>`
            }
        }
    ]
});


if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((posicion) => {
        const latitudUsuario = posicion.coords.latitude;
        const longitudUsuario = posicion.coords.longitude;
        const precision = posicion.coords.accuracy;

        const esUbicacionPrecisa = precision < 100;
        const latitudAUsar = esUbicacionPrecisa ? latitudUsuario : latitudPredeterminada;
        const longitudAUsar = esUbicacionPrecisa ? longitudUsuario : longitudPredeterminada;

        cargarMapa(latitudAUsar, longitudAUsar);
        mapa.setView([latitudAUsar, longitudAUsar], 13);
        agregarMarcador(latitudAUsar, longitudAUsar, 'Tu ubicación');
        cargarDestacamentos();
    }, (error) => {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo obtener la ubicación. Usando ubicación predeterminada.',
            icon: 'error',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });

        cargarMapa(latitudPredeterminada, longitudPredeterminada);
        agregarMarcador(latitudPredeterminada, longitudPredeterminada, 'Ubicación predeterminada');
        cargarDestacamentos();
    });
} else {
    alert("Tu navegador no soporta geolocalización.");
    cargarMapa(latitudPredeterminada, longitudPredeterminada);
    agregarMarcador(latitudPredeterminada, longitudPredeterminada, 'Ubicación predeterminada');
    cargarDestacamentos();
}

const BuscarCoordenadas = (e) => {
    e.preventDefault();

    const lat = parseFloat(inputLatitud.value);
    const lng = parseFloat(inputLongitud.value);

    if (!isNaN(lat) && !isNaN(lng)) {
        agregarMarcador(lat, lng, 'Ubicación personalizada');
        mapa.setView([lat, lng], mapa.getZoom());
    } else {
        Swal.fire({
            title: 'Error',
            text: 'Ingrese coordenadas válidas',
            icon: 'error',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
    }
};

const BuscarNombres = async (e) => {
    e.preventDefault();
    btnBuscarNombre.disabled = true;
    const nombreLugar = inputBuscarLocalizacion.value;

    if (nombreLugar.trim() !== '') {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${nombreLugar}`);
            const data = await response.json();

            if (data.length > 0) {
                const { lat, lon, display_name } = data[0];
                inputLatitud.value = lat;
                inputLongitud.value = lon;


                if (marcadorActual) {
                    mapa.removeLayer(marcadorActual);
                }


                agregarMarcador(lat, lon, display_name);

               
                mapa.setView([lat, lon], 25);
            } else {
                Swal.fire('No se encontraron resultados');
            }
        } catch (error) {
            console.error('Error en la búsqueda de lugar:', error);
            Swal.fire('Error al buscar el lugar');
        }
    } else {
        Swal.fire('Por favor ingrese un nombre de lugar');
    }

    btnBuscarNombre.disabled = false;
};



const Guardar = async (e) => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formularioDestacamentos, ['ubi_id']) || !validarCoordenadas()) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        BtnGuardar.disabled = false;
        return;
    }

    try {
        const body = new FormData(formularioDestacamentos);
        const url = '/CECOM/API/destacamentos/guardar';
        const config = { method: 'POST', body };
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
                customClass: { title: 'custom-title-class', text: 'custom-text-class' }
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
                customClass: { title: 'custom-title-class', text: 'custom-text-class' }
            });
        }
    } catch (error) {
        console.log(error);
    }
    formularioDestacamentos.reset();
    BtnGuardar.disabled = false;
    cargarDestacamentos();
};


const validarCoordenadas = () => {
    const latitud = parseFloat(inputLatitud.value);
    const longitud = parseFloat(inputLongitud.value);

    if (isNaN(latitud) || latitud < -90 || latitud > 90) {
        Swal.fire({
            title: "Latitud inválida",
            text: "La latitud debe estar entre -90 y 90.",
            icon: "error"
        });
        return false;
    }

    if (isNaN(longitud) || longitud < -180 || longitud > 180) {
        Swal.fire({
            title: "Longitud inválida",
            text: "La longitud debe estar entre -180 y 180.",
            icon: "error"
        });
        return false;
    }

    return true;
};

const validarSoloNumeros = (event) => {
    const regex = /^[0-9.-]*$/;
    if (!regex.test(event.key)) {
        event.preventDefault();
    }
};

const Modificar = async (e) => {
    e.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formularioDestacamentos, [])) {
        Swal.fire({
            title: "Campos vacíos",
            text: "Debe llenar todos los campos",
            icon: "info"
        });
        return;
    }

    try {
        const body = new FormData(formularioDestacamentos);
        const url = '/CECOM/API/destacamentos/modificar';

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
                customClass: { title: 'custom-title-class', text: 'custom-text-class' }
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
                    text: 'cutsom-text-class'
                }
            });
        }
    } catch (error) {
        console.log(error);
    }
    BtnModificar.disabled = false;
    formularioDestacamentos.reset();
    cargarDestacamentos();
    Cancelar();
};


const Eliminar = async (e) => {
    const id = e.currentTarget.dataset.id;

    let confirmacion = await Swal.fire({
        title: '¿Está seguro de que desea eliminar este Destacamento/Ubicación?',
        text: "Esta acción es irreversible.",
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
            denyButton: 'custom-deny-button'
        }
    });

    if (confirmacion.isConfirmed) {
        try {
            const body = new FormData();
            body.append('id', id);

            const url = '/CECOM/API/destacamento/eliminar';
            const config = {
                method: 'POST',
                body
            };

            const respuesta = await fetch(url, config);
            const data = await respuesta.json();
            const { codigo, mensaje } = data;

            if (codigo === 4) {

                await Swal.fire({
                    title: '¡Éxito!',
                    text: mensaje,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    background: '#e0f7fa',
                    customClass: { title: 'custom-title-class', text: 'custom-text-class' }
                });
                
                mapa.eachLayer((layer) => {
                    if (layer instanceof L.Marker) {
                        mapa.removeLayer(layer);
                    }
                });
                cargarDestacamentos(); 
                
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
                        text: 'cutsom-text-class'
                    }
                });
            }
        } catch (error) {
            console.log(error);
        }

    }
};

const llenarDatos = (e) => {
    const elemento = e.currentTarget.dataset;

    formularioDestacamentos.ubi_id.value = elemento.id;
    formularioDestacamentos.ubi_nombre.value = elemento.nombre;
    formularioDestacamentos.ubi_longitud.value = elemento.longitud;
    formularioDestacamentos.ubi_latitud.value = elemento.latitud;


    TituloCrear.classList.add('d-none')
    BtnGuardar.parentElement.classList.add('d-none');
    BtnModificar.parentElement.classList.remove('d-none');
    BtnCancelar.parentElement.classList.remove('d-none');
    TituloPrincipal.innerHTML = 'Modificacion de Destacamento/Ubicacion'
};

const Cancelar = () => {

    formularioDestacamentos.reset();
    BtnGuardar.parentElement.classList.remove('d-none');
    BtnModificar.parentElement.classList.add('d-none');
    BtnCancelar.parentElement.classList.add('d-none');
    TituloCrear.classList.remove('d-none');
    TituloPrincipal.innerHTML = 'Agregar Destacamento / Ubicación';


};

datatable.on('click', '.modificar', llenarDatos);
datatable.on('click', '.eliminar', Eliminar);
BtnCancelar.addEventListener('click', Cancelar);
btnBuscarCoordenadas.addEventListener('click', BuscarCoordenadas);
btnBuscarNombre.addEventListener('click', BuscarNombres);
inputLatitud.addEventListener("keypress", validarSoloNumeros);
inputLongitud.addEventListener("keypress", validarSoloNumeros);
formularioDestacamentos.addEventListener('submit', Guardar);
BtnModificar.addEventListener('click', Modificar)
cargarDestacamentos();