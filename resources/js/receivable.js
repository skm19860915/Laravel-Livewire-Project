// require('./app');
import helper from './helper';

let receivableJS = {

  init: function(){

    console.log("Receivable.js");
    $(()=>{
      const proccessPaymentLinks  = document.querySelectorAll(`a[proccess_payment_link]`);
        proccessPaymentLinks.forEach(link => {
         const paymentString  = link.attributes.proccess_payment_link.value;
         const action  = link.attributes.action.value;
         const _token  = link.attributes.token.value;
         const payment = JSON.parse(paymentString);
         //when proccess payment clicked
        //  link.addEventListener('click',e=>{
        //    e.preventDefault();
        //   let form  = document.createElement('form');
        //   let month_index = document.createElement('input');
        //   let date_due =  document.createElement('input');
        //   let token =  document.createElement('input');
        //   //assign inputs values
        //   month_index.setAttribute('value',payment.month_index);
        //   date_due.setAttribute('value',payment.date_due.split('T')[0]);
        //   token.setAttribute('value',_token);
        //   // assign inputs names
        //   date_due.setAttribute('name','date_due');
        //   month_index.setAttribute('name','month_index');
        //   token.setAttribute('name','_token');
        //   //assign inputs to form
        //   form.appendChild(month_index);
        //   form.appendChild(date_due);
        //   form.appendChild(token);
        //   form.setAttribute('action',action)
        //   form.setAttribute('method',"POST")
        //   //assign from to document
        //   document.body.appendChild(form);
        //   //sumbit form
        //   form.submit();


        //  })
      })
    })
  },



}

receivableJS.init();

