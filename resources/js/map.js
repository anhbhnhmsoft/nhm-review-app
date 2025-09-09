import {Loader} from '@googlemaps/js-api-loader';
import axios from 'axios';

export const MapPlugin = {
    defaultCenter: {lat: 21.0285, lng: 105.8542},// Hà nội
    map_id: null,
    // Initialize the loader. (bắt buộc)
    initLoader: async function () {
        const {data} = await axios.get('/common/google-map');
        this.map_id = data.map_id;
        const loader = new Loader({
            apiKey: data.key,
            version: "weekly",
            libraries: ["places", "geocoding", 'maps', 'marker'],
            language: 'vi',
            region: 'VN'
        });
        await loader.load();

        await Promise.all([
            google.maps.importLibrary('maps'),
            google.maps.importLibrary("marker"),
            google.maps.importLibrary("places")
        ]);
        return loader
    },

    // Initialize the map. (bắt buộc)
    createMap: (mapDOM, location, zoom) => {
        return new google.maps.Map(mapDOM, {
            center: location, // { lat: lat, lng: lng }
            zoom: zoom,
            mapTypeControl: false,
            mapId: MapPlugin.map_id,
            restriction: {
                latLngBounds: {
                    north: 23.4,
                    south: 8.3,
                    east: 109.5,
                    west: 102.1
                }, // vĩ tuyến kinh độ việt nam
                strictBounds: false // Cho phép di chuyển đến giới hạn, nhưng không vượt qua
            }
        });
    },

    createMarker: (options) => {
        return new google.maps.marker.AdvancedMarkerElement(options);
    },
    createInfowindow: (options) => {
        return new google.maps.InfoWindow(options);
    },
    plugin: {
        createMarkerStore: (map, position, store, firstOpen = true) => {
            const marker = MapPlugin.createMarker({
                map: map,
                position: position,
                content: MapPlugin.plugin.buildContentStore(store),
                title: store.name
            });
            marker.addListener("click", () => {
                if (marker.content.classList.contains("highlight")) {
                    marker.content.classList.remove("highlight");
                    marker.zIndex = null;
                } else {
                    marker.content.classList.add("highlight");
                    marker.zIndex = 10;
                }
            });
            if (firstOpen){
                marker.click();
            }
            return marker;
        },
        buildContentStore: (store) => {
            const content = document.createElement("div");
            content.classList.add("property");
            content.innerHTML = `
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>
                </div>
                <div class="image-holder">
                    <img src="${store.path}" alt="${store.slug ? store.slug : "Địa điểm"}">
                </div>
                <div class="details">
                    <div class="name">${store.name}</div>
                    <div class="address">${store.address}</div>
                    <div class="rating-location">
                        <span>${store.rate}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="rating-location-icon">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                        </svg>
                        <span>${store.reviews_count} đánh giá</span>
                    </div>
                </div>
            `;
            return content;
        },
    }
}
