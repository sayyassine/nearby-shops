<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 05/01/2020
 * Time: 23:26
 */

namespace App\Controller;


use App\Entity\Store;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class APIController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class APIController extends AbstractController
{

    /**
     * (TESTED)
     * @Route("/api/stores" , name="get_stores")
     *
     * Requires ROLE_USER (X_HTTP_TOKEN)
     * methods: {GET, POST}
     * parameters: {
     *  location : object|null { long: integer, lat: integer }
     * }
     * returns: {
     *  stores: array of
     *      store_object : object{
     *          name: string,
     *          id: integer,
     *          name: location {long: integer, lat:integer},
     *          distance: float
     *      } ,
     *      distance : float // distance from store in km
     *  stores_count: integer,
     *  error: boolean,
     *  error_message: string
     * @param Request $request
     * @return Response
     */
    public function get_stores(Request $request, SerializerInterface $serializer){

       /**
         * DONE(TESTED) GET User location (return error if location not found in the request)
         * TODO Exclude unliked stores for the user
         * DONE(TESTED)GET Filtering paramaters
         * DONE(TESTED)search stores nearby (apply filtterring)
         * OPTIONAL fetch stores from API
         * OPTIONAL cache api call result
         */

        //decoding the json request and extracting parameters
        $rq = json_decode($request->getContent());

        //getting the location
        $location = isset($rq->location) ? $rq->location : null ;
        if( !isset($location->lat) || !isset($location->long) ){
            return $this->json(["error"=> true , "error_message" => "Invalid or undefined location"]);
        }

        /**
         * Paging attributes
         *
         * limit : results number (results_per_page)
         * start : first result (page_number * results_per_page)
         *      page_number : starts from 0 and should be lower than ABS(results_count / results_per_page).
         *                    if not an empty list is returned
         *
         * return filtered_result[page_number+ * :
         */

        //start by default set to first result
        $start = isset($rq->start) ? $rq->start : 0;

        //limits by default set to 10 results
        $limit = isset($rq->limit) ? $rq->limit : 10;

        //filter by type if specified
        $type = isset($rq->type) ? $rq->type : null ;

        //filter by radius(km) if specified (only shops with distance lower than this value will be returned)
        $radius  = isset($rq->radius) ? $rq->radius : null ;

        //Getting the repository and running the query
        $stores_repository = $this->getDoctrine()->getRepository(Store::class);
        $results_array = $stores_repository->findNearest($location->long,$location->lat,$start , $limit,$type , $radius );

        //Prepapring the response object  query
        //$stores = $result_arrays  ;
        $stores_count = count($results_array );
        $error = false ;
        $error_message = "" ;


        $response = [
            'stores' => $results_array ,
            'stores_count' => $stores_count,
            'error' => $error ,
            "error_message" => $error_message
        ];

        $jsonObject = $serializer->serialize( $response, 'json', [
            AbstractNormalizer::CALLBACKS => [
                'type' => function ($innerObject) {
                    return ['id' => $innerObject->getId() ,'name' => $innerObject->getName(), ];
                },
            ],
        ]);

        return new JsonResponse($jsonObject , Response::HTTP_OK, [] ,true );

    }


    /**
     *
     * @Route("/api/stores/like/{store}" , name="like_store")
     * @param Store $store
     * @return JsonResponse
     */
    public function like_store(Store $store){

        if( !$store ) {
            return $this->json(['error'=> true , "error_message" => "Store invalid or not existing" , Response::HTTP_NOT_FOUND ]);
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->addLikedStore($store);
        $em->persist($user);
        $em->flush();

        return $this->json(['error' => false, 'error_message' => ""] );
    }

    /**
     *
     * @Route("/api/stores/remove-liked/{store}" ,name="remove_liked_store")
     * @param Store $store
     * @return JsonResponse
     */
    public function remove_liked_store(Store $store){

        if( !$store ) {
            return $this->json(['error'=> true , "error_message" => "Store invalid or not existing" , Response::HTTP_NOT_FOUND ]);
        }

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $user->removeLikedStore($store);

        $em->persist($user);
        $em->flush();

        return $this->json(['error' => false, 'error_message' => ""] );
    }


    /**
     *
     * @Route("/api/stores/liked" , name="get_liked_stores")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function get_liked_stores(Request $request , SerializerInterface $serializer){

        $stores = $this->getUser()->getLikedStores();

        $rq = json_decode($request->getContent());

        //getting pagination rules from request (if set)
        $start = isset($rq->start) ? $rq->start : 0 ;
        $limit = isset($rq->limit) ? $rq->limit : 20 ;

        //getting filtering rules from request(if set)
        $type = isset($rq->type) ? $rq->type : null ;
        $radius = isset($rq->radius) ? $rq->radius : null ;
        $location = (isset($rq->location) && isset($rq->location->long) &&  isset($rq->location->lat)) ? $rq->location : null ;

        //applying filtering
        $stores = $stores->filter(
            function ($a) use($type,$location , $radius)  { return (
                    ( $type ? $a->getType()->getId() === intval($type) : true ) &&
                    ( (!empty($location)&& !empty($radius) )  ? ($a->calculateDistance($location->long , $location->lat) < floatval($radius)) : true )
                );
            }
        );


        //if location is set , sort by nearest
        $stores = $stores->toArray();
        if($location){
            usort($stores , function ($a , $b) use($location) {
                return $a->calculateDistance($location->long ,$location->lat) > $b->calculateDistance($location->long ,$location->lat) ? 1 : -1;
            }) ;
        };

        //applying pagination
        $results_array = array_slice($stores , $start , $limit);


        $response = [
            'stores' => $results_array ,
            'stores_count' => count($results_array),
            'error' => false ,
            "error_message" => ""
        ];

        $jsonObject = $serializer->serialize( $response, 'json', [
            AbstractNormalizer::CALLBACKS => [
                'type' => function ($innerObject) {
                    return [
                        'id' => $innerObject->getId() ,
                        'name' => $innerObject->getName()
                    ];
                }
            ],
        ]);

        return new JsonResponse($jsonObject, Response::HTTP_OK ,[] , true );
    }



}