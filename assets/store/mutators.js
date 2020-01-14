export default {
    // remove the logged out user from the state
    logout (state) {
        state.user = {};
        state.liked_stores = [];
        state.disliked_stores = [];
    },
    // adds the logged in user to the state
    login (state ,user) {
        state.user = user ;
    },
    like_store(state, store_id){
        if(state.liked_stores.includes(""+ store_id))
            return;
        const index = state.disliked_stores.indexOf("" + store_id);
        if(index >= 0 )
            state.disliked_stores.splice(index,1)

        state.liked_stores.push(""+store_id);
    },
    unlike_store(state, store_id) {
        const index = state.liked_stores.indexOf("" + store_id);
        if(index >= 0 )
            state.liked_stores.splice(index,1);
    },
    dislike_store(state, store_id){
        if(state.disliked_stores.includes(""+ store_id)){
            //update dislike time
            return;
        }
        const index = state.liked_stores.indexOf("" + store_id)
        if(index >= 0 )
            state.liked_stores.splice(index,1);

        state.disliked_stores.push(""+store_id);
    },
    undislike_store(state, store_id) {
        const index = state.disliked_stores.indexOf("" + store_id)
        if(index >= 0 )
            state.disliked_stores.splice(index,1);
    },
    add_liked_stores(state , stores){
        state.liked_stores = stores.map((e)=> ""+e.id );
    }
}