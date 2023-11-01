'use strict'
function modalFunction()
{
    return{
        modalShown : false,
        init()
        {
            window.addEventListener('show-my-modal',(e) => {
                console.log(e)
                if(e.detail == this.$el.id)
                {
                    this.showModal();
                    document.body.classList.add('overflow-y-clip')
                }
            })
            window.addEventListener('hide-my-modal',(e) => {
                if(e.detail == this.$el.id)
                {
                    this.hideModal();
                }
            })
            window.addEventListener('hide-modals',(e) => {
                this.hideModal()
            })
        },
        showModal()
        {
            this.modalShown = true;
        },
        hideModal()
        {
            this.modalShown = false;
            document.body.classList.remove('overflow-y-clip')
        },
    }
}