export default {
    //used to check if the user is logged in or not
    is_logged_in: state => !(Object.entries(state.user).length === 0 && state.user.constructor === Object),

    //getter for the email user
    user_email: (state, getters) => !(getters.is_logged_in) ? null : state.user.email ,

    //getter for the liked stores count
    liked_stores_count : (states, getters) => !(getters.is_logged_in) ? 0 : states.liked_stores.length ,

    //check if a store is in the like list
    is_liked : (state) => (store_id) => {
        return state.liked_stores.includes(""+store_id);
    },

    //check if a store is in the dislike list
    is_disliked : (state) => (store_id) => {
        return state.disliked_stores.includes(""+store_id);
    }

}