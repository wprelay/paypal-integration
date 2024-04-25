import axios from "axios";


export const axiosClient = axios.create({
    baseURL: 'admin-ajax.php',
});

