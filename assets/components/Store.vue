<!-- components that displays one store -->
<template>
    <b-card
        :title="object.name"
        :img-src="object.picture ? object.picture  : '/images/stores/default.jpg'"
        img-class="w-50"
        img-alt="Store Image"
        img-top
        class="store d-inline-block mr-4"
        body-class="p-2"


    >
        <b-card-text>
            <div>
                Type : {{ object.type.name }}<br/>
                Distance : {{ distance }} km
            </div>
        </b-card-text>
        <template v-slot:footer>
            <div class="d-flex justify-content-around">
                <div>
                    <font-awesome-icon style="cursor: pointer" color="red" v-if="is_liked" :icon="['fas' ,'heart']" @click="unlike_store"/>
                    <font-awesome-icon style="cursor: pointer" v-else :icon="['far' ,'heart']" @click="like_store"/>
                </div>
                <div>
                    <font-awesome-icon style="cursor: pointer" color="orange" v-if="is_disliked" :icon="['fas' ,'thumbs-down']" @click="undislike_store"/>
                    <font-awesome-icon style="cursor: pointer" v-else :icon="['far' ,'thumbs-down']" @click="dislike_store"/>
                </div>
            </div>
        </template>
    </b-card>
</template>

<script>
    export default {
        name: "Store",
        props : [
            "object",
            "distance",
        ],
        computed : {
            is_liked(){
                return this.$store.getters.is_liked(this.object.id);
            },
            is_disliked(){
                return this.$store.getters.is_disliked(this.object.id);
            }
        },
        methods : {
            like_store(){
                //sends a request to the backend to persist stored store
                //adds the liked store to like list in the state
                this.$axios.post("/stores/like/" + this.object.id).then(
                    function (response){
                        if(!response.error){
                            this.$store.commit("like_store" , this.object.id);
                        }
                    }.bind(this)
                );
            },
            //removes the store from the like list
            unlike_store(){
                //sends a request to the backend to persist stored store
                //adds the liked store to like list in the state
                this.$axios.post("/stores/remove-liked/" + this.object.id).then(
                function (response){
                    if(!response.error){
                        this.$store.commit("unlike_store" , this.object.id)
                    }
                }.bind(this)
            );


    },
            //adds the store the dislike list
            dislike_store(){
                //sends a request to the backend to persist stored store
                //adds the liked store to like list in the state
                this.$axios.post("/stores/dislike/" + this.object.id).then(
                    function (response){
                        if(!response.error){
                            this.$store.commit("dislike_store" , this.object.id)
                        }
                    }.bind(this)
                );
            },
            //removes the store from the dislike list
            undislike_store(){
                //sends a request to the backend to persist stored store
                //adds the liked store to like list in the state
                this.$axios.post("/stores/remove-disliked/" + this.object.id).then(
                function (response){
                    if(!response.error){
                        this.$store.commit("undislike_store" , this.object.id)
                    }
                }.bind(this)
            );


    }
        }

    }
</script>

<style scoped>
    .store{
        width: 200px;
    }
</style>