import helper from './helper';
import axios from 'axios';

let emailJS = {

  init: function(){
        $('#emailsDatatable').DataTable();
        console.log('email-journey')
        this.updateStatus();
    },
    updateStatus: function(){
        $(".btn-status").click(e=>{
            const appUrl = helper.getSiteUrl();
            let token = $("[name='token']").attr('content');
            var id = e.target.id

            let settings = {
                method: 'POST',
                url: appUrl + '/settings/emails',
                data: {token, id},
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="token"]').getAttribute('content')
                },
                xsrfHeaderName: 'X-XSRF-TOKEN',
            };

            axios(settings).then(resp => {
                if(resp.status === 200) {
                   if(resp.data){
                    console.log(resp.data)
                    if(resp.data == -1){
                        $('#email_action').removeAttr("style");
                    }
                    else{
                        location.reload();
                    }
                   }
                }
            }).catch(err => {
                console.error(err);
            });
        });
    }
}

emailJS.init();

