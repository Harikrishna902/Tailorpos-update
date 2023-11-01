$(function() {
    "use strict";
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
    var coll = document.getElementsByClassName("collapsible");
    var i;
    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
    function dragover(ev) {
        ev.preventDefault();
        ev.dataTransfer.dropEffect = "move";
    }
    function dragstart(ev) {
        ev.dataTransfer.setData("text/plain", ev.target.id);
        ev.dataTransfer.effectAllowed = "move";
    }
    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.closest('.scrum-board-column').appendChild(document.getElementById(data));
    }

    window.addEventListener('swal-alert', event => {
        Swal.fire({
            title: event.detail.title,
            text: event.detail.message,
            icon: 'error',
            confirmButtonText: 'Ok'
        })
    });

    Livewire.on('closemodal', () => {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').removeAttr('style');
    })

    Livewire.on('reloadpage', () => {
        window.location.reload();
    })

    $(function() {
       "use strict";
        let localtry = localStorage.getItem("message");
        if (localtry !== null) {
            localtry = JSON.parse(localtry);
            toastr[localtry.type](localtry.message, localtry.title);
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }
            localStorage.removeItem("message");
        }
    });
    window.addEventListener('alert', event => {
        toastr[event.detail.type](event.detail.message, event.detail.title ?? '');
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
        }
    });
    Livewire.on('savemessage', (event) => {
        localStorage.setItem("message", JSON.stringify(event));
    })

    window.addEventListener('swal', function(e) {
        swal("Failure!", e.detail.title, "error");
    });
});