import { createPopper } from '@popperjs/core';
import '@fortawesome/fontawesome-free/css/all.css';
import 'bootstrap';
import './modules/lazy-stylesheet';
import ModMenuCollapse from "./modules/mod-menu-collapse";

$(()=>{
    new ModMenuCollapse('.mod-menu-collapse');
});