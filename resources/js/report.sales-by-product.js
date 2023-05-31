// require('./app');
import moment from 'moment';
import helper from './helper';

let financeJS = {

  init: function(){
        
        $('.row_icon').on('click', function(e){
            e.preventDefault();
            let row = $(this).closest('tr').attr('id');
            let arr = row.split('_');
            let childrenRows = '.' + arr[0] + '_children_' + arr[1];

            if($(childrenRows).hasClass('d-none')) {
                $(childrenRows).removeClass('d-none');
                $('#' + row).find('.fa').removeClass('fa-plus-square').addClass('fa-minus-square');
            } else {
                $(childrenRows).addClass('d-none');
                $('#' + row).find('.fa').removeClass('fa-minus-square').addClass('fa-plus-square');
            }
            
        });

        $('.expand_totals').on('click', function(e){
            e.preventDefault();
            let row = $(this).closest('tr').attr('id');

            if($('.product_totals_detail').hasClass('d-none')) {
                $('.product_totals_detail').removeClass('d-none');
                $('#' + row).find('.fa').removeClass('fa-plus-square').addClass('fa-minus-square');
            } else {
                $('.product_totals_detail').addClass('d-none');
                $('#' + row).find('.fa').removeClass('fa-minus-square').addClass('fa-plus-square');
            }
            
        });

        $('#export').on('click', function(e) {
            let url = helper.getSiteUrl()+`/export/sales-by-product`;

            let start = $("#daterange").data('start');
            let end = $("#daterange").data('end');
            start = moment(start).format('YYYY-MM-DD');
            end =  moment(end).format('YYYY-MM-DD');

            const _token  = $('meta[name=token]').attr('content');

            
            // var formData = new FormData();
            // formData.append('start', _start);
            // formData.append('end', _end);
            // formData.append('_token', _token);
            // let request = new XMLHttpRequest();
            // request.open('POST', url);
            // request.send(formData);      

            let form = document.createElement('form');
            let from = document.createElement('input');
            let to = document.createElement('input');
            let token = document.createElement('input');
            //assign inputs values
            from.setAttribute('value', start);
            to.setAttribute('value', end);
            token.setAttribute('value', _token);
            // assign inputs names
            from.setAttribute('name', 'from');
            to.setAttribute('name', 'to');
            token.setAttribute('name', '_token');
            //assign inputs to form
            form.appendChild(from);
            form.appendChild(to);
            form.appendChild(token);
            form.setAttribute('action', url)
            form.setAttribute('method', "POST")
            //assign from to document
            document.body.appendChild(form);
            //sumbit form
            form.submit();
        });

    
        $(async () => {
            var start = moment().subtract(29, 'days');
            var end = moment();
            var x = 0;
            async function cb(start, end) {
                let _start = await $("#daterange").data('start');
                let _end = await  $("#daterange").data('end');
                _start = moment(_start).format('M/D/YYYY');
                _end =  moment(_end).format('M/D/YYYY');
                $('#reportrange span').html(_start + ' - ' + _end);
                $("#daterange-from-to").find(`[name='to']`).val(_start);
                $("#daterange-from-to").find(`[name='from']`).val(_end);

                if (!x) { x += 1;} else {
                    const action =  helper.getSiteUrl()+`/reports/sales-by-product`;                    
                    const _token  = $('meta[name=token]').attr('content');
                    //when proccess payment clicked

                    let form = document.createElement('form');
                    let from = document.createElement('input');
                    let to = document.createElement('input');
                    let token = document.createElement('input');
                    //assign inputs values
                    from.setAttribute('value',start.format('YYYY-M-D'));
                    to.setAttribute('value', end.format('YYYY-M-D'));
                    token.setAttribute('value', _token);
                    // assign inputs names
                    from.setAttribute('name', 'from');
                    to.setAttribute('name', 'to');
                    token.setAttribute('name', '_token');
                    //assign inputs to form
                    form.appendChild(from);
                    form.appendChild(to);
                    form.appendChild(token);
                    form.setAttribute('action', action)
                    form.setAttribute('method', "POST")
                    //assign from to document
                    document.body.appendChild(form);
                    //sumbit form
                    form.submit();
                };
            }

            const html = `
            <li class='c-p' id="daterange-from-to">
            <div
                class="d-flex align-items-center justify-content-between fz-12px w-270px   my-2">
                <div>
                    <span class="text-muted">FROM</span>
                    <br>
                    <input readonly class="form-control  w-111px" name="to" value="${moment().format('M/D/YYYY')}"/>
                </div>
                <div>
                    <span class="text-muted">to</span>
                    <br>
                    <input readonly class="form-control  w-111px" name="from" value="${moment().add(1,'M').format('M/D/YYYY')}"/>
                </div>
            </div>
            </li>
            `

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [ moment().subtract(30, 'days'),moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                },
                alwaysShowCalendars:false,
            }, cb)
            $(`.daterangepicker  ul`).addClass('max-content');
            $(`.daterangepicker  [data-range-key="Last Month"]`).after(html);

            cb(start, end);
            
            $("#daterange-from-to").click(() => {
                $(`.daterangepicker  [data-range-key="Custom Range"]`).trigger("click");
                $(`.daterangepicker  ul [data-range-key]`).removeClass('active');

            });           
        })

        



  },



}

financeJS.init();

