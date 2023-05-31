// require('./app');
import moment from 'moment';
import helper from './helper';

let marketingTrendJS = {

  init: function(){

    $('input[name="range"]').on('change', function(e){
        console.log(e.currentTarget.id);
        getReportData(e.currentTarget.id);
    });
    const getReportData = async (type) => {
        const action  = helper.getSiteUrl()+`/reports/marketing-trend?type=${type}`;
        const _token  = $('meta[name=token]').attr('content');

        let form = document.createElement('form');
        let typeInput = document.createElement('input');
        let token = document.createElement('input');

        typeInput.setAttribute('value',type);
        token.setAttribute('value', _token);
        
        typeInput.setAttribute('name', 'type');
        token.setAttribute('name', '_token');

        form.appendChild(typeInput);
        form.appendChild(token);
        form.setAttribute('action', action)
        form.setAttribute('method', "POST")

        document.body.appendChild(form);
        form.submit();
    }
    
    



  },



}

marketingTrendJS.init();

