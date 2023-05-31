// require('./app');
import helper from './helper';
import formTable from './form_table';

let ticket_info = {

    init: function () {
        console.log('ticket_info.js');

        const params = {
            trigger: document.getElementById('addNewFormRowForProductsTable'),
            tbody: document.getElementById('products-rows'),
            cols: { products: 'select', amount: 'number', price: 'number' },
            saveLink: false,
            cancelBtn: true,
            clearTrFirstTime: true,
        }
        new formTable(params);
        this.onProductsChange()
        this.watch(document.getElementById('products-rows'))
        $("[name='services[]']").change(e => {

            const total = ticket_info.total()
        })

        $("#apdov").keyup(() => {
            ticket_info.total();
        });


        this.total();
        this.pay_increments()

        $('.forms').on('submit', (e) => {
            let form = $(e.target);
            let submitBtn = form.find('.submit-buttons');
            submitBtn.prop('disabled', true).text('Processing...');
        });
    },

    onProductsChange: function () {
        const products = JSON.parse(document.getElementById('products-rows').dataset.products);

        $("#addNewFormRowForProductsTable").click(() => {
            setTimeout(() => {
                const selectProduct = $("[name='products[]']")


                let product_id = selectProduct.val();
                let product = products.find(p => p.id == product_id)

                let amount = product.amount;
                let price = product.price;
                const row = selectProduct[0].parentNode.parentNode;
                let tds = row.querySelectorAll("td");

                tds[1].innerHTML = amount;
                tds[2].innerHTML = price;
                const total = ticket_info.total()
                // console.log(total);


            }, 100)
        })


        $("[name='products[]']").change((e) => {
            e.stopImmediatePropagation()
            let product_id = e.target.value;
            let product = products.find(p => p.id == product_id)

            let amount = product.amount;
            let price = product.price;
            const row = e.target.parentNode.parentNode;
            let tds = row.querySelectorAll("td");

            tds[1].innerHTML = amount;
            tds[2].innerHTML = price;
            const total = ticket_info.total()
            // console.log(total);



        })
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
                    let tds = row.querySelectorAll("td");

                    tds[1].innerHTML = amount;
                    tds[2].innerHTML = price;
                    ticket_info.total()
                    $('[id^="cancel-"]').click(() => {
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
        const rows = document.querySelector('#products-rows').querySelectorAll('tr');
        let totalProduct = 0;
        rows.forEach(row => {

            let product_price = parseFloat(row.querySelectorAll('td')[2].textContent);
            totalProduct += product_price;
        })
        return totalProduct;
    },
    getTotalServices: function () {
        let selectedServices = document.querySelectorAll("[name='services[]'");
        let totalServices = 0;
        selectedServices.forEach(service => {
            if (service.checked) totalServices += parseFloat(service.dataset.price);
        })
        return totalServices;
    }
    ,
    total: function () {
        const t1 = this.getTotalProducts();
        const t2 = this.getTotalServices();
        const total = t1 + t2;
        $("[name='total']").val(total)

        // balance during visit
        let bdv = $("#bdv");
        // amount paid during office visit
        let apdov = $("#apdov");
        // get amount value
        apdov.val() ? apdov = parseFloat(apdov.val()) : apdov = 0;
        // calaculat Balance during visit
        let bdv_amount = total - apdov;
        // set new value for Balance During Vists
        bdv.val(bdv_amount)

        this.pay_increments()

        return total;
    }
    ,
    pay_increments: function () {
        const balance = $("[name='balanc_during_visit']").val();
        const pi = $("[name='payment_increments']");
        pi.html(`
                <option value="">Select Increments</option>
                <option value="full">Paid In Full</option>
        `)
        for (let index = 1; index < 25; index++) {
            const amount = (parseFloat(balance) / index).toFixed(2);
            pi.append(`<option value="${amount}">${index} Month${index > 1 ? 's' : ''} - $${amount}</option>`)

        }

    }
}

ticket_info.init();

