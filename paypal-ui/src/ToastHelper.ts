import {toast} from "react-toastify";

export const toastrSuccess = (message: any) => {
    toast.success(message, {
        position: "top-right",
        autoClose: 2000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: false,
        progress: undefined,
        theme: "light",
        className: "toast-message",
    });
}

export const toastrError = (message: any) => {

    if (!message) {
        message = 'Error Occurred';
    }

    toast.error(message, {
        position: "top-right",
        autoClose: 2000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: false,
        progress: undefined,
        theme: "light",
        className: "toast-message",
    });
}