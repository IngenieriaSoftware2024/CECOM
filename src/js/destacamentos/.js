
// const inputlongitud = document.getElementById('ubi_longitud');
// const inputlatitud = document.getElementById('ubi_latitud');
// const FormDestacamentos = document.getElementById('FormDestacamentos');
// const BtnBuscar = document.getElementById('BtnBuscar');

// const defaultLat = 14.6349;
// const defaultLng = -90.5069;

// if (navigator.geolocation) {

//     navigator.geolocation.getCurrentPosition((position) => {

//         const userLat = position.coords.latitude;
//         const userLng = position.coords.longitude;
//         const accuracy = position.coords.accuracy;


//         const isLocationAccurate = accuracy < 100;


//         const latToUse = isLocationAccurate ? userLat : defaultLat;
//         const lngToUse = isLocationAccurate ? userLng : defaultLng;


//         const map = L.map('map').setView([latToUse, lngToUse], 13);


//         L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
//             attribution: 'Tiles &copy; Esri, DeLorme, NAVTEQ'
//         }).addTo(map);


//         L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
//             attribution: 'Tiles &copy; Esri'
//         }).addTo(map);


//         L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Places/MapServer/tile/{z}/{y}/{x}', {
//             attribution: 'Tiles &copy; Esri'
//         }).addTo(map);

//         let currentMarker = null; // Variable para almacenar el marcador actual


//         const customIcon = L.icon({
//             iconUrl: './images/localizacion.png',
//             iconSize: [25, 32],
//             iconAnchor: [16, 32],
//             popupAnchor: [0, -32]
//         });


//         const addMarker = (lat, lng) => {

//             if (currentMarker) {
//                 map.removeLayer(currentMarker);
//             }

//             currentMarker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
//             currentMarker.bindPopup(`<b>Lat: ${lat}</b><br><b>Lng: ${lng}</b>`).openPopup();
//         };

//         addMarker(latToUse, lngToUse);


//         map.on('click', (e) => {
//             const { lat, lng } = e.latlng;

//             inputlatitud.value = lat;
//             inputlongitud.value = lng;

//             addMarker(lat, lng);
//         });


//         BtnBuscar.addEventListener('click', (e) => {
//             e.preventDefault();

//             const lat = parseFloat(inputlatitud.value);
//             const lng = parseFloat(inputlongitud.value);


//             if (!isNaN(lat) && !isNaN(lng)) {
//                 addMarker(lat, lng);
//             } else {
//                 Swal.fire({
//                     title: 'Error',
//                     text: 'ingrese coordenadas validas',
//                     icon: 'error',
//                     showConfirmButton: false,
//                     timer: 1500,
//                     timerProgressBar: true,
//                     background: '#e0f7fa',
//                     customClass: {
//                         title: 'custom-title-class',
//                         text: 'custom-text-class'
//                     }
//                 });
//             }
//         });

//     }, (error) => {
//         Swal.fire({
//             title: 'Error',
//             text: 'No se pudo obtener la ubicación. Usando ubicación predeterminada.',
//             icon: 'error',
//             showConfirmButton: false,
//             timer: 1500,
//             timerProgressBar: true,
//             background: '#e0f7fa',
//             customClass: {
//                 title: 'custom-title-class',
//                 text: 'custom-text-class'
//             }
//         });

//         const map = L.map('map').setView([defaultLat, defaultLng], 13);

//         L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
//             attribution: 'Tiles &copy; Esri, DeLorme, NAVTEQ'
//         }).addTo(map);

//         addMarker(defaultLat, defaultLng);
//     });
// } else {
//     alert("Tu navegador no soporta geolocalización.");
// }
