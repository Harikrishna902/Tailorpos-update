function alpineUpdater()
{
    'use strict'
    return{
        step : 1,
        loading : false,
        count : 0,
        init()
        {
            setInterval(() => {
                try{
                    if(this.count < 5)
                    {
                        this.count ++;
                    }
                    else{
                        this.count= 0;
                    }
                }
                catch(e)
                {

                }
               
            },1000)
        },
        updateNow()
        {
            this.step = 2;
            console.log(this.$wire)
            try{
            this.$wire.update(1)
            }
            catch(e)
            {

            }
        },
        
    }
}