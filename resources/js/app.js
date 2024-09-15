import './bootstrap';
import "flatpickr/dist/themes/material_blue.css";

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard'
import flatpickr from "flatpickr";
import Tooltip from "@ryangjchandler/alpine-tooltip";

Alpine.plugin(Clipboard);
Alpine.plugin(Tooltip);

window.flatpickr = flatpickr;

Livewire.start()
