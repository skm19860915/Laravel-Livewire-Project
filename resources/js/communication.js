// require('./app');
import helper from './helper';
import axios from 'axios';

let communicationJS = {

  init: function(){

        console.log("communication");
        $('#emailCommunicationsTable').DataTable();
        this.updateStatus();
    },

    updateStatus: function(){
        $(".btn-status").click(e=>{
            const appUrl = helper.getSiteUrl();
            let token = $("[name='token']").attr('content');
            var id = e.target.id

            let settings = {
                method: 'POST',
                url: appUrl + '/communication/patient/delete',
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
                    location.reload();
                   }
                }
            }).catch(err => {
                console.error(err);
            });
        });
    }
}

communicationJS.init();

