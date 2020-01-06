export default {
    // remove the logged out user from the state
    logout (state) {
        state.user = null
    },
    // adds the logged in user to the state
    login (state ,user) {
        state.user = user ;
    }
}