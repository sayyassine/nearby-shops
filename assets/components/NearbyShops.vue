<!--All the stores are listed here ordered by the distance to the user-->
<!--once created tries to get the users location and then sends it to the back end endpoint to get stores nearby-->
<!--if geolocation fails displays an error-->
<template>
    <div class="container">
        <div class="alert alert-warning mt-3" v-if="gettingLocation" >Getting location ... </div>
        <div class="alert alert-danger mt-3" v-else-if="location === null" ><b>We couldn't get you loation</b> : {{ errorStr }}</div>
        <template>
            <store :object="st.store" :distance="st.distance" :key="st.id" v-for="st in stores" >
            </store>
*        </template>
    </div>
</template>

<script>
    import Store from "./Store" ;
    export default {
        name: "NearbyShops",
        components: {Store},
        methods : {
            get_stores(){
                this.loading = true ;
                this.$axios.post("/stores" , {
                    location : this.location,
                    start : (this.page-1) * this.item_per_page ,
                    limit : this.page * this.item_per_page
                }).then(
                    response => {
                        if(!response.data.error){
                            this.stores = response.data.stores
                        }
                    }
                ).finally(()=>{this.loading = false })
            }

        },
        data : function () {
            return {
                loading : false ,
                errorStr : "" ,
                location : null ,
                gettingLocation : false ,
                stores : [
                    {
                        store : {
                            id : 43,
                            name : "Test Store",
                            type : { id : 14 , name : "Music" },
                            long : 111.2,
                            lat : 100.23
                        },
                        distance : 220.43
                    },
                    {
                        store : {
                            id : 44,
                            name : "Test Store B",
                            type : { id : 14 , name : "Music" },
                            long : 111.2,
                            lat : 100.23
                        },
                        distance : 220.43
                    },
                ] ,
                page : 1 ,
                item_per_page : 20,
                type : null,
            }
        },
        created() {
            //do we support geolocation
            if(!("geolocation" in navigator)) {
                this.errorStr = 'Geolocation is not available.';
                return;
            }

            this.gettingLocation = true;
            // get position
            navigator.geolocation.getCurrentPosition(pos => {
                this.errorStr = false;
                this.location = {
                    long: pos.coords.longitude,
                    lat: pos.coords.latitude
                };
                this.gettingLocation = false;

                this.get_stores();
            }, err => {
                this.gettingLocation = false;
                this.errorStr = err.message;
            })

        }
    }
</script>

<style scoped>

</style>