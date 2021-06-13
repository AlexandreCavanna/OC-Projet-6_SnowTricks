document.querySelector('#add-video').addEventListener("click", () => {
    const index = document.getElementById("widgets-counter").value;

    const tmpl = document.querySelector('#trick_videos').dataset.prototype.replace(/__name__/g, index);

    document.querySelector("#add-video").insertAdjacentHTML( 'beforebegin', tmpl );

    let value = parseInt(document.getElementById('widgets-counter').value, 10);
    value++

    document.getElementById("widgets-counter").value = value;

    handleDeleteButtons();
});

function handleDeleteButtons() {
    let $button;

    for(let i = 0; i < document.querySelectorAll('button[data-action="delete"]').length; i++){
        $button = document.querySelectorAll('button[data-action="delete"]');

        $button[i].addEventListener("click", function(event){

            const target = event.currentTarget.getAttribute("data-target");
            const t = document.querySelector(target);

            if(t !== null) {
                t.remove();
            }
        });
    }
}

function updateCounter() {
    let count = document.querySelectorAll('#trick_videos div.form-group').length;
    document.getElementById("widgets-counter").value =+ count;
}

updateCounter();
handleDeleteButtons();
