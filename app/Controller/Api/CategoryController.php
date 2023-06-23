<?php

namespace app\Controller\Api;
use app\Helper\Helper;
class CategoryController extends BaseController
{
    /**
     * "/category/list" Endpoint - Get list of categories stored in session
     */
    public function listAction()
    {

        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        //$arrQueryStringParams = $this->getQueryStringParams();
        $responseData = [];
        if (strtoupper($requestMethod) == 'GET') {
            try {

                $queryStringParams = $this->getQueryStringParams();
                if(!empty($queryStringParams) && isset($queryStringParams['session_id'])){
                    Helper::setSessionId($queryStringParams['session_id']);
                    if(!empty($_SESSION['collectionData'])){
                        $responseData = $_SESSION['collectionData'];
                    }
                }

            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * "/category/listSummary" Endpoint - Get summary list of categories stored in session
     */
    public function listSummaryAction(){
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        //$arrQueryStringParams = $this->getQueryStringParams();
        $responseData = [];
        if (strtoupper($requestMethod) == 'GET') {
            try {

                $queryStringParams = $this->getQueryStringParams();
                if(!empty($queryStringParams) && isset($queryStringParams['session_id'])){
                    Helper::setSessionId($queryStringParams['session_id']);
                    if(!empty($_SESSION['collectionData'])){
                        $collection = $_SESSION['collectionData'];
                        if(!empty($collection)){
                            $responseData = $collection->getCollectionSummary();
                        }

                    }
                }

            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * "/category/listProducts" Endpoint - Get products in all categories
     */
    public function listProductsAction(){
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        //$arrQueryStringParams = $this->getQueryStringParams();
        $responseData = [];
        if (strtoupper($requestMethod) == 'GET') {
            try {

                $queryStringParams = $this->getQueryStringParams();
                if(!empty($queryStringParams) && isset($queryStringParams['session_id'])){
                    Helper::setSessionId($queryStringParams['session_id']);
                    if(!empty($_SESSION['collectionData'])){
                        $collection = $_SESSION['collectionData'];
                        if(!empty($collection)){
                            foreach ($collection->getCollection() as $category){
                                if(!empty($category->products)){
                                    array_push($responseData, ...(array)$category->products);
                                }
                            }
                        }

                    }
                }

            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * "/category/listProducts" Endpoint - Get products in all categories
     */
    public function findCategoryByNameAction(){
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        //$arrQueryStringParams = $this->getQueryStringParams();
        $responseData = [];
        if (strtoupper($requestMethod) == 'GET') {
            try {

                $queryStringParams = $this->getQueryStringParams();
                if(!empty($queryStringParams) && isset($queryStringParams['session_id'])){
                    Helper::setSessionId($queryStringParams['session_id']);
                    if(!empty($_SESSION['collectionData'])){

                        /**
                         * $collection \app\Model\CategoryCollection
                         */
                        $collection = $_SESSION['collectionData'];
                        $products = $collection->getProductsInCategory($queryStringParams['name']);

                        if(!empty($products)){
                            $responseData = ["categoryname"=>$queryStringParams['name'], "products"=>(array)$products];
                        }
                    }
                }

            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }




}