import L from 'leaflet';

export default class Map {

    static init () {
        let map = document.querySelector('#map');
        if (map === null) {
            return;
        }
        let center = [map.dataset.lat, map.dataset.lng];
        map = L.map('map').setView(center, 13);
        let token = 'pk.eyJ1IjoiYmlnYm9zc3MiLCJhIjoiY2sxM2p5Y3AyMDdscjNucnFlb3hxNDdvMSJ9.K0_TLaBIml_qFtcVcrdNUA';
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            minZoom: 12,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        L.marker(center).addTo(map);
    }
}
