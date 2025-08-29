import {Loader} from '@googlemaps/js-api-loader';
import axios from 'axios';

const MapPlugin = {
    state: {
        map: null,
        autocomplete: null,
        info_window: null,
        config: {
            key: null,
            map_id: null
        },
        center: { lat: 21.0285, lng: 105.8542 } // Hà nội
    },
    DOM: {
        map_container: () => document.getElementById('map-google'),
        searching_map: () => document.getElementById('map-place-autocomplete-card')
    },
    initial: {
        // B1: init config
        initConfig: async () => {
            const {data} = await axios.get('/common/google-map');
            MapPlugin.state.config.key = data.key;
            MapPlugin.state.config.map_id = data.map_id
        },

        // B2: Khởi tạo loader
        initLoader: () => {
            return new Loader({
                apiKey: MapPlugin.state.config.key,
                version: "weekly",
                libraries: ["places", "geocoding", 'maps', 'marker'],
                language: 'vi',
                region: 'vi'
            });
        },
        // B3: Khởi tạo lib
        initLib: async () => {
            await Promise.all([
                google.maps.importLibrary('maps'),
                google.maps.importLibrary("marker"),
                google.maps.importLibrary("places")
            ]);
        },

        // Initialize the map. (bắt buộc)
        initMap: () => {
            const mapEl = MapPlugin.DOM.map_container();
            if (mapEl){
                MapPlugin.state.map =  new google.maps.Map(MapPlugin.DOM.map_container(), {
                    center: MapPlugin.state.center,
                    zoom: 12,
                    mapId: MapPlugin.state.config.map_id,
                    mapTypeControl: false,
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
            }
        },
        // init info window
        initInfoWindow: () => {
            MapPlugin.state.info_window = new google.maps.InfoWindow({});
        },
        // init autocomplete
        initPlaceAutocomplete: () => {
            const cardEl = MapPlugin.DOM.searching_map();
            if (cardEl){
                MapPlugin.state.autocomplete = new google.maps.places.PlaceAutocompleteElement({
                    componentRestrictions: { country: 'VN' } // giới hạn ở việt nam
                });
                MapPlugin.state.autocomplete.id = 'place-autocomplete-input';
                MapPlugin.state.autocomplete.locationBias = MapPlugin.state.center;
                //@ts-ignore
                cardEl.appendChild(MapPlugin.state.autocomplete);
                MapPlugin.state.map.controls[google.maps.ControlPosition.TOP_LEFT].push(cardEl);
            }
        },

        // init marker
        initMarker: (position = null, draggable = false) => {
            const options = {
                map: MapPlugin.state.map,
                gmpDraggable: draggable,
                title: "Vị trí này có thể kéo thả được",
            };
            if (position){
                options.position = position // { lat: lat, lng: lng }
            }
            return new google.maps.marker.AdvancedMarkerElement(options);
        }
    },
}

export default MapPlugin;
