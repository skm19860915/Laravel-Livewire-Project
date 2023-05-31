
import { options } from 'dropzone';
import helper from './helper';
import alertify from 'alertifyjs';
import formTable from './form_table';
let services = {

  init: function(){

        console.log('services');


         const params = {
            trigger: document.getElementById('addNewFormRowForServicesTable'),
            tbody: document.getElementById('services-rows'),
             cols: { name: 'text', price: 'number',receivable:"checkbox",description:'text',note:"text" },
             saveLink: true,
            action:true
        }
         new formTable(params);


    },




}

services.init();

