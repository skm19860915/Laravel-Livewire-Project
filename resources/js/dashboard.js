// require('./app');
import helper from './helper';
let dashboardJS = {

  init: function(){

        console.log(`Dashboard`);

        $(() => {

            let types = document.querySelector(`#appointment-types-checkboxs`).querySelectorAll(`input`);
            types.forEach(type => {
                let selectedTypes = [];
                type.addEventListener('change',async e => {
                    let getSelectedTypes = await types
                                                .forEach(async _type => {
                            let selected = _type.checked
                            // console.log(selected,'  ',_type.value);
                            if (selected) {
                                selectedTypes.push(parseInt(_type.value))
                                selectedTypes = await [...new Set(selectedTypes)];
                            } else {
                                selectedTypes = await dashboardJS.removeItemAll(selectedTypes, parseInt(_type.value));
                            }

                        })

                selectedTypes =  await [...new Set(selectedTypes)]

                    //filter types
                    let appointments = $(`.appointments`).prop('style','display:none !important');
                    console.log(selectedTypes);
                    selectedTypes.forEach(type => {
                        const clas = `.appointment-types-${type}`;
                        $(clas).prop('style','display:flex !important');

                    })

            })
            })
        })
    },
    removeItemAll:function(arr, value) {
                var i = 0;
                while (i < arr.length) {
                    if (arr[i] === value) {
                    arr.splice(i, 1);
                    } else {
                    ++i;
                    }
                }
                return arr;
                }



}

dashboardJS.init();

