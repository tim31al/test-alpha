import axios from "axios";


const axiosInstance = axios.create({
  baseURL: process.env.VUE_APP_API_URL
});

axiosInstance.interceptors.response.use(
  (res) => res,
  (e) => {
    console.log(e);
    return Promise.reject(e);
  }
);

export default axiosInstance;
