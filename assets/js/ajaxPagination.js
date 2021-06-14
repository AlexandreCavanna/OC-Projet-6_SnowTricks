import axios from 'axios';

const loadBtn = document.getElementById('load-more');
let nextPage = loadBtn.dataset.nextPage;

loadBtn.addEventListener('click', function onClickBtnDelete(event) {
    event.preventDefault();

    const url = this.href;
    const container = document.querySelector('#trick-container');

    axios.get(url + `?page=${nextPage++}`).then(function (response) {

        container.insertAdjacentHTML('beforeend', response.data.html );

        if (nextPage > response.data.pages) {
            loadBtn.style.display = "none";
        }
    })
})
