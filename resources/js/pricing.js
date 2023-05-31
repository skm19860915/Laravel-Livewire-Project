import formTable from './form_table';

let products = {

    init: function () {

        $(':input').inputmask();

        const params2 = {
            trigger: document.getElementById('addNewFormRowForServicesTable'),
            tbody: document.getElementById('services-rows'),
            cols: { name: 'text', price: 'number', receivable: "checkbox", description: 'text', note: "text" },
            saveLink: true,
            action: true
        }
        // new formTable(params);
        new formTable(params2);

        $('#addProduct').on('click', function () {
            $('#new_product').show();
        })

        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('click', '.edit', function () {
            let id = $(this).attr('id').split('-')[1];
            products.formifyRow(id);
        });

        $(document).on('click', '.disable', function () {
            let id = $(this).attr('id').split('-')[1];
            products.disableProduct(id);
        });

        $('.cancel').on('click', function () {
            let id = $(this).attr('id').split('-')[1];
            products.cancel(id);
        });

        $('.save').on('click', function () {
            let id = $(this).attr('id').split('-')[1];
            if (products.validateForm(id)) {
                products.saveProduct(id);
            }
        });

        $(document).on('click', '.restore', function () {
            let id = $(this).attr('id').split('-')[1];
            products.restoreProduct(id);
        });

        $(document).on('focus', 'input.invalid-feedback-border, select.invalid-feedback-border', function () {
            $(this).removeClass('invalid-feedback-border');
            $(this).next('span.form-error').remove();
        });
    },

    validateForm: function (id = null) {
        let isValid = true;
        if (!id) {
            $('#new_product input.required').each(function () {
                if ($(this).val() == '') {
                    isValid = false;
                    $(this).addClass('invalid-feedback-border');
                    $('<span class="text-danger form-error"><small>Required</small></span>').insertAfter($(this));
                }
            });
        }
        if ($('input[name="name-' + id + '"]').val() == '') {
            isValid = false;
            $('input[name="name-' + id + '"]').addClass('invalid-feedback-border');
        }

        if ($('input[name="amount-' + id + '"]').val() == '') {
            isValid = false;
            $('input[name="amount-' + id + '"]').addClass('invalid-feedback-border');
        }

        if ($('input[name="price-' + id + '"]').val() == '') {
            isValid = false;
            $('input[name="price-' + id + '"]').addClass('invalid-feedback-border');
        }

        if ($('#product_type_select-' + id + ' option:selected').val() == '0') {
            isValid = false;
            $('#product_type_select-' + id).addClass('invalid-feedback-border');
        }

        if (isValid == false) {
            $('<span class="text-danger form-error"><small>Required</small></span>').insertAfter('.invalid-feedback-border');
        }

        return isValid;
    },

    formifyRow: function (id = null) {

        //const productTypes = JSON.parse(document.getElementById('products-rows').dataset.productTypes);

        $('tr#' + id + ' td.editable input').prop('readonly', false);
        $('tr#' + id + ' select.product_type_select').prop('disabled', false);

        $('tr#' + id + ' td.actions .edit-disable-btns').hide();
        $('tr#' + id + ' td.actions .save-cancel-btns').show();
    },

    cancel: function (id = null) {
        $(':input.form-control').each(function (idx, el) {
            let id = $(el).attr('id');
            let originalValue = document.getElementById(id).dataset.originalValue;
            $(this).val(originalValue);
        });

        $('tr#' + id + ' td.editable input').prop('readonly', true);
        $('tr#' + id + ' select.product_type_select').prop('disabled', true);

        $('tr#' + id + ' td.actions .edit-disable-btns').show();
        $('tr#' + id + ' td.actions .save-cancel-btns').hide();
    },

    saveProduct: async function (id = null) {
        let route = '';

        if (id == 'new') {
            route = document.getElementById('new_product').dataset.store;
        } else {
            route = document.getElementById(id).dataset.update;
        }

        if (id == 'new') {
            var formData = new FormData(document.getElementById('form-new'));
            formData.append('product_type_id', $('#product_type_select option:selected').val());
        } else {
            var formData = new FormData(document.getElementById('form-' + id));
            formData.append('product_type_id', $('#product_type_select-' + id + ' option:selected').val());
        }
        var obj = {};

        for (let [key, value] of formData.entries()) {
            let newKey = key.split('-')[0];
            obj[newKey] = value.replace('$', '').replace(',', '');
            console.log(obj[newKey]);
        }

        let options = {
            method: 'post',
            url: route,
            data: obj,
            xsrfHeaderName: 'X-XSRF-TOKEN',
        };

        try {
            let resp = await axios(options);
            if (id !== 'new') {
                $('tr#' + id + ' td.editable input').prop('readonly', true);
                $('tr#' + id + ' select.product_type_select').prop('disabled', true);

                $('tr#' + id + ' td.actions .edit-disable-btns').show();
                $('tr#' + id + ' td.actions .save-cancel-btns').hide();
            } else {
                products.addNewRow();
            }
        }
        catch (e) {
            if (e.response.status === 422) {
                $('.invalid-feedback').remove();
                $.each(e.response.data.errors, function (key, value) {
                    $.each(value, function (k, v) {
                        $('<span class="invalid-feedback d-block" role="alert"><strong>' + v + '</strong></span>').insertAfter('#' + key);
                    });
                });
                $('.invalid-feedback').show();
            } else {
                console.error(e.response);
            }
        }
    },

    disableProduct: async function (id) {
        const route = document.getElementById(id).dataset.delete;

        let options = {
            method: 'post',
            url: route,
            xsrfHeaderName: 'X-XSRF-TOKEN',
        };

        try {
            let resp = await axios(options);
            if (resp.status === 200) {
                $('#status-' + id + ' span.badge').removeClass('badge-success').addClass('badge-danger').text('Disabled');
                $('#disable-' + id).replaceWith(`<a role="button" id="restore-${id}" class="restore text-success" data-toggle="tooltip" data-placement="top" title="Restore Product"><span class="material-icons md-18">power_settings_new</span></a>`);
                $('[data-toggle="tooltip"]').tooltip();
            }
        }
        catch (e) {
            if (e.response.status === 422) {
                $('.invalid-feedback').remove();
                $.each(e.response.data.errors, function (key, value) {
                    $.each(value, function (k, v) {
                        $('<span class="invalid-feedback d-block" role="alert"><strong>' + v + '</strong></span>').insertAfter('#' + key);
                    });
                });
                $('.invalid-feedback').show();
            } else {
                console.error(e.response);
            }
        }
    },

    restoreProduct: async function (id) {
        const route = document.getElementById(id).dataset.restore;

        let options = {
            method: 'post',
            url: route,
            xsrfHeaderName: 'X-XSRF-TOKEN',
        };

        try {
            let resp = await axios(options);
            if (resp.status === 200) {
                $('#status-' + id + ' span.badge').removeClass('badge-danger').addClass('badge-success').text('Active');
                $('#restore-' + id).replaceWith(`<a role="button" id="disable-${id}" class="disable text-success" data-toggle="tooltip" data-placement="top" title="Disable Product"><span class="material-icons md-18">power_settings_new</span></a>`);
                $('[data-toggle="tooltip"]').tooltip();
            }
        }
        catch (e) {
            if (e.response.status === 422) {
                $('.invalid-feedback').remove();
                $.each(e.response.data.errors, function (key, value) {
                    $.each(value, function (k, v) {
                        $('<span class="invalid-feedback d-block" role="alert"><strong>' + v + '</strong></span>').insertAfter('#' + key);
                    });
                });
                $('.invalid-feedback').show();
            } else {
                console.error(e.response);
            }
        }
    },

    addNewRow: function () {
        location.reload();
    }

}

products.init();
