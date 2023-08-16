import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/app.scss';
import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css'; 
import {
    Collapse,
    Dropdown,
    Ripple,
    Sidenav,
    Select,
    Datepicker,
    Modal,
    Input,
    Tab,
    Offcanvas,
    Alert,
    initTE,
  } from "tw-elements";


  initTE({ Select });
  initTE({ Collapse, Dropdown });
  initTE({ Sidenav });
  initTE({ Ripple, Input});
  initTE({ Alert });
  initTE({ Datepicker, Input });
  initTE({ Tab });
  initTE({ Modal, Ripple });
  initTE({ Offcanvas, Ripple });

const slider = document.getElementById('slider');

if(slider){ 
  const min = document.getElementById('min');
  const max = document.getElementById('max');
  const range = noUiSlider.create(slider, {
      start: [min.value || 0, max.value || 500],
      connect: true,
      step: 10,
      range: {
          'min': 0,
          'max': 500
      }
  });
  range.on('slide', function(values, handle){
    if(handle === 0){
      min.value = Math.round(values[0])
    }
    if(handle === 1){
      max.value = Math.round(values[1])
    }
  })
}