export default {
    //
    logout (state) {
        state.user = null
    },
    login (state ,user) {
        state.user = user ;
    }
}