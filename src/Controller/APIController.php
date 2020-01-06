<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 05/01/2020
 * Time: 23:26
 */

namespace App\Controller;


use phpDocumentor\Reflection\Location;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class APIController
 * @package App\Controller
 */
class APIController extends AbstractController
{

    /**
     * @Route("/api/stores/" , name="get_stores")
     * Requires ROLE_USER (X_HTTP_TOKEN)
     * methods: {GET, POST}
     * parameters: {
     *  user_id : string,
     *  location : object|null { long: integer, lat: integer }
     * }
     * returns: {
     *  stores: array of store_object
     *      store_object : object{
     *          name: string,
     *          id: integer,
     *          name: location {long: integer, lat:integer},
     *          distance: float
     *      } ,
     *  stores_count: integer,
     *  error: boolean,
     *  error_message: string
     *
     */
    public function get_stores(){

        /**
         * TODO GET User location (return error if location not found in the request)
         * TODO Get unliked stores for the user
         * TODO GET Filtering paramaters
         * TODO search stores nearby (apply filtterring)
         * OPTIONAL fetch stores from API
         * OPTIONAL cache api call result
         */
        $stores = [] ;
        $stores_count = count($stores);
        $error = false ;
        $error_message = "" ;
        return $this->json(["stores"=> $stores , "error" => $error , "stores_count" => $stores_count , $error_message ]);
    }




}