<!--All the stores are listed here ordered by the distance to the user-->
<!--once created tries to get the users location and then sends it to the back end endpoint to get stores nearby-->
<!--if geolocation fails displays an error-->
<template>
    <div class="container">
        <div class="vld-parent">
            <loading :active.sync="loading" :can-cancel="false"></loading>
        </div>
        <b-card class="p-0 my-4" body-class="p-0">
            <b-card-header class="m-0 p-2 px-4">
                <div class="row d-flex justify-content-between align-items-center">
                    <h1>
                        Stores Nearby
                    </h1>

                    <div class="d-flex">

                        <b-dropdown id="dropdown-form" variant="primary" dropleft ref="dropdown" class="m-2">
                            <template v-slot:button-content>
                                <font-awesome-icon icon="filter" /><span class="sr-only">Search</span>
                            </template>
                            <b-dropdown-header id="dropdown-header-label">
                                Filter by
                            </b-dropdown-header>
                            <b-dropdown-form>
                                <!--Type filter-->
                                <b-input-group class="my-2 wide" >
                                    <b-form-select id="type" class="form-control" v-model="type">
                                        <option value="0">
                                            Select Store Type
                                        </option>
                                        <option :value="store.id" v-for="store in store_types">
                                            {{ store.name }}
                                        </option>
                                    </b-form-select>
                                </b-input-group>

                                <!--Radius filter-->
                                <b-input-group class="my-2 wide" append="KM"  >
                                    <b-form-input v-model="max_distance" placeholder="Maximal Distance" type="number" ></b-form-input>
                                </b-input-group>

                                <!--Items per page-->
                                <b-input-group class="my-2 wide" append="Item/Page"  >
                                    <b-form-select v-model="item_per_page"  >
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="40">40</option>
                                    </b-form-select>
                                </b-input-group>
                            </b-dropdown-form>
                            <b-dropdown-divider></b-dropdown-divider>
                            <b-dropdown-item-button variant="success" class="text-center" @click="page=1;get_stores()">Apply</b-dropdown-item-button>
                        </b-dropdown>

                    </div>
                </div>
            </b-card-header>
            <div class="container px-5 pb-3">

                <div class="alert alert-warning mt-3" v-if="gettingLocation" >Getting location ... </div>
                <div class="alert alert-danger mt-3" v-else-if="location === null" ><b>We couldn't get your location</b> : {{ errorStr }}</div>

                <div class="row flex-row-reverse mb-2 text-muted align-items-center pt-2 font-weight-light" v-if="applied_filters !== null">

                    <div class="border border-secondary mr-2 mb-0 rounded px-1 text-muted" v-if="applied_filters !== null">
                        showing <b>{{results_in_page}}</b> from <b>{{this.applied_filters.results_count}}</b> results
                    </div>
                    <div class="border border-secondary mr-2 mb-0 rounded px-1 text-muted" v-if="applied_filters !== null">
                        Type : <b>{{this.applied_filters.type ?  this.applied_filters.type.name : "All"}}</b>
                    </div>

                    <div class="border border-secondary mr-2 mb-0 rounded px-1 text-muted" v-if="applied_filters !== null">
                        Max distance: <b>{{this.applied_filters.max_distance ? this.applied_filters.max_distance + " KM" : "None"}}</b>
                    </div>

                </div>
                <div class="row">
                    <store :object="st.store" :distance="st.distance" :key="st.id" v-for="st in stores" >
                    </store>
                    <div class="d-flex justify-content-center align-items-center" v-if="!loading && no_stores" >
                        <img src="/images/nothing_found.gif" class="mw-50">
                        <div>
                            <div class="display-4">
                                Seems like we can't help you there.
                            </div>
                            <div class="">
                                We couldn't find any results matching these filters.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <b-card-footer class="m-0 p-2 px-4">
                <div class="row d-flex justify-content-center align-items-center">
                    <b-button :class="['btn','btn-sm', (page === p ? 'btn-outline-success' :'btn-success') , 'mx-1' ]"
                              v-for="p in pages" :key="p" :disabled="page === p"
                                @click="page=p; get_stores()">
                        {{ p }}
                    </b-button>
                </div>
            </b-card-footer>

        </b-card>
    </div>
</template>

<script>
    import Store from "./Store" ;
    import Loading from 'vue-loading-overlay'
    import 'vue-loading-overlay/dist/vue-loading.css';
    import FormOptions from "bootstrap-vue/esm/mixins/form-options";

    export default {
        name: "NearbyShops",
        components: {FormOptions, Store, Loading},
        methods : {
            get_stores(){
                let data = {
                    location : this.location,
                    start : (this.page-1) * this.item_per_page ,
                    limit : this.page * this.item_per_page
                } ;

                if(this.max_distance > 0 )
                    data['radius'] = parseFloat(this.max_distance);

                if(this.type !== "0" )
                    data['type'] = this.type ;

                this.loading = true ;
                this.$axios.post("/stores" ,data ).then(
                    response => {
                        if(!response.data.error){
                            this.stores = response.data.stores ;
                            this.results_count = parseInt(response.data.stores_count);

                            this.applied_filters = {} ;

                            this.applied_filters['type'] = this.store_types.find(e => e.id === this.type);
                            this.applied_filters['item_per_page'] = this.item_per_page ;
                            this.applied_filters['start'] = (this.page-1) * this.item_per_page;
                            this.applied_filters['results_count'] = this.results_count;
                            this.applied_filters['max_distance'] = this.max_distance;
                        }
                    }
                ).finally(()=>{this.loading = false })
            },

        },
        data : function () {
            return {
                applied_filters : null ,
                loading : false ,
                errorStr : "" ,
                location : null ,
                gettingLocation : false ,
                stores : [] ,
                page : 1 ,
                item_per_page : 20,
                results_count : 0,
                type : 0,
                store_types : [],
                max_distance : null
            }
        },
        created() {

            this.$axios.get("/stores/types").then(function(response){
                this.store_types = response.data.types ;
            }.bind(this));

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

        },
        computed : {
            pages () {
                let pages = [] ;
                let i = 1 ;
                if(this.results_count === 0)
                    return [1];
                for( i=1 ; i <= this.results_count/parseInt(this.item_per_page) ; i++  ){
                    pages.push(i);
                }
                if(this.results_count % this.item_per_page !== 0)
                    pages.push(i);

                return pages ;
            },
            no_stores(){
                return !this.stores || this.stores.length === 0
            },
            results_in_page(){
                let start = parseInt(this.applied_filters.start),
                    results_count = parseInt(this.applied_filters.results_count),
                    item_per_page = parseInt(this.applied_filters.item_per_page);

                    return start + item_per_page > results_count ?  results_count % item_per_page : item_per_page
            }
        }
    }
</script>

<style scoped>
    .wide {
        width: 300px;
    }
    h1 {
        font-weight: 400 ;
    }
    .mw-50 {
        max-width : 50%
    }

</style>