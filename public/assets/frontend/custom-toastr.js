'use strict'
function toasterCustom()
{
    return{
        toasties : [],
        init()
        {
            window.addEventListener('send-notification',(event) => {
                let id = Math.floor(Math.random() * 9999999999);
                let toast = {
                    shown : false,
                    id,
                    message : event.detail.message,
                    title : event.detail.title
                }
          
                this.toasties.push(toast)
                setTimeout(() => {
                    let index = this.toasties.findIndex((x) => x.id == id)
                    if(index != -1)
                    {
                        this.toasties.splice(index,1)
                    }
                },3000)
            })
        },
    }
}