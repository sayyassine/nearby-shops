<!-- components that displays one store -->
<template>
    <div class="col-md-3 col-sm-6 col-xs-6 p-2">
        <b-card
                :title="object.name"
                :sub-title="object.type.name"
                :img-src="object.picture ? object.picture  : '/images/stores/default.png'"
                img-class="w-50"
                img-alt="Store Image"
                img-top
                class="store d-inline-block w-100"
                body-class="pt-1"


        >
            <b-card-text>
                <div>
                    <font-awesome-icon icon="map-pin"></font-awesome-icon> {{ two_decimals(distance) }} km
                </div>
            </b-card-text>
            <template v-slot:footer v-if="is_logged_in">
                <div class="d-flex justify-content-around" >
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
    </div>

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
            },
            two_decimals(){
                return (n) => Math.round(n*100)/100;
            },
            is_logged_in(){
                return this.$store.getters.is_logged_in;
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

    }
</style>