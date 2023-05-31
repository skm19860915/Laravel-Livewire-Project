import alertify from 'alertifyjs';
const bootbox =   window.bootbox;
alertify.defaults = {

        notifier:{delay:5,position:'top-right'}

};
class FormTable {



    constructor({trigger,tbody,cols,saveLink,action,cancelBtn,clearTrFirstTime}) {

        //Trigger
        if (!trigger) return console.error('Trigger Not Found');
        if (!tbody) return console.error('tbody Not Found');
        if (!cols) return console.error('Columns Not Found');
        saveLink ? this.saveLink = saveLink : this.saveLink = false ;
        action ? this.action = action : this.action = false ;
        cancelBtn ? this.cancelBtn = cancelBtn : this.cancelBtn = false ;
        clearTrFirstTime ? this.clearTrFirstTime = clearTrFirstTime : this.clearTrFirstTime = false ;
        this.trigger = trigger
        this.tbody = tbody
        //create id
        this.id = Math.random().toString(36).substr(2, 9);
        this.save = `save-${this.id}`;
        this.cancel = `cancel-${this.id}`;

        if (this.action)
        {
            // add edit and delete link
            this.addActionButtons(tbody,cols)
        }

        $(this.trigger).click(() => {
            if (this.clearTrFirstTime) tbody.innerHTML = '';
            this.clearTrFirstTime = 0;
            /// create row
            this.row = this.createRow(cols)
            /// inset row to document
            this.tbody.insertAdjacentHTML('beforeend', this.row)
            // cancel  = delete row
            this.cancelRow(this.cancel)
            // save data
            this.saveRow(this.save,cols)
            // generat new radom id
            this.id = Math.random().toString(36).substr(2, 9);
            this.save = `save-${this.id}`;
            this.cancel = `cancel-${this.id}`;
        })

        return this;
    }
    addCancelBtn(rows) {
        let trs = rows.querySelectorAll('tr');
        trs.forEach(tr => {
            let tds = tr.querySelectorAll('td');
            let cancel = document.createElement('a');
            cancel.setAttribute('class', 'text-primary link ')
            cancel.style.cursor = `pointer`;
            cancel.textContent = 'Cancel'
            tds[tds.length - 1].appendChild(cancel);
            cancel.addEventListener('click',e => e.target.parentNode.parentNode.remove())
        })
    }
    addActionButtons(rows,cols) {
        // console.log('Add Action Button');
        let trs = rows.querySelectorAll('tr');
        trs.forEach(tr => {
          this.Actionbuttons(tr,cols)
        })
    }
    Actionbuttons(tr,cols)
    {
       let tds = tr.querySelectorAll('td');
            // let itemId = tr.dataset.id;
            let deleteApi = tr.dataset.delete;
            let object = JSON.parse(tr.dataset.object);
            // console.log(deleteApi);
            let edit_td = tds[tds.length - 2 ]
            let delete_td = tds[tds.length - 1]
            const id  = Math.random().toString(36).substr(2, 9);
            /// add action button to last two tds
            let editBtn = `edit-${id}`;
            let disableBtn = `delete-${id}`;
            // console.log(object);
            edit_td.innerHTML = `<th><a class="text-primary link " style="cursor:pointer" id='${editBtn}'>Edit</a>`
            delete_td.innerHTML   =`<a class="link text-${ !object.disable ? "danger":"success"} " style="cursor:pointer" id='${disableBtn}'>${ !object.disable ? "Disable":"Enable"}</a>`
            $(`#${disableBtn}`).click(async (e) => {
                let tthis = this;
                 if(e.target.textContent == 'Disable')
                {
                    let is_product = deleteApi.indexOf('product') != -1;

                    bootbox.confirm({
                        message: `Are you sure you want to disable this ${is_product ? 'product' : 'service'}?`,
                        buttons: {
                            //i make it opsite to change button order
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
                            //user want disable
                            console.log(result);
                            if(!result)
                            {
                                    // proccess delete
                                    const _token = tthis.tbody.dataset.token;
                                    const _delete = await $.post(deleteApi, { _token, });
                                    // console.log(_delete.responseJSON);
                                    if (_delete.status) {
                                        //get row
                                        const row = e.target.parentNode.parentNode;
                                        const lastTd = row.querySelectorAll('td');
                                        let td = lastTd[Math.abs(lastTd.length- 1)];
                                        let a = td.querySelector('a');
                                        console.log(_delete.data);
                                        if(_delete.data.disable == 1)
                                        {
                                            a.innerText = "Enable";
                                            a.classList.add('text-success');
                                            a.classList.remove('text-danger');
                                        }else{

                                            a.innerText = "Disable";
                                            a.classList.remove('text-success');
                                            a.classList.add('text-danger');
                                        }
                                        //delete row
                                        // row.remove();
                                        //message
                                        alertify.success(_delete.msg).dismissOthers()
                                    } else {
                                        alertify.error(_delete.msg).dismissOthers()
                                    }
                            }
                        }
                    });
                }else{

                                    // if user want enable
                                    // proccess delete
                                    const _token = this.tbody.dataset.token;
                                    const _delete = await $.post(deleteApi, { _token, });
                                    // console.log(_delete.responseJSON);
                                    if (_delete.status) {
                                        //get row
                                        const row = e.target.parentNode.parentNode;
                                        const lastTd = row.querySelectorAll('td');
                                        let td = lastTd[Math.abs(lastTd.length- 1)];
                                        let a = td.querySelector('a');
                                        console.log(_delete.data);
                                        if(_delete.data.disable == 1)
                                        {
                                            a.innerText = "Enable";
                                            a.classList.add('text-success');
                                            a.classList.remove('text-danger');
                                        }else{

                                            a.innerText = "Disable";
                                            a.classList.remove('text-success');
                                            a.classList.add('text-danger');
                                        }
                                        //delete row
                                        // row.remove();
                                        //message
                                        alertify.success(_delete.msg).dismissOthers()
                                    } else {
                                        alertify.error(_delete.msg).dismissOthers()
                                    }
                }


            })
            $(`#${editBtn}`).click(async (e) => {
                const row = e.target.parentNode.parentNode;
                let fileds = {};
                let keys = Object.keys(cols);
                keys.forEach(k => { if (object[k]) fileds[k] = object[k]; })
                // inset input fields
                let tds = row.querySelectorAll('td');
                // console.log(tds[2].querySelector('input'));
                keys.forEach(async(k,index) => {
                    let td = tds[index];
                    let value = fileds[k] ? fileds[k] : '';
                    let input = ``;
                    if (cols[k] !== 'checkbox') {
                        if(k == 'price')
                        {
                            let newprice = td.childNodes[0].textContent.split('$').join('');
                            newprice = parseFloat(newprice).toFixed(2);
                            value = newprice;
                        }
                        if(k == 'amount')
                        {
                            let newprice = td.childNodes[0].textContent.split('$').join('');
                            newprice = parseFloat(newprice).toFixed(0);
                            value = newprice;
                        }
                        input = `<input class='form-control ' step='any' name='${k}' value='${value}' id='${k}-${this.id}' type='${cols[k]}' />`;
                    } else {
                        input = `<input class='form-control ' name='${k}'  ${value ? "checked" : ""}  id='${k}-${this.id}' type='${cols[k]}' />`;
                    }
                    td.innerHTML = input
                })

                let delete_td = tds[tds.length - 2 ]
                let edit_td = tds[tds.length - 1]
                const id  = Math.random().toString(36).substr(2, 9);
                /// add action button to last two tds
                let saveEditBtn = `save-edit-${id}`;
                let cancelEditBtn = `cancel-edit-${id}`;
                delete_td.innerHTML = `<th><a class="text-primary link " style="cursor:pointer" id='${saveEditBtn}'>Save</a>`
                edit_td.innerHTML   =`<a class="text-primary link " style="cursor:pointer" id='${cancelEditBtn}'>Cancel</a>`


                const inputs = row.querySelectorAll('input');
                $(`#${saveEditBtn}`).click(async e => {
                    const params = {};
                    inputs.forEach(node => {
                        let nodeAttr = node.attributes;
                        if (node.type)
                        {
                            if (node.type == 'checkbox')
                            {

                                return params[nodeAttr.name.value] = node.checked;
                            }
                        }
                        params[nodeAttr.name.value] = node.value;

                    })
                    params['_token'] = this.tbody.dataset.token;
                    let update = await $.post(row.dataset.update,params);
                    if (update.status) {
                        inputs.forEach(async (input )=> {
                            if (input.type != 'checkbox')
                            {
                                if(input.name == 'price')
                                {

                                    input.insertAdjacentHTML('afterend', "$"+parseFloat(input.value).toFixed(2))
                                }else{

                                    input.insertAdjacentHTML('afterend', input.value)
                                }
                            } else {
                                //update object info
                                input.parentNode.parentNode.setAttribute('data-object',JSON.stringify(update.data));

                                input.insertAdjacentHTML('afterend', input.checked ? "Yes" : "No")
                            }
                            this.Actionbuttons(tr,cols)
                            input.remove()
                        })

                        return alertify.success(update.msg).dismissOthers();
                    };
                    return alertify.error(update.msg).dismissOthers();
                })

                $(`#${cancelEditBtn}`).click(() => {
                        inputs.forEach(input => {
                            if (input.type == 'checkbox'){
                                input.insertAdjacentHTML('afterend', input.checked ? "Yes" : "No")
                            }else if(input.name == 'price'){
                                let price  = parseFloat(input.value).toFixed(2)
                                input.insertAdjacentHTML('afterend', "$"+price)

                            }else{
                                input.insertAdjacentHTML('afterend', input.value)
                            }
                            input.remove()
                            this.Actionbuttons(tr,cols)
                        })
                })


            })
    }

    createRow(cols)
    {
        let colsKey = Object.keys(cols);
        let tds = ``;
        colsKey.forEach((k) => {
            if (cols[k] != 'select')
            {
                tds += `
                <td>
                <input class='form-control' name='${k}' id='${k}-${this.id}' type='${cols[k]}' />
                </td>

                `
            } else if (cols[k] == 'select') {
                let data = this.tbody.dataset[k];
                data ? data = JSON.parse(data) : data = [{id:'',name:''}];

                let options = ``;

                data.forEach(d => {
                    options += `<option value='${d.id}'>${d.name}</option>`;
                });
                tds += `
                <td>
                    <select class='form-control' name='${k}[]' id='${k}-${this.id}' type='${cols[k]}'>
                        ${options}
                    </select>
                </td>

                `
            } else {
                return '';
            }
        })




        let html = `
                <tr>
                ${tds}
                    ${this.saveLink ? `<th><a class="text-primary link " style="cursor:pointer" id='${this.save}'>Save</a></th>`:``}
                    <th><a class="text-primary link " style="cursor:pointer" id='${this.cancel}'>Cancel</a></th>
                </tr>

        `
        return html
    }

    cancelRow(cancel) {
        const cancelBtn = document.querySelector(`#${cancel}`)
        if (cancelBtn)
        {
            cancelBtn.addEventListener('click', (e) => {
                let row = e.target.parentNode.parentNode;
                row.remove()
            })
        }
    }
    saveRow(save,cols) {
        const saveBtn = document.querySelector(`#${save}`)
        if (saveBtn) {
            saveBtn.addEventListener('click', async (e) => {

                const row = e.target.parentNode.parentNode;
                const rowNodes = row.querySelectorAll('input');
                const params = {};
                params['_token'] = this.tbody.dataset.token;
                rowNodes.forEach(node => {
                    let nodeAttr = node.attributes;
                    if (node.type)
                    {
                        if (node.type == 'checkbox')
                        {

                            return params[nodeAttr.name.value] = node.checked;
                        }
                    }
                    return params[nodeAttr.name.value] = node.value;


                })
                const createProduct = await $.post(this.tbody.dataset.store, params)
                    .done(res => {
                        let html = ``;
                        Object.keys(cols).forEach(c => {

                            if (cols[c] == 'checkbox')
                            {
                                // console.log(res.data.object[c]);
                                 html += `<td> ${res.data.object[c] ? 'Yes' : 'No'} </td>`
                            }
                            else {
                               if(c == 'price')
                               {
                                   html += `<td> $${res.data.object[c] ? res.data.object[c] : ''} </td>`

                               }else{

                                   html += `<td> ${res.data.object[c] ? res.data.object[c] : ''} </td>`
                               }
                            }
                        })
                    html += `<td></td><td></td>`;
                    row.innerHTML = html
                    row.setAttribute('data-update', res.data.updateUrl);
                    row.setAttribute('data-object', JSON.stringify(res.data.object));
                    this.Actionbuttons(row,cols)
                    alertify.success(res.msg).dismissOthers()
                })
                .fail(res => {
                   // display errors
                    Object.values(res.responseJSON.errors).forEach(e => {
                        alertify.error(e[0]).dismissOthers();
                    })
                })
            })
        }
    }


}



export default FormTable;
