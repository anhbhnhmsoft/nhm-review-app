import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import {MapPlugin} from "./map.js";

Alpine.plugin((Alpine) => {
    Alpine.magic('mapPlugin', () => MapPlugin);
});

Livewire.start()
