// require('./app');
import helper from './helper';
let marketingSource = {

  init: function(){

        console.log("Marketing Sources");
        $(".disable").click(e=>{
            // let no = !window.confirm("Are you sure you want to disable this marketing source?");
            // if(no) e.preventDefault();
            e.preventDefault();
            window.bootbox.confirm({
                message: "Are you sure you want to disable this marketing source?",
                buttons: {
                    //i make it opsite to change button order
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

        })
  },



}

marketingSource.init();

