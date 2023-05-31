// require('./app');
import helper from './helper';
import alertify from 'alertifyjs';

//// alert options
alertify.defaults = {

    notifier: { delay: 5, position: 'top-right' }

};
let globalJS = {

    init: function () {
        this.maskInputs();
        helper.loadStates();
        $("[type='datepicker']").datepicker();
    },

    maskInputs: function () {
        $(".phones").inputmask("(999) 999-9999");
        $(".zips").inputmask("99999");
        $(".times").inputmask('99:99 AA')
        $('.currency').inputmask({
            alias: 'currency',
            prefix: '$',
            removeMaskOnSubmit: true
        });
        $(".dates").inputmask({
            alias: "datetime",
            placeholder: 'mm/dd/yyyy',
            inputFormat: 'mm/dd/yyyy'
        });
        $('.text').inputmask({
            regex: "[A-Za-z0-9#.,''-() &]+"
        });
        $('.numeric').inputmask({
            alias: 'numeric',
            removeMaskOnSubmit: true
        });

        $('.times').inputmask({
            alias: "datetime",
            placeholder: "HH:MM AM",
            inputFormat: "hh:MM TT",
            insertMode: false,
            showMaskOnHover: false,
            hourFormat: 12
        })
    }


}

globalJS.init();
