
(function () {
    Livewire.on('reloadpage', () => {
        window.location.reload();
    })
})()

function header()
{
    return{
        branchDropdownShown : false,
        languageDropdownShown : false,
        accountDropdownShown : false,
        init()
        {

        },
        showBranchDropdown()
        {
            this.branchDropdownShown = true;
        },
        hideBranchDropdown()
        {
            this.branchDropdownShown = false;
        },
        toggleBranchDropdown(){
            if(this.branchDropdownShown)
            {
                this.hideBranchDropdown()
            }
            else{
                this.showBranchDropdown()
            }
        },
        showLanguageDropdown()
        {
            this.languageDropdownShown = true;
        },
        hideLanguageDropdown()
        {
            this.languageDropdownShown = false;
        },
        toggleLanguageDropdown(){
            if(this.languageDropdownShown)
            {
                this.hideLanguageDropdown()
            }
            else{
                this.showLanguageDropdown()
            }
        },
        showAccountDropdown()
        {
            this.accountDropdownShown = true;
        },
        hideAccountDropdown()
        {
            this.accountDropdownShown = false;
        },
        toggleAccountDropdown(){
            if(this.accountDropdownShown)
            {
                this.hideAccountDropdown()
            }
            else{
                this.showAccountDropdown()
            }
        },
        changeBranch()
        {
            this.$wire.changeBranch(1)
        }
    }
}

function main()
{
    return{
        showPreloader : true,
        cartShown : false,
        cartItems : this.$persist([]),
        cartData : {
            subtotal : 0,
            total : 0,
            tax_total : 0,
            delivery_charge : 0,
            tax_percentage : 0
        },
        noteAddingItem : null,
        notes : '',
        products : [],
        init()
        {
            setTimeout(() => {
                this.showPreloader = false
                document.body.classList.remove('overflow-y-clip')
            },200)
            window.addEventListener('get-products',(e) => {
                this.products = JSON.parse(e.detail)
                this.recalculateData()
            })
            window.addEventListener('clear-everything',(e) => {
                this.cartItems = []
            })
        },
        addItemToCart(itemId)
        {
            let id = parseInt(itemId);
            let cartIndex = this.cartItems.findIndex((x) => x.id == itemId);
            console.log(this.cartItems)
            if(cartIndex != -1)
            {
                let item = this.cartItems[cartIndex];
                item['quantity'] ++;
            }
            else{
                let index = this.products.findIndex((x) => x.id == itemId)
                if(index != -1)
                {
                    let item = this.products[index];
                    item['quantity'] = 1;
                    this.cartItems.push(item)
                }
            }
            this.recalculateData()
        },
        getItemCountFromCart(itemId)
        {
            let index = this.cartItems.findIndex((x) => x.id == itemId)
            if(index != -1)
            {
                return this.cartItems[index].quantity;
            }
            return 0
        },
        reduceItemFromCart(itemId)
        {
            let index = this.cartItems.findIndex((x) => x.id == itemId)
            if(index != -1)
            {
                let item = this.cartItems[index]
                if(item.quantity == 1)
                {
                    this.cartItems.splice(index,1)
                    this.recalculateData()
                    return;
                }
                else{
                    this.cartItems[index].quantity --;
                    this.recalculateData()
                }
            }
            return 0
        },
        recalculateData()
        {
            let subtotal = 0;
            this.cartItems.forEach((x) => {
                let localSubtotal = 0;
                localSubtotal =  x.stitching_cost * x.quantity;
                subtotal += localSubtotal
                let item = x;
                item['total']  = localSubtotal
            })
            this.cartData.subtotal = subtotal;
        },
        showCart()
        {
            this.cartShown = true;
        },
        hideCart()
        {
            this.cartShown = false;
        },
        replaceCurrencyText(inputText,targetNumber)
        {
            let number = parseFloat(targetNumber).toFixed(2)
            const replaced = inputText.replace(/\d+\.\d+/g, number);
            return replaced
        },
        toggleCart(){
            if(this.cartShown)
            {
                this.showCart()
            }
            else{
                this.hideCart()
            }
        },
        showNote(item)
        {
            this.$dispatch('show-my-modal','add-note');
            this.noteAddingItem = item;
            this.notes = item.notes ? item.notes : '';
        },
        saveNote(wire)
        {
            this.$dispatch('hide-my-modal','add-note'); 
            wire.saveNote(this.noteAddingItem,this.notes);
        },
        checkoutFromCart(wire){
            wire.save(this.cartItems)
        }
    }
}