// require('./app');
import helper from './helper';
const bootbox =   window.bootbox;

let locationJS = {

  init: function(){
    $('#locationsDatatable').DataTable();

        // this.edit();
        this.formSubmission()
        console.log("locations.js");

    },
    formSubmission: function () {

    $('.forms').on('submit', (e)=>{
      let form = $(e.target);
      let submitBtn = form.find('.submit-buttons');
      submitBtn.prop('disabled', true).text('Processing...');
    });

    $("[name='is_primary']").change(e=>{
        const target = e.target;
        const checked = target?.parentElement?.parentElement?.querySelector("[type='checkbox']")?.checked;
        if(!checked) return target.checked = false;
        // console.log('ok');
    })
    $("input[type='checkbox']").change(e=>{
        let target = e.target;
        const is_primary = target?.parentElement?.parentElement?.parentElement?.querySelector("[name='is_primary']");
        if(!target.checked) is_primary.checked = 0;
    })


    $(".disable-user-button").click(e=>{
      // let no = !window.confirm("Are you sure?");
      // if(no) e.preventDefault();
       e.preventDefault();
      bootbox.confirm({
          message: "Are you sure you wish to disable this user?",
          buttons: {
              cancel: {
                label: 'Yes',
                className: 'btn-primary'
              },
              confirm: {
                  label: 'Cancel',
                  className: 'btn-secondary'
              },
          },
          callback: async function (result) {
              if(!result)
              {
                  let x = e.target.href;
                  window.location = x;
                  delete bootbox.confirm
              }
        }})
    });

  },

}

locationJS.init();

