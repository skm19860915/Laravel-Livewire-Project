// require('./app');
import helper from './helper';

let historyJS = {

  init: function(){

        console.log("history");
        this.dataTable()

    },

    dataTable: () => {
        const appUrl = helper.getSiteUrl();
        const patient_id = $('#ticketsDatatable').data('patient');
        const dataSource = appUrl + '/patient/tickets/'+patient_id;
        const editApi  = appUrl + '/edit/ticket/'
        $('#ticketsDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: dataSource,
            columns: [
                {
                    data: "action", render: (data, type, row) => {
                        let expandHtml = '';
                        if(data){
                            expandHtml = `
                                <button type="button" class="btn btn-info btn-sm px-1 py-0" data-toggle="collapse" data-target="#collapsed${row.id}">
                                    <i class="fa fa-plus text-white text-120"></i>
                                </button>
                                <div id="collapsed${row.id}" class="collapse bg-white" style="width: auto; font-size:13px;">
                                    Purchased:<br>
                                    <ul class="ml-1">
                                        ${data}
                                    </ul>
                                </div>
                                `;
                        }

                        return expandHtml;
                    }
                },
                {
                    data: "id", render: (v, x, rowData) => {
                        return `<a href="${editApi + v}"
                                   have-products="${rowData.count_product}"
                                   remaining_balance="${rowData.remaining_balance}"
                                   have_refill="${rowData.have_refill}"
                        >${v}</a>`;
                    }
                }, // 0
                { data: "date" }, // 1
                { data: "patient_name"}, // 2
                { data: "user_name"}, // 3
                {
                    data: "total", render: (v) => {
                        let value = parseFloat(v);
                        if (value) return value.toFixed(2);
                        return v;
                }  }, // 4
                {
                    data: "remaining_balance", render: (v) => {
                        let value = parseFloat(v);
                        if (value) return "$"+value.toFixed(2);
                        return v;
                }    }, // 5
            ],
            columnDefs: [{
                targets: 0,
                orderable:false
            }]
        }).on('draw.dt', () => {

            let rows = document.querySelectorAll('#ticketsDatatable td  a[have-products]');
            rows.forEach(row => {
                let haveProduct = parseInt(row.getAttribute('have-products'));
                if (!haveProduct)
                {
                    let tr =  row.parentElement.parentElement
                    tr.classList.add('bg-light-danger');
                }
            })
            rows = document.querySelectorAll('#ticketsDatatable td  a[remaining_balance]');
            rows.forEach(row => {
                let remaining_balance = parseFloat(row.getAttribute('remaining_balance'));
                // console.log(remaining_balance);
                if (remaining_balance == 0)
                {
                    let tr =  row.parentElement.parentElement
                    tr.classList.remove('bg-light-danger');
                    tr.classList.add('bg-light-success');
                }
            })
            rows = document.querySelectorAll('#ticketsDatatable td  a[have_refill]');
            rows.forEach(row => {
                let refill = parseInt(row.getAttribute('have_refill'));
                //console.log(refill);
                if (refill)
                {
                    let tr =  row.parentElement.parentElement
                    tr.classList.remove('bg-light-danger');
                    tr.classList.remove('bg-light-success');
                    tr.classList.add('bg-light-warning');
                }
            })
            // console.log(rows);

            $('.collapse').on('shown.bs.collapse', function (e) {
                let collapseDiv = $(e.target);
                let collapseBtn = collapseDiv.siblings('button');
                collapseBtn.find('i').removeClass('fa-plus').addClass('fa-minus');
            });
            $('.collapse').on('hidden.bs.collapse', function (e) {
                let collapseDiv = $(e.target);
                let collapseBtn = collapseDiv.siblings('button');
                collapseBtn.find('i').removeClass('fa-minus').addClass('fa-plus');
            });

        });
    }
}

historyJS.init();

