import axios from 'axios';

document.querySelectorAll('#delete-picture').forEach(function (link){
    link.addEventListener('click', function onClickBtnDelete(event) {
        event.preventDefault();

        const url = this.href;

        axios.delete(url).then(function (response) {
            event.target.parentNode.remove()
        })
    });
})
