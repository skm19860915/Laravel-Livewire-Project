import helper from './helper';

let zingle = {

    init(){
        this.saveCredentials();
    },

    saveCredentials(){
        $('#zingleForm').on('submit', () => {
            $('button[type="submit"]').prop('disabled', true).text('Connecting to Zingle to verify credentials...');
        });
    }
}

$( () => {
    zingle.init();
})
