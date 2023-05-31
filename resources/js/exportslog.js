// require('./app');
import helper from './helper';

let exportsLog = {

  init: function(){

        $(() => {
            $('#exporslog').dataTable({
                    "order": [[ 0, 'DESC' ]]
            });
        })

  },



}

exportsLog.init();


