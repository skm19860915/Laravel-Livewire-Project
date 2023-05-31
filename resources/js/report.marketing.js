// require('./app');
import moment from 'moment';
import helper from './helper';

let reportMarketingJS = {

  init: function(){
        console.log(`finance.js`);

        $(async () => {
            var start = $('#daterange').data('start');
            var end   = $('#daterange').data('end');
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
                    console.log(x);
                    const action  = helper.getSiteUrl()+`/reports/marketing`;
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
                                <input readonly class="form-control  w-111px" name="to" value="${start}"/>
                            </div>
                            <div>
                                <span class="text-muted">to</span>
                                <br>
                                <input readonly class="form-control  w-111px" name="from" value="${end}"/>
                            </div>
                        </div>
                        </li>
                        `
                // console.log(start);
                // console.log(end);

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
            cb(start,end)
            $(`.daterangepicker   ul`).addClass('max-content');
            $(`.daterangepicker   [data-range-key="Last Month"]`).after(html);
            $(`.daterangepicker   ul [data-range-key]`).removeClass('active');
            $(`.daterangepicker   ul [data-range-key="This Month"]`).addClass('active');
            // cb(start, end)
            $("#daterange-from-to").click(() => {
                $(`.daterangepicker  [data-range-key="Custom Range"]`).trigger("click");
                $(`.daterangepicker   ul [data-range-key]`).removeClass('active');
                $(`.daterangepicker  ul [data-range-key]`).last().addClass('active');

            })
        })



  },



}

reportMarketingJS.init();

