function alpineInstaller()
{
    'use strict'
    return{
        step : 1,
        loading : false,
        init()
        {
            console.log(this.step)
        },
        checkRequirements()
        {
            this.step = 'loading';
            this.$wire.checkRequirementsServer(1).then(result => {
                this.step = 3;
            })
        },
        showDatabase()
        {
            this.step = 4;
        },
        checkDatabase()
        {
            this.loading = true;
            this.$wire.checkDatabase(1).then(result => {
                if(result == true)
                {
                    this.startInstallation()
                    this.step  = 'loading';
                }
                this.loading = false;
            })
        },
        startInstallation()
        {
            this.step ='loading';
            this.$wire.startInstallation(1).then(result => {
                this.step = 7;
            })
        }
    }
}