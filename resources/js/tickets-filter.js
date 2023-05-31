// require('./app');
import helper from './helper';
import formTable from './form_table';

let ticketFilter = {

  init: function(){
        console.log('ticket Filter.js');



        this.dataTable()



    } ,
    dataTable: () => {
        const appUrl = helper.getSiteUrl();
        const year = $(`[name='year']`).val();
        const month = $(`[name='month']`).val();

        const dataSource = appUrl + `/filter/tickets?year=${year}&month=${month}`;
        const editApi  = appUrl + '/edit/ticket/'
    $('#ticketsDatatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: dataSource,
            columns: [

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
                      v = parseFloat(v);
                      v = "$" + v.toFixed(2);
                      return v;
                }  }, // 4
                {
                    data: "remaining_balance", render: (v) => {
                      v = parseFloat(v);
                      v = "$" + v.toFixed(2);
                      return v;
                }    }, // 5
            ],
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

          rows = document.querySelectorAll('#ticketsDatatable td  a[have_refill]');
          rows.forEach(row => {
              let refill = parseInt(row.getAttribute('have_refill'));
              // console.log(refill);
              if (refill)
              {
                  let tr =  row.parentElement.parentElement
                  tr.classList.remove('bg-light-danger');
                  tr.classList.remove('bg-light-success');
                  tr.classList.add('bg-light-warning');
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
            // console.log(rows);
        });
  }



}

ticketFilter.init();

