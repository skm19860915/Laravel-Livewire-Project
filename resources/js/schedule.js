// require('./app');
import helper from './helper';
import {Calendar} from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import alertify from 'alertifyjs';
import moment from 'moment';
import axios from 'axios';
const bootbox =  window.bootbox;

var _blocks = [];

let schedule = {
    
    init: function() {
        this.runCalendar()
        this.togglePatient()
        this.formSubmission()
        this.deleteAppointment()

        $(() => {

            if($('#date_appointment').val()) {
                let date = $('#date_appointment').val();
                schedule.getBlocks(date);
            }
            // start select 2
            $('#current_patient').select2({
              placeholder: "Please enter 1 or more character",
              minimumInputLength: 1,
              ajax: {
                url: helper.getSiteUrl()+'/ajax-search-patients',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    searchTerm: params.term // search term
                  };
                 },
                 processResults: function (response) {
                    return {
                      results: $.map(response, function (item) {

                        if(item.address){
                          item.address+=',';
                        }else{
                          item.address = '';
                        }
                        if(item.city){
                          item.city+=',';
                        }else{
                          item.city = '';
                        }
                        if(!item.state){
                          item.state = '';
                        }
                        if(item.zip){
                          item.zip = ', '+item.zip;
                        }else{
                          item.zip = '';
                        }

                        return {
                            text: `${item.first_name}, ${item.last_name} - ${item.address} ${item.city} ${item.state}${item.zip}`,
                            id: item.id
                        }
                      })
                    } ;
                 },
                 cache: true
              }
            });
        })
        console.log("schedule.js");
        alertify.set('notifier', 'position', 'top-right');
        alertify.set('notifier','delay', 50);


        $('#date_appointment').on('change', function(e) {
            $('#time_appointment').val('HH:MM AM');
            let date = e.target.value;
            schedule.getBlocks(date);
            
        });

        $('#block_form input').on('blur', function(){
            if(schedule.blockFormIsValid()) {
                $('#block_submit').prop('disabled', false);
            }
        });

        $('.delete-block').on('click', function(){
            let id = $('.delete-block').attr('id').split('_')[2];
            let token = $("[name='token']").attr('content');
            axios.post(helper.getSiteUrl()+`/block/delete/${id}`,{token});
            window.location.replace(helper.getSiteUrl()+`/schedule`);
        });

        $("#time_appointment").on('keyup', function(e) {
            let time = e.target.value;
            if(!time) {
                time = 'HH:MM A';
            }
            let timeArr = time.split(' ')
            let hm= timeArr[0].split(':');
            let h = hm[0];
            let m = hm[1];
            let mode = timeArr[1]

            let t00 = m.indexOf('00');
            let t15 = m.indexOf('15');
            let t30 = m.indexOf('30');
            let t45 = m.indexOf('45');
            let all = t00 + t15 + t30 + t45;
            if(all == -4 && m.indexOf('M') == -1 && h.indexOf('H') == -1) //a valid time exists, but it's not 15,30,45, or 00.
            {
                let reset = `${h}:MM ${mode}`;
                e.target.value=reset;
                $('[name="time_appointment"]').addClass('is-invalid');
                $('[name="time_appointment"]').removeClass('valid');
                alertify.dismissAll();
                alertify.error(`Minutes must be multiple of 15.`);
            }
            else {
                if(m.indexOf('M') == -1 && h.indexOf('H') == -1) //we already know the time is right, and this is checking that there are no H or Ms in the input.
                {
                    if(schedule.hasBlockConflict(e.target.value)) {
                        $('[name="time_appointment"]').addClass('is-invalid');
                        $('[name="time_appointment"]').removeClass('valid');
                        alertify.dismissAll();
                        alertify.error(`The calendar is blocked during this time.`);
                    } else {
                        alertify.dismissAll();
                        $('[name="time_appointment"]').addClass('is-valid');
                        $('[name="time_appointment"]').removeClass('is-invalid');  
                    }
                } else if (m.indexOf('M') !== -1 || h.indexOf('H') !== -1) {
                    $('[name="time_appointment"]').addClass('is-invalid');
                    $('[name="time_appointment"]').removeClass('valid');
                    alertify.dismissAll();
                    alertify.error(`Please choose a valid time.`);
                }
            }
        });

        this.reorder();
        this.preventSmsConfirmation();
    },

    deleteAppointment: function ()
    {
        $(() => {

            const deleteAppointment = $("#deleteAppointment");
            if (deleteAppointment)
            {

                deleteAppointment.click((e) => {
                    const type = deleteAppointment.prop('type');
                    if (type == 'button')
                    {
                        bootbox.confirm({
                            message: 'Are you sure you wish to delete this appointment ?',
                            buttons: {
                                cancel: {
                                    label: 'Yes',
                                    className: 'btn-primary'
                                },
                                confirm: {
                                    label: 'Cancel',
                                    className: 'btn-secondary'
                                },
                            },
                            callback: async function (result) {
                                if(!result)
                                {
                                    deleteAppointment.prop('type', 'submit').trigger('click');
                                }
                        }})
                        // const x = window.confirm('Are you sure you wish to delete this appointment ?')
                        // if (x) {
                        //     deleteAppointment.prop('type', 'submit').trigger('click');
                        // }
                    }
                })
            }
        })
    },


    formSubmission: function () {
        $('.forms').on('submit', (e) => {
            
            if($('input.is-invalid').length > 0) {
                alertify.error('Correct errors before submitting.');
                return false;
            }

            let form = $(e.target);
            let currentOrnew = e.target.currentOrnew ? e.target.currentOrnew.value : false ;

            const reorder = e.target.reorder ? e.target.reorder.value : false;
            if ( ! reorder) {
                alertify.error('Please select whether this appointment is a reorder.');
                return false;
            }

            if (currentOrnew == 'new') {
                let firstname = form.find(`[name='first_name']`);
                let lastname = form.find(`[name='last_name']`);
                let city = form.find(`[name='city']`);
                let marketingSource = form.find(`[name='how_did_hear_about_clinic']`);
                ///filter values
                let  fnameHasNumber = /\d/.test(firstname.val());
                let  lnameHasNumber = /\d/.test(lastname.val());
                let  cityHasNumber = /\d/.test(city.val());
                //show messages
                let stop = 0;
                if (!marketingSource.val()) {
                    stop = 1
                    e.preventDefault()
                    marketingSource.addClass('is-invalid')
                    alertify.error('Marketing source is required.');
                }else{
                    marketingSource.removeClass('is-invalid')
                    marketingSource.addClass('is-valid')
                }

                if (city.val() && cityHasNumber) {
                    stop = 1
                    e.preventDefault()
                    city.addClass('is-invalid')
                    alertify.error('City should not include numbers.');
                }else{
                    city.removeClass('is-invalid')
                    city.addClass('is-valid')

                }
                /*if( !city.val()){
                    stop = 1
                    e.preventDefault()
                    city.addClass('is-invalid')
                    alertify.error('City is required.');
                }else{

                    if (cityHasNumber) {
                        stop = 1
                        e.preventDefault()
                        city.addClass('is-invalid')
                        alertify.error('City should not include numbers.');
                    }else{
                        city.removeClass('is-invalid')
                        city.addClass('is-valid')

                    }
                }*/

                if( !lastname.val()){
                    stop = 1
                    e.preventDefault()
                    lastname.addClass('is-invalid')
                    alertify.error('Last name required.');

                }else{
                    if (lnameHasNumber) {
                        stop = 1
                        e.preventDefault()
                        lastname.addClass('is-invalid')
                        alertify.error('Last name should not include numbers.');
                    }else{
                        lastname.removeClass('is-invalid')
                        lastname.addClass('is-valid')

                    }
                }
                if(!firstname.val()){
                    stop = 1
                    e.preventDefault()
                    firstname.addClass('is-invalid')
                    alertify.error('First name is required.')
                }else{

                    if (fnameHasNumber) {
                        stop = 1
                        e.preventDefault()
                        firstname.addClass('is-invalid')
                        alertify.error('First name should not include numbers.');
                    }else{
                        firstname.removeClass('is-invalid')
                        firstname.addClass('is-valid')

                    }
                }

                if(stop)
                {
                    return;
                }
            }else if(currentOrnew == 'current'){
                let patient  = form.find(`[name='current_patient']`);
                let vpatient= patient.val();
                let date = form.find(`[name='date_appointment']`);
                let vdate= date.val();
                let time = form.find(`[name='time_appointment']`);
                let vtime= time.val();
                let appt_type = form.find(`[name='appointment_type']`);
                let vappt_type= appt_type.val();

                if(!vpatient){
                    e.preventDefault();
                    $('.select2-selection.select2-selection--single').css({'border-color':'red'});
                    alertify.error('Patient is required');
                }else{
                $('.select2-selection.select2-selection--single').css({'border-color':'#00addd'});
                }
                if(!vdate){
                    e.preventDefault();
                    date.addClass('is-invalid');
                    alertify.error('Date is required');
                }else{
                    date.removeClass('is-invalid');
                }
                if(!vtime){
                    e.preventDefault();
                    time.addClass('is-invalid');
                    alertify.error('Time is required');
                }else{
                    time.removeClass('is-invalid');
                }
                if(!vappt_type){
                    e.preventDefault();
                    appt_type.addClass('is-invalid');
                    alertify.error('Appointment is required');
                }else{
                    appt_type.removeClass('is-invalid');

                }

                if(!vpatient || !vdate || !vtime || !appt_type)
                {

                    return;
                }
                // e.preventDefault();
                // return
            }
            const time = e.target?.time_appointment?.value;
            if(time)
            {
                {
                    let t00 = time.indexOf(':00');
                    let t15 = time.indexOf(':15');
                    let t30 = time.indexOf(':30');
                    let t45 = time.indexOf(':45');
                    let all = t00 + t15 + t30 + t45;
                    let h = time.substring(0,2);
                    if(all == -4)
                    {
                        e.preventDefault();
                        alertify.error(`Minutes must be multiple of 15.`);
                        return;
                    }
                }
            }

            
            let submitBtn = form.find('.submit-buttons');
            submitBtn.prop('disabled', true).text('Processing...');
            
    });

  },

 

 

  async runCalendar() {

    /* initialize the calendar
     ----------------------------------------------------------------- */
    //Date for the calendar events (dummy data)


    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;
    let plugins = {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        headerToolbar: {left: 'prev,next today',center: 'title',right: 'dayGridMonth,timeGridWeek,timeGridDay'},
        themeSystem: 'bootstrap'
    }
    const _token = $("[name='token']").attr('content');
    var calendar = new Calendar(calendarEl, {
        ...plugins,
        events: async function (date) {
            const table = $(".fc-view-harness.fc-view-harness-active");
            table.append(`
            <div class="loader">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            `)
            let {start,end} = date ;
            start = moment(start).format('YYYY-MM-DD');
            end = moment(end).format('YYYY-MM-DD');
            let res = await $.post(helper.getSiteUrl()+'/get/appts',{_token,start,end});
            let events = await res.appts.map(appt => schedule.appts(appt));
            let blocks = await res.blocks.map(block => schedule.blocks(block));

            for(var i of blocks) {
                events.push(i);
            }
            $(".loader").remove();
        
            return events;
         },
         lazyFetching :false,
         eventDidMount: async function(info){
              let event = info.event['_def'];
              let el = info.el;
              let eventTitle = el.querySelector('.fc-event-title');              
              let detail = event.extendedProps.detail;

              // defualt background
              //el.classList.add('appt.new')
              // default padding
              el.classList.add('pl-2')
              el.classList.add('pr-2')
              // default padding
              eventTitle.classList.add('w-100')
              // default text color
              eventTitle.classList.add('text-white')
              /**
            //       .appt-success
            //       .appt-confirm-no-show
            //       .appt-cancelled
            //       .appt-reschedule
            //       .appt-voicemail
            //       .appt-no-sales
            //     */
              //if(patient.schedule_type.toLowerCase() == 'Rescheduled'.toLowerCase()) eventTitle.classList.add('bg-warning')
              if(detail.schedule_type_id == 5 ) el.classList.add('appt-reschedule')
              else if(detail.schedule_type_id == 4 ) el.classList.add('appt-cancelled')
              else if(detail.schedule_type_id == 6 ) el.classList.add('appt-voicemail')
              else if(detail.schedule_type_id == 3 ) el.classList.add('appt-success')
              else if(detail.schedule_type_id == 7) el.classList.add('appt-no-sales')
              else if(detail.schedule_type_id == 8) el.classList.add('appt-confirm-no-show')
              else if(detail.schedule_type_id == 9) el.classList.add('appt-block')
              else el.classList.add('appt-new')

              if (detail.new)
              {
                  let content  = eventTitle.textContent;
                  eventTitle.innerHTML = `<span class='d-flex align-items-center'>${content}<i class="fas fa-star-of-life"></i></span>`
              }

              if (detail.appointment_type == "Procedure")
              {
                  eventTitle.insertAdjacentHTML('beforeend', ` <i class="fas fa-plus-square"></i> `)

              }
              if (detail.appointment_type == "Other")
              {
                  eventTitle.insertAdjacentHTML('beforeend', ` <i class="far fa-circle"></i> `)

              }
          }


        } );
     calendar.render();

    },

  appts(appt){
    let time24 = this.get24hTime(appt.time);
    let endTime = moment(appt.time, 'hh:mm A').add(30, 'minutes');
    let endTime24 = this.get24hTime(endTime.utcOffset(0, true).format('hh:mm A'));
    let start  = `${appt.date}T${time24}`;
    let end = `${appt.date}T${endTime24}`;
    let time = appt.time.split(' ').join('').slice(0,-1).replace(':00','').toLowerCase();
    let url = helper.getSiteUrl() + '/edit/appointment/' + appt.id;
     let total = '';
     if(parseInt(appt.ticket_count) !=0) total = appt.total?"$"+parseFloat(appt.total).toFixed(2):'';

    let title = `${time} ${appt.last_name}, ${appt.first_name} ${total}`;

    let detail = appt;
    let event = {
        title,
        start,
        end,
        detail,
        url,
     }

     return  event;
  },


  blocks(block) {
    let date = block.date
    
    let startTime24 = this.get24hTime(block.start_time);
    let endTime24 = this.get24hTime(block.end_time);

    let start = `${date}T${startTime24}`;
    let end = `${date}T${endTime24}`;
    let startTime = block.start_time.split(' ').join('').slice(0,-1).replace(':00','').toLowerCase();
    let endTime = block.end_time.split(' ').join('').slice(0,-1).replace(':00','').toLowerCase();
    let url = helper.getSiteUrl() + '/block/edit/' + block.id;
    
    let title = `${startTime} - ${endTime} ${block.description}`;
    let detail = block;
    let event = {
        title,
        start,
        end,
        detail,
        url,
     }
     return event;
  },

    get24hTime(str){
        str = String(str).toLowerCase().replace(/\s/g, '');
        var has_am = str.indexOf('am') >= 0;
        var has_pm = str.indexOf('pm') >= 0;
        // first strip off the am/pm, leave it either hour or hour:minute
        str = str.replace('am', '').replace('pm', '');
        // if hour, convert to hour:00
        if (str.indexOf(':') < 0) str = str + ':00';
        // now it's hour:minute
        // we add am/pm back if striped out before
        if (has_am) str += ' am';
        if (has_pm) str += ' pm';
        // now its either hour:minute, or hour:minute am/pm
        // put it in a date object, it will convert to 24 hours format for us
        var d = new Date("1/1/2011 " + str);
        // make hours and minutes double digits
        var doubleDigits = function(n){
            return (parseInt(n) < 10) ? "0" + n : String(n);
        };
        return doubleDigits(d.getHours()) + ':' + doubleDigits(d.getMinutes());
    }
    ,
    togglePatient() {
        $(() => {
        const currentOrnew = $("input[name='currentOrnew']");

        /// on document load
        const  value = currentOrnew.val();
            if (value == 'current')
            {
                $("#new-patient").addClass('d-none');
                $("#current-patient").removeClass('d-none');
            } else {
                $("#new-patient").removeClass('d-none');
                $("#current-patient").addClass('d-none');
            }

        // on value change
        currentOrnew.click(e => {
            const value = e.target.value;
            if (value == 'current')
            {
                $("#new-patient").addClass('d-none');
                $("#current-patient").removeClass('d-none');
            } else {
                $("#new-patient").removeClass('d-none');
                $("#current-patient").addClass('d-none');
            }
        });
    })
 },

    reorder() {
        $('#reorderTooltip').on('click', ()=> {
            alertify.notify(
                'A reorder won\'t have an appointment confirmation sent to the patient (only applies if Zingle is enabled).',
                'custom',
                5,
                ()=> {}
            );
        });
    },

    preventSmsConfirmation() {
        $('#preventSMSConfirmationTooltip').on('click', ()=> {
            alertify.notify(
                'Check this to prevent Zingle from sending an SMS confirmation to the patient (only applies if Zingle is enabled).',
                'custom',
                5,
                ()=> {}
            );
        });
    },

    getBlocks(date) {
        let start = moment(date).format('YYYY-MM-DD');
        let end = moment(date).format('YYYY-MM-DD');
        let token = $("[name='token']").attr('content');
        let settings = {
            method: 'POST',
            url: helper.getSiteUrl()+'/get/blocks',
            data: {token, start, end},
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="token"]').getAttribute('content')
            },
            xsrfHeaderName: 'X-XSRF-TOKEN',
        };
    
        axios(settings).then(resp => {
            if(resp.status === 200) {
                _blocks = resp.data.map(block => schedule.blocks(block));
            }     
        }).catch(err => {
            console.error(err);
        }); 
       
    },

    hasBlockConflict(apptTime) {
        let apptDate = moment($('#date_appointment').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
        let inputTime = moment(apptTime, 'hh:mm A');
        let time24 = this.get24hTime(inputTime.utcOffset(0, true).format('hh:mm A'));
        let inputDateTime  = moment(`${apptDate}T${time24}`, 'YYYY-MM-DDTHH:mm');

    
        for(var i of _blocks) {
            let blockStart = moment(i.start, 'YYYY-MM-DDTHH:mm');
            let blockEnd = moment(i.end, 'YYYY-MM-DDTHH:mm');
            
            if(inputDateTime.isBetween(blockStart, blockEnd) || inputDateTime.isSame(blockStart)) {
                return true;
            } 
        }
        return false;
    },

    blockFormIsValid() {
        $('#block_form input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        let valid = true;
        let rsn = document.getElementById('block_reason');
        let date = document.getElementById('block_date');
        let start = document.getElementById('block_start');
        let end = document.getElementById('block_end');
        let mDate = moment(date.value, 'MM/DD/YYYY');
        let mStart = moment(start.value, 'hh:mm A');
        let mEnd = moment(end.value, 'hh:mm A');

        if(rsn.value == '') {
            rsn.classList.add('is-invalid');
            $('<span class="invalid-feedback d-block" role="alert"><strong>Reason is required</strong></span>').insertAfter('#block_reason');
            valid = false;
        }

        if(!mDate.isValid()){
            date.classList.add('is-invalid');
            $('<span class="invalid-feedback d-block" role="alert"><strong>Please enter a valid date</strong></span>').insertAfter('#block_date');
            valid = false;
        }

        if(!mStart.isValid()) {
            start.classList.add('is-invalid');
            $('<span class="invalid-feedback d-block" role="alert"><strong>Please enter a valid time</strong></span>').insertAfter('#block_start');
            valid = false;
        }

        if(!mEnd.isValid()) {
            end.classList.add('is-invalid');
            $('<span class="invalid-feedback d-block" role="alert"><strong>Please enter a valid time</strong></span>').insertAfter('#block_end');
            valid = false;
        } else if(mEnd.isSameOrBefore(mStart)) {
            end.classList.add('is-invalid');
            $('<span class="invalid-feedback d-block" role="alert"><strong>End time must be greater than start time.</strong></span>').insertAfter('#block_end');
            valid = false;
        }

        if(!valid) return false;

        $('#block_form input').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        return true;
        
    }
}

schedule.init();

