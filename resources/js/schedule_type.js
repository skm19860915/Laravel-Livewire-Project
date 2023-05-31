// require('./app');
import helper from './helper';

let schedule_type = {

  init: function(){

        this.dataTable();
        this.formSubmission()
        console.log("schedule_type.js");


    },
    formSubmission: function () {

    $('.forms').on('submit', (e)=>{
      let form = $(e.target);
      let submitBtn = form.find('.submit-buttons');
      submitBtn.prop('disabled', true).text('Processing...');
    });

  },


    dataTable: () => {
        $('#scheduleDatatable').DataTable();
  }

}

schedule_type.init();

