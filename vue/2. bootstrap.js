import axios from 'axios';
window.axios = axios;

// Create an instance of Axios
const axiosInstance = axios.create({
    baseURL: 'http://pos.test/api/',
    headers: {'X-Requested-With': 'XMLHttpRequest'},
});

// Add an interceptor to set the Authorization header
axiosInstance.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers['Authorization'] = `Bearer ${token}`;
  }
  return config;
});

export default axiosInstance;


// description
// axios can be used as normal or default but to setting up for baseURL and authorization, i create an instannce of Axios
