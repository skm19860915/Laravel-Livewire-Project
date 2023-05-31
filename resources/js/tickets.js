// require('./app');
import helper from './helper';
import moment from 'moment';
import toastr from 'toastr';
const bootbox = window.bootbox;
let ticket_info = {

    init: function () {
        console.log('tickets.js');

        $(':input').inputmask();

        $('#addNewFormRowForProductsTable').on('click', function () {
            setTimeout(function () {
                ticket_info.calculateTreatmentEndDate();
            }, 500);
        });

        $(document).on('blur', '#products-rows tr td.amount-node input', function (e) {
            ticket_info.calculateTreatmentEndDate();
        });

        $(document).on('blur', '#products-rows tr td.price-node input', function (e) {
            ticket_info.total();
        });

        $(document).on('keyup', '.services_price', function (e) {
            ticket_info.total();
        });

        $('#ticket_date').on('change', function () {
            $("#firstPaymentDueDate").val(moment().add(1, 'month').format('MM/DD/YYYY'));
            ticket_info.calculateTreatmentEndDate();
        });

        $('input:radio[name="treatment_type"]').change( e => {
            //console.log(e.target.value);
            $('#selected_tt_id').val(e.target.value);
        });

        $('#products-rows tr select').change(e => {
            e.stopImmediatePropagation()
            let product_id = e.target.value;
            let products = JSON.parse(document.getElementById('products-rows').dataset.products);
            let product = products.find(p => p.id == product_id)

            let amount = product.amount;
            let price = product.price;

            amount = parseFloat(amount).toFixed(0);
            price = parseFloat(price).toFixed(2);
            const row = e.target.parentNode.parentNode;
            let tds = row.querySelectorAll("td");
            const id = row.id.split('_')[1];

            tds[1].innerHTML = `<input value="${amount}" id="product_${id}_amount" name="product_${id}_amount" class="form-control numeric" />`;
            tds[2].innerHTML = `<input value="${price}"  id="product_${id}_price" name="product_${id}_price"  class="form-control currency" />`;

            $(`body #product_${id}_price`).inputmask({
                alias: 'currency',
                prefix: '$',
                removeMaskOnSubmit: true
            });

            $(`body #product_${id}_amount`).inputmask({
                alias: 'numeric',
                removeMaskOnSubmit: true
            });

            ticket_info.calculateTreatmentEndDate();
            ticket_info.total();
        })

        try {
            $(document).ready(() => {
                const ticket = $(`[data-ticket]`).data('ticket');
                if (ticket) {
                    if (ticket.revisit) {
                        let form = $(".forms");
                        form.find('input').prop('disabled', true)
                        form.find('select').prop('disabled', true)
                        form.find('button').prop('disabled', true)
                    }
                }

                $(`body input.services_price`).inputmask({
                    alias: 'currency',
                    prefix: '$',
                    removeMaskOnSubmit: true
                });
            })

            this.productTable()
            this.onProductsChange()
            this.watch(document.getElementById('products-rows'))

            $("#apdov").keyup(() => {
                ticket_info.total();
            });

            $('.services').change((e) => {
                const service = e.target;

                const inputPrice = service.nextElementSibling;

                inputPrice.value = parseFloat(service.dataset.price).toFixed(2);
                inputPrice.disabled = !inputPrice.disabled
                ticket_info.total()
            })



            this.total();
            this.pay_increments()

            $(() => {

                $(`[id^='delete']`).click(async (e) => {
                    bootbox.confirm({
                        message: `Are you sure you want to delete this product?`,
                        buttons: {
                            cancel: {
                                label: 'Cancel',
                                className: 'btn-secondary'
                            },
                            confirm: {
                                label: 'Yes',
                                className: 'btn-primary'
                            },
                        },
                        callback: async function (result) {
                            let close = this.find('.close');

                            //user want disable
                            if (result) {
                                // let x = await window.confirm("Are you sure?");
                                const row = e.target.parentNode.parentNode;

                                let rows = await row.parentNode;
                                row.remove();

                                let trs = null;
                                if (rows) {
                                    trs = rows.querySelectorAll('tr');
                                }
                                if (!trs.length) {
                                    rows.innerHTML = `
                                        <tr class="bg-light product-table-message">
                                            <td colspan="5">
                                                <small>No sold items dectected, Press the 'Add' button below to get started</small>
                                            </td>
                                        </tr>
                                    `;

                                    let ticketData = JSON.parse(document.getElementById('page_header').dataset.ticket);
                                    let treatmentType = ticketData.treatment_type_id;
                                    $(`treatment_type-${treatmentType}`).prop('checked', true);

                                    $('#recommended_treatment').removeClass('d-none');

                                }

                                ticket_info.calculateTreatmentEndDate();

                                ticket_info.total();;

                            }
                        }
                    })

                })
            })

            $('.submit-buttons').on('click', (e) => {
                e.preventDefault();

                let bal = $('#bdv').val();
                if ($('#payment_increments').val() == '' && bal.replace(/[^\d.-]/g, '') > 0) {
                    bootbox.confirm({
                        message: `There's still a balance of ${bal} on this ticket, are you sure you want to save it?`,
                        buttons: {
                            cancel: {
                                label: 'Cancel',
                                className: 'btn-secondary'
                            },
                            confirm: {
                                label: 'Yes',
                                className: 'btn-primary'
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                let submitBtn = $('#ticket_form').find('.submit-buttons');
                                submitBtn.prop('disabled', true).text('Processing...');
                                ticket_info.submitForm();
                            }
                        }
                    });
                } else {
                    let submitBtn = $('#ticket_form').find('.submit-buttons');
                    submitBtn.prop('disabled', true).text('Processing...');
                    ticket_info.submitForm();
                }
            });



        } catch (e) { }

        this.dataTable()

        const pi = $("[name='payment_increments']");
        pi.ready(async e => {
            let ticket = await pi.data('update');

            if (!ticket) return

            let options = await pi.find(`option[value$='${ticket.month_plan}']`).get(0);

            if (!options) {
                $("#firstPaymentDueDate").val(moment().add(1, 'month').format('MM/DD/YYYY'));
                $('.firstPaymentDue').addClass('d-none');
                return;
            }
            options.selected = true;
            options = $(options);
            if (!options.val()) $('.firstPaymentDue').addClass('d-none');
            if (options.val() == '0,full') $('.firstPaymentDue').addClass('d-none');

        });

        pi.change((e) => {
            let value = e.target.value;
            let firstPaymentDue = $(".firstPaymentDue");
            if (value && value != '0,full') {
                $("#firstPaymentDueDate").val(moment().add(1, 'month').format('MM/DD/YYYY'));
            } else if (value && value === '0,full') {
                $('#apdov').val($('#total').val());
                ticket_info.total();
                return firstPaymentDue.addClass('d-none');
            }
            return firstPaymentDue.removeClass('d-none');
        })
    },

    productTable() {
        const trigger = $("#addNewFormRowForProductsTable");
        const target = $("#products-rows");
        const edit = target.data('edit');

        let firstTime = 1
        trigger.click(() => {
            $('#empty_row').remove();
            const products = target.data('products');
            const storeApi = target.data('store');
            const token = target.data('token');
            let id = Math.random().toString(36).substr(2, 9);
            const deleteBtn = `delete-${id}`;

            $(".product-table-message").remove()
            let options = '';

            $('#recommended_treatment').addClass('d-none');

            if (products.length) {

                products.forEach((p) => {
                    options += `<option value="${p.id}">${p.name}</option>`
                })

                let selectId = `product_${id}_select`;
                const html = `
                  <tr id="product_${id}">
                      <td><select name="product_${id}_select"  id="${selectId}" class='form-control'>${options}</select></td>
                      <td class="amount-node">
                          <input type="text" value="${parseFloat(products[0].amount).toFixed(0)}" id="product_${id}_amount" name="product_${id}_amount" type="text" class="form-control numeric" />
                      </td>
                      <td class="price-node">
                          <input type="text" value="${parseFloat(products[0].price).toFixed(2)}" id="product_${id}_price" name="product_${id}_price" type="text"  class="form-control currency" />
                      </td>

                      <td><a class='link text-primary c-p' id="${deleteBtn}">Delete</a></td>
                  </tr>
              `
                if (!edit) {
                    if (firstTime) target.html(``);
                    firstTime = 0;
                }

                target.append(html);

                $(`body #product_${id}_price`).inputmask({
                    alias: 'currency',
                    prefix: '$',
                    removeMaskOnSubmit: true
                });

                $(`body #product_${id}_amount`).inputmask({
                    alias: 'numeric',
                    removeMaskOnSubmit: true
                });

                $(`#${selectId}`).change(e => {
                    e.stopImmediatePropagation()
                    let product_id = e.target.value;
                    let product = products.find(p => p.id == product_id)

                    let amount = product.amount;
                    let price = product.price;
                    amount = parseFloat(amount).toFixed(0);
                    price = parseFloat(price).toFixed(2);
                    const row = e.target.parentNode.parentNode;
                    let id = row.id.split('_')[1];
                    let tds = row.querySelectorAll("td");

                    let selected = $(tds[0]).find(`select`).val();

                    tds[1].innerHTML = `<input value="${amount}" id="product_${id}_amount" name="product_${id}_amount" type="text" class="form-control numeric" />`;
                    tds[2].innerHTML = `<input value="${price}" id="product_${id}_price" name="product_${id}_price" type="text" class="form-control currency" />`;
                    const total = ticket_info.total()
                    ticket_info.calculateTreatmentEndDate();

                    $(`body #product_${id}_price`).inputmask({
                        alias: 'currency',
                        prefix: '$',
                        removeMaskOnSubmit: true
                    });

                    $(`body #product_${id}_amount`).inputmask({
                        alias: 'numeric',
                        removeMaskOnSubmit: true
                    });
                })
                ticket_info.total()

                $(`#${deleteBtn}`).click(async (e) => {
                    bootbox.confirm({
                        message: `Are you sure you want to delete this product?`,
                        buttons: {
                            cancel: {
                                label: 'Cancel',
                                className: 'btn-secondary'
                            },
                            confirm: {
                                label: 'Yes',
                                className: 'btn-primary'
                            },
                        },
                        callback: async function (result) {
                            if (result) {
                                e.target.parentNode.parentNode.remove();
                                let trs = target[0].querySelectorAll('tr');
                                if (!trs.length) {
                                    target.html(`
                                        <tr class="bg-light product-table-message">
                                            <td colspan="5">
                                                <small>No Sold Items dectected, Press the 'Add' button below to get started</small>
                                            </td>
                                        </tr>`);

                                    $('#recommended_treatment').removeClass('d-none');
                                }

                                ticket_info.calculateTreatmentEndDate();

                                ticket_info.total()
                            }
                        }
                    });

                })
            }
        })
    },

    submitForm: async function () {
        $('.invalid-feedback').remove();
        let form = $('#ticket_form');
        let route = form.data('action');

        var formData = new FormData(document.getElementById('ticket_form'));
        var obj = {};
        var servicesObj = {};
        var productArr = [];

        for (var pair of formData.entries()) {
            if (pair[0] === 'services') {
                let serviceId = pair[1];
                let receivable = $(`#service_${serviceId}`).data('receivable');

                if (receivable) {
                    let servicePrice = $(`#service_${serviceId}_price`).val().replace(/[^\d.-]/g, '');
                    servicesObj[serviceId] = servicePrice;
                } else {
                    servicesObj[serviceId] = 0;
                }
            }
            else if (pair[0].startsWith('product_')) {
                let id = pair[0].split('_')[1];
                let product_id = $(`#product_${id}_select`).val();
                let price = $(`#product_${id}_price`).val();
                let amount = $(`#product_${id}_amount`).val();
                if (price) {
                    price = price.replace(/[^\d.-]/g, '');
                }

                productArr.push({
                    product_id: product_id,
                    custom_amount: amount,
                    custom_price: price,
                });

                formData.delete(`product_${id}_select`);
                formData.delete(`product_${id}_amount`);
                formData.delete(`product_${id}_price`);
            }
            else {
                if (pair[1].includes('$')) {
                    obj[pair[0]] = pair[1].replace(/[^\d.-]/g, '');
                } else {
                    obj[pair[0]] = pair[1];
                }
            }
        }

        if (obj.amount_paid_during_office_visit == '') {
            obj.amount_paid_during_office_visit = '0.00';
        }

        obj['services'] = servicesObj;
        obj['products'] = productArr;

        if (!obj.treatment_end_date) {
            obj.treatment_end_date = $('#treatment_end_date').val();
        }
        if (!obj.total) {
            obj.total = $('#total').val();
        }
        if (!obj.balanc_during_visit) {
            obj.balanc_during_visit = $('#balanc_during_visit').val();
        }
        if (!obj.amount_paid_during_office_visit) {
            obj.amount_paid_during_office_visit = $('#amount_paid_during_office_visit').val();
        }

        obj.treatment_type_id = $('#selected_tt_id').val();

        obj.payment_increments = $('#payment_increments').val();

        let options = {
            method: 'post',
            url: route,
            data: obj,
            xsrfHeaderName: 'X-XSRF-TOKEN',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="token"]').getAttribute('content')
            }
        };

        try {
            let resp = await axios(options);
            //console.log(resp)
            if (resp.status === 200) {
                let str = route.includes('create') ? 'created' : 'updated';
                location.replace(helper.getSiteUrl() + `/tickets?${str}`);
            }
        } catch (e) {
            if (e.response.status === 422) {
                $('.invalid-feedback').remove();
                $.each(e.response.data.errors, function (key, value) {
                    $.each(value, function (k, v) {
                        $('<span class="invalid-feedback d-block" role="alert"><strong>' + v + '</strong></span>').appendTo('#form_errors');
                    });
                });

                $('.submit-buttons').prop('disabled', false).text('Save Changes');
            }
            if (e.response.status === 419) {
                window.history.replaceState(null, '', helper.getSiteUrl() + '/login');
            }
        }
    },

    calculateTreatmentEndDate: function () {
        if ($('#total').val().replace(/[^\d.-]/g, '') < 0) {
            $('#treatment_end_date').val($('#ticket_date').val());
        }
        else {
            let maxDuration = null;
            let durations = [];
            let products = JSON.parse(document.getElementById('products-rows').dataset.products);

            $('#products-rows tr').each(function () {
                let selectedOption = $(this).find('select option:selected').val();
                let product = products.find(p => p.id == selectedOption);
                let productType = null;

                if (product == undefined || product == null) {
                    productType = 'none';
                } else {
                    productType = product.product_type.description;
                }

                if (productType == 'ESWT') {
                    durations.push(3);
                } else {
                    durations.push($(this).find('td.amount-node > input').val());
                }
            });

            maxDuration = Math.max.apply(Math, durations);

            let date = $('#ticket_date').val();
            let momentDate = moment(date, 'MM-DD-YYYY');
            let newDate = momentDate.add(maxDuration, 'months');
            $('#treatment_end_date').val(newDate.format('MM/DD/YYYY'));
        }
    },

    onProductsChange: function () {
        const products = JSON.parse(document.getElementById('products-rows').dataset.products);
    },

    watch: function (target) {
        // Select the node that will be observed for mutations
        const targetNode = target;

        let onProductsChange = this.onProductsChange;
        let total = this.total;

        // Options for the observer (which mutations to observe)
        const config = { attributes: true, childList: true, subtree: true };

        // Callback function to execute when mutations are observed
        const callback = function (mutationsList, observer) {

            onProductsChange()
            let nodes = mutationsList[0].addedNodes;
            nodes.forEach(n => {
                if (n.localName == 'tr') {
                    const products = JSON.parse(document.getElementById('products-rows').dataset.products);
                    let select = $(n).find('select').get(0);
                    let first_option = $(select).find('option').get(0);

                    let product_id = first_option.value;
                    let product = products.find(p => p.id == product_id)

                    let amount = product.amount;
                    let price = product.price;
                    const row = n;
                    let id = n.id.split('_')[1];
                    let tds = row.querySelectorAll("td");

                    tds[1].innerHTML = `<input value="${parseFloat(amount).toFixed(0)}" id="product_${id}_amount" name="product_${id}_amount" type="text" class="form-control numeric" />`;
                    tds[2].innerHTML = `<input value="${parseFloat(price).toFixed(2)}"  id="product_${id}_price" name="product_${id}_price"  type="text" class="form-control currency" />`;

                    $(`body #product_${id}_price`).inputmask({
                        alias: 'currency',
                        prefix: '$',
                        removeMaskOnSubmit: true
                    });

                    $(`body #product_${id}_amount`).inputmask({
                        alias: 'numeric',
                        removeMaskOnSubmit: true
                    });

                    ticket_info.total()
                    $('[id^="delete-"]').click(() => {
                        ticket_info.total()
                    })
                }
            })
        };

        // Create an observer instance linked to the callback function
        const observer = new MutationObserver(callback);

        // Start observing the target node for configured mutations
        observer.observe(targetNode, config);


    }
    ,
    getTotalProducts: function () {
        let rows = document.querySelector('#products-rows').querySelectorAll('tr');

        let totalProduct = 0;
        rows.forEach(async row => {
            let tds = row.querySelectorAll('td');
            let amount, price;

            if (!tds[1] || !tds[2]) return;

            amount = tds[1].querySelector('input').value;
            price = tds[2].querySelector('input').value.replace(/[^\d.-]/g, '');
            amount = parseFloat(amount);
            price = parseFloat(price);

            totalProduct += price;
        })
        return totalProduct;
    },

    getTotalServices: function () {
        let selectedServices = document.querySelectorAll(".services");
        let totalServices = 0;

        selectedServices.forEach(service => {

            let receivable = parseInt(service.dataset.receivable);
            if (!receivable) return;

            let nextElement = service.nextElementSibling;

            if (service.checked) {
                totalServices += parseFloat(nextElement.value.replace(/[^\d.-]/g, ''))
            }
        })
        return totalServices;
    },

    total: async function () {
        const t1 = this.getTotalProducts();
        const t2 = this.getTotalServices();
        const total = t1 + t2;

        $("[name='total']").val(total.toFixed(2));

        let apdov = $("#apdov").val().replace(/[^\d.-]/g, '');

        apdov = (apdov) ? parseFloat(apdov) : 0;

        let bdv_amount = total - apdov;
        bdv_amount = bdv_amount.toFixed(2);

        // console.log('total: ' + total.toFixed(2));
        // console.log('amount paid during visit: ' + apdov);
        // console.log('balance due: ' + bdv_amount);
        $("#bdv").val(bdv_amount);

        if (total == 0) {
            $("#reschedule_cancellation_btn").removeClass('d-none')
        } else {
            $("#reschedule_cancellation_btn").addClass('d-none')
        }

        if (total < 0) {
            ticket_info.calculateTreatmentEndDate();
        }
        this.pay_increments()

        if (total == apdov) {
            $('#payment_increments').val('0,full');
        }

        return total;
    }
    ,
    pay_increments: function () {
        const balance = $("[name='balanc_during_visit']").val().replace(/[^\d.-]/g, '');
        const pi = $("#payment_increments");

        pi.html(`
                <option value="">Select Increments</option>
                <option value="0,full">Paid In Full</option>
        `)
        for (let index = 1; index < 25; index++) {
            const amount = (parseFloat(balance) / index).toFixed(2);
            pi.append(`<option value="${amount},${index}">${index} Month${index > 1 ? 's' : ''} - $${amount}</option>`)
        }
    }
    ,
    dataTable: async () => {

        const appUrl = helper.getSiteUrl();

        const dataSource = appUrl + '/tickets';
        const editApi = appUrl + '/edit/ticket/'

        if (window.location.search.substring(1) === 'created') {
            toastr.success('Ticket created', 'Success', { timeOut: 4000 });
            window.history.replaceState(null, '', dataSource)
        }

        if (window.location.search.substring(1) === 'updated') {
            toastr.success('Ticket updated', 'Success', { timeOut: 4000 });
            window.history.replaceState(null, '', dataSource)
        }

        let td = await $('#ticketsDatatable').DataTable({
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
                { data: "patient_name" }, // 2
                { data: "user_name" }, // 3
                {
                    data: "total", render: (v) => {
                        let value = parseFloat(v);
                        if (value) return "$" + value.toFixed(2);
                        return v;
                    }
                }, // 4
                {
                    data: "remaining_balance", render: (v) => {
                        v = parseFloat(v);
                        v = "$" + v.toFixed(2);
                        return v;
                    }
                }, // 5
            ],
        }).on('draw.dt', () => {

            let rows = document.querySelectorAll('#ticketsDatatable td  a[remaining_balance]');
            rows.forEach(row => {
                let remaining_balance = parseFloat(row.getAttribute('remaining_balance'));

                if (remaining_balance == 0) {
                    let tr = row.parentElement.parentElement
                    tr.classList.remove('bg-light-danger');
                    tr.classList.add('bg-light-success');
                }
            })

            rows = document.querySelectorAll('#ticketsDatatable td  a[have_refill]');
            rows.forEach(row => {
                let refill = parseInt(row.getAttribute('have_refill'));

                if (refill) {
                    let tr = row.parentElement.parentElement
                    tr.classList.remove('bg-light-danger');
                    tr.classList.remove('bg-light-success');
                    tr.classList.add('bg-light-warning');
                }
            })

            rows = document.querySelectorAll('#ticketsDatatable td  a[have-products]');
            rows.forEach(row => {
                let haveProduct = parseInt(row.getAttribute('have-products'));
                if (!haveProduct) {
                    let tr = row.parentElement.parentElement
                    tr.classList.add('bg-light-danger');
                }
            })
        });

    }



}

ticket_info.init();

