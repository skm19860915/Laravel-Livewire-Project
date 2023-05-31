import { options } from 'dropzone';
import helper from './helper';
import alertify from 'alertifyjs';
import formTable from './form_table';
let products = {

  init: function(){

        console.log('products');

        const params = {
            trigger: document.getElementById('addNewFormRowForProductsTable'),
            tbody: document.getElementById('products-rows'),
            cols: { name: 'text', amount: 'number', price: 'number',description:'text',note:"text" },
            saveLink: true,
            action:true
        }

        new formTable(params);

    },




}

products.init();

