import axios from "axios";

const axiosClient = axios.create({
  baseURL: "admin-ajax.php",
  headers: {
    Accept: "application/json",
    "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
  },
});

axiosClient.interceptors.request.use(
  function (config) {
    // Modify config or attach common data
    // Example: Attach a common query parameter
    config.data = {
      action: "wp_relay_paypal",
      ...config.data,
    };

    return config;
  },
  function (error) {
    // Handle request error
    return Promise.reject(error);
  },
);

export { axiosClient };
