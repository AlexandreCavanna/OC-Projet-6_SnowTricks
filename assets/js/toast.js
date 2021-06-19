import { Toast } from "bootstrap";

const toastElList = [].slice.call(document.querySelectorAll('.toast'));
const toastList = toastElList.map(function (toastEl) {
    return new Toast(toastEl)
});
toastList.forEach(toast => toast.show());
