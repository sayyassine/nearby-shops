<?php
/**
 * Created by PhpStorm.
 * User: sayya
 * Date: 09/01/2020
 * Time: 22:52
 */

namespace App\Controller;


use App\Entity\Store;
use App\Entity\StoreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminAPIController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminAPIController extends AbstractController
{
    /**
     * TODO : store picture
     * @Route("/admin/api/add-store" , name="add_store")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add_store(Request $request){
        $rq = json_decode($request->getContent());
        $error = "" ;
        $type = null ;
        if(
            !isset($rq->store) || !isset($rq->store->name) || !isset($rq->store->type) || !isset($rq->store->longitude) ||
            !isset($rq->store->latitude)
        ){
            $error =  "some required informations are missing" ;
        }
        if( isset($rq->store) && isset($rq->store->type) && strlen($error) === 0){
            $type_repository = $this->getDoctrine()->getRepository(StoreType::class);
            $type = $type_repository->find($rq->store->type);
            $error = !empty($type) ? "" : "Invalid Store type choosen" ;
        }
        if(strlen($error))
            return $this->json(["error"=> true , "error_message" => $error],
                Response::HTTP_BAD_REQUEST );

        $req_store= $rq->store;
        $store = new Store() ;
        $store->setName($req_store->name);
        $store->setLongitude(floatval($req_store->longitude));
        $store->setLatitude(floatval($req_store->latitude));
        $store->setType($type);

        $em = $this->getDoctrine()->getManager();
        $em->persist($store);
        $em->flush();

        return $this->json(["error " =>  false ,"store_id" => $store->getId()]);
    }

}