import helper from './helper';
const bootbox =   window.bootbox;
let paymentJS = {

  init: function(){
    window.done = 0 ;
        $(() => {

            $('.forms').on('submit', async (e)=>{
                if(window.done == 1) return;
                let form = $(e.target);
                let balance = parseFloat(e.target.getAttribute('balance')) ;
                const amount =parseFloat(e.target.amount.value) || 0;
                if(amount <= 0)
                {
                    e.preventDefault();
                    bootbox.alert(`Payment must be greater than $0.`)
                    return false;
                }

                let payment_increments = form.data('increment');
                if (typeof payment_increments == 'string')
                {
                    payment_increments = payment_increments.split(",").join('');
                }
                if(amount > balance )
                {
                    e.preventDefault();
                    bootbox.alert(`Payment amount cannot be greater than balance.`)
                    return false;
                }

                if (amount < payment_increments) {
                    e.preventDefault();
                    let form = $(e.target);
                    bootbox.confirm({
                        message: `This payment is less than the monthly payment agreement. Are you sure you wish to apply this amount to balance?`,
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
                                window.done = 1;
                                let submitBtn = form.find('.submit-buttons');
                                submitBtn.prop('disabled', true).text('Processing...');
                                delete bootbox.confirm;
                                form.trigger('submit');
                            }
                        }
                    });
                }

            });
            const refund = $(`a[data-payment]`);
            const confirmModal = $(`#confirmModal`);
            const refundForm = $(`#refundForm`)
            refund.click(e => {
                e.preventDefault();
                const payment_id = e.target.dataset.payment;
                confirmModal.modal()
                let submitBnt = refundForm.find(`button[type='submit']`);
                let appUrl = helper.getSiteUrl();
                let refund = appUrl + '/refund/payment/' + payment_id;
                refundForm.attr(`action`, refund);
            })


            const history = $(`[data-history]`);
            if (history.data('history'))
            {
                const to = history[0];
                to.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});

            }
        })

  },



}

paymentJS.init();
