import axios from 'axios';

const loadBtn = document.getElementById('load-more');
let nextPage = loadBtn.dataset.nextPage;

loadBtn.addEventListener('click', function onClickBtnDelete(event) {
    event.preventDefault();

    const url = this.href;
    const container = document.querySelector('#container-pagination');

   axios.interceptors.request.use(function (config) {
        loadBtn.lastElementChild.classList.add('spinner-border', 'spinner-border-sm');
        return config;
    }, function (error) {
        return Promise.reject(error);
    });

    axios.interceptors.response.use(function (config) {
        loadBtn.lastElementChild.classList.remove('spinner-border', 'spinner-border-sm');
        return config;
    }, function (error) {
        return Promise.reject(error);
    });

    axios.get(url + `?page=${nextPage++}`).then(function (response) {

        container.insertAdjacentHTML('beforeend', response.data.html );

        if (nextPage > response.data.pages) {
            loadBtn.style.display = "none";
        }
    });


})
