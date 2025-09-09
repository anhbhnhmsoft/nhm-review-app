import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import {MapPlugin} from "./map.js";
import {GeoPlugin} from "./geo.js";

Alpine.plugin((Alpine) => {
    Alpine.magic('mapPlugin', () => MapPlugin);
    Alpine.magic('GeoPlugin', () => GeoPlugin);
});

Livewire.start()
