import axios from 'axios'
import config from '../config/config'

export default (store) => ({
    axios,
    config: {
        baseURL: config.BASE_URL , // api URL
    },
    interceptors: {
        //adding authentication token to requests header
        beforeRequest (config, axiosInstance) {

            const token = store.getters.is_logged_in ? store.state.user.token  : null ;

            if (token) {
                config.headers.Authorization = `Bearer ${token}` ;
            }

            return config ;
        },
        // add errors from server to client app
        beforeResponseError (error) {
            const { response, message } = error;

            if (response) { // backend error
                // shows response error
                alert(error.response.data.message)
            } else if (message) { // network error
                alert(message)
            }

            // return Promise.reject(error)
        }
    }

})
