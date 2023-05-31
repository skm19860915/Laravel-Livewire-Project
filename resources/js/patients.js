// require('./app');
import helper from './helper';

let patientJS = {

    init: function(){
            this.dataTable();
            this.edit();
            console.log("patients.js");
        },
        formSubmission: function () {

        $('.forms').on('submit', (e)=>{
        let form = $(e.target);
        let submitBtn = form.find('.submit-buttons');
        submitBtn.prop('disabled', true).text('Processing...');
        });

    },

    edit: async function(){

        //Load state dropdown
        helper.loadStates();

        // $( "[type='datepicker']" ).datepicker(); clinics asked to remove datepicker from input field


    },
    dataTable: () => {
        const appUrl = helper.getSiteUrl();
        const dataSource = appUrl + '/patients';
        const overviewPatient = appUrl + '/overview/patient/';
        $('#patientDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: dataSource,
            columns: [
                {
                    data: 'first_name', render: (data, type, row) => {
                        return `<a href="${overviewPatient+row.action}">${data}</a>`;
                } }, // 0
                { data: "home_phone" }, // 1
                { data: "cell_phone" }, // 2
                { data: "email"      }, // 3
                { data: "date_of_birth"}, // 4
                { data: "address"    }, // 5
                { data: "last_activity"},
                {
                    data: "action", render: (data, type, row) => {
                        const editHtml = `
                        <a  title="Edit" href="${appUrl}/edit/patient/${data}" >
                            <i class="fa fa-edit text-blue-m1 text-120"></i>
                        </a>
                        `
                        const deleteHtml = `
                            <a  title="Edit" href="" >
                                <i class="fa fa-trash text-danger text-120"></i>
                            </a>
                        `
                        return editHtml;
                }} //6
            ],
            //
            columnDefs: [
                {
                targets: [6, 7],
                orderable:false
                }
            ]
        });
    }

}

patientJS.init();

