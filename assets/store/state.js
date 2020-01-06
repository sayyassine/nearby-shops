//State object
export default {
    //user object
    user : {
        /**
         * is empty if no user is logged in :
         *
         * if a user is connected :
         *   email : string
         *      contains the connected user email
         *   token : string
         *      - the user api key , is user in each api call to authenticate the user
         *   location : object|null
         *      -> long: integer
         *      -> lat: integer
         *      - the user location
         */
    },

    //the location is separated from the user object because it can be used even if no user is connected
    user_location : {
        /**
         * long: integer
         *  - the users longitude
         * lat: integer
         *  - the users longitude
         */
    },

    //contains nearby stores
    stores : [
        /**
         * {
         *      id : interger
         *          - store id
         * },
         * ...
         */
    ] ,

    //a list of liked stores ids
    liked_stores : [
        /**
         * empty if no user connected
         * integer
         */
    ],

    // a list of liked stores ids
    unliked_stores : [
        /**
         * empty if no user connected
         * integer
         */
    ]

}