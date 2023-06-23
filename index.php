<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "autoload.php";


use app\Helper\Helper;
use app\Model\Category;
use app\Model\CategoryCollection;
use app\Model\Product;

Helper::startSession();

$collection = new CategoryCollection(new Helper());
$collection->addCategory(new Category('Mens', [new Product('Blue Shirt', '/Assets/blue-shirt.jpg'), new Product('Red T-Shirt', '/Assets/red-shirt.png')]));
$collection->addCategory(new Category('Kids', [new Product('Sneakers', '/Assets/sneakers.png'), new Product('Toy car', '/Assets/toy-car.jpg')]));

$_SESSION["collectionData"] = $collection;

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap-grid.css" rel="stylesheet">
    <link href="/vendor/wenzhixin/bootstrap-table/dist/bootstrap-table.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/Assets/main.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Afrozaar Assesment</a>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/" data-bs-title="Dashboard" data-api-method="category/listSummary" data-next-type="categorySummaryTable">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products" data-bs-title="Products" data-api-method="category/listProducts" data-next-type="productSummaryTable">
                            Products
                        </a>
                    </li>
                </ul>

            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>


            <div class="categorySummary section" id="Dashboard">
                <h2>Categories</h2>
                <table id="categorySummaryTable" data-toggle="table" data-toolbar="#toolbar">
                    <thead>
                    <tr>
                        <th data-field="name" data-formatter="ActionFormatter">Name</th>
                        <th data-field="productCount">Number of products</th>
                    </tr>
                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>

            <div class="productSummaryTable section" id="Products">
                <h2>Products</h2>
                <table id="productSummaryTable" data-toggle="table" data-toolbar="#toolbar">
                    <thead>
                    <tr>
                        <th data-field="name">Name</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>



            <div class="categoryDetails section" id="categoryDetails">
                <h2 id="categoryName"></h2>
                <div class="products container">
                    <div class="row">
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="/vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/vendor/wenzhixin/bootstrap-table/dist/bootstrap-table.min.js"></script>


<script type="application/javascript">

    window.addEventListener('popstate', function (event) {
        // Log the state data to the console
        loadDefaults();
    });

    var categoryData = <?= json_encode($collection->getCollectionSummary()); ?>;
    var categorySummary = $('#categorySummaryTable');
    var baseUrl = "<?= Helper::getBaseUrl() ?>";
    var session_id = "<?= session_id() ?>";
    $( document ).ready(function() {
        categorySummary.bootstrapTable('load', categoryData);

        $('.nav-item a').click(function (ev){

            ev.preventDefault();

            let $currentElement = $(this);
            var nextTitle = $currentElement.attr("data-bs-title");
            let nextURL = $currentElement.attr("href");
            let apiMethod = $currentElement.attr("data-api-method");
            var nextElementType = $currentElement.attr("data-next-type");

            window.history.pushState("", nextTitle, nextURL);

            API.getRequest(function (result){
                let parentElement = $("#"+nextTitle);
                let childElement = $("#" + nextElementType);

                if(parentElement.length){
                    $('.section').hide();
                    parentElement.show();

                    if(childElement.length){
                        childElement.bootstrapTable('load', result);
                    }

                }

            }, apiMethod, {});
        });


        loadDefaults();




    });

    function loadDefaults(){
        //check what section is loaded.
        let pathname = window.location.pathname;

        if(pathname.length === 1){
            $('.section').hide();
            API.getRequest(function (result){
                let parentElement = $('.categorySummary');
                let childElement = $("#categorySummaryTable");

                if(parentElement.length){
                    parentElement.show();

                    if(childElement.length){
                        childElement.bootstrapTable('load', result);
                    }

                }

            }, "category/listSummary", {});

        }

        if(pathname.includes("/products")){
            $('.section').hide();
            API.getRequest(function (result){
                let parentElement = $('#Products');
                let childElement = $("#productSummaryTable");

                if(parentElement.length){
                    parentElement.show();

                    if(childElement.length){
                        childElement.bootstrapTable('load', result);
                    }

                }

            }, "category/listProducts", {});
        }

        if(pathname.includes("/category/")){
            $('.section').hide();
            let catName = pathname.split("/")[2];

            loadCategoryDetails(null, false, catName);
        }
    }

    var API = {

        _baseurl: baseUrl + "/app/",
        _authtoken: null,
        _authenticated: false,
        _authenticatedforedit: false,

        _sendRequest: function (method, data, requestType, success, failure) {
            data["session_id"] = session_id;
            $.ajax({
                type: requestType,
                contentType: "application/json; charset=utf-8",
                url: this._baseurl + method,
                data: data,
                dataType: "json"
            })
                .done(function (result, textStatus, jqXHR) {
                    if (result){
                        success(result);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.responseText);
                    if (failure)
                        failure(jqXHR, textStatus, errorThrown);
                })
        },

        _noResponse: function () {
        },

        getRequest: function (onComplete, method, data) {
            if (!onComplete) onComplete = this._noResponse;

            this._sendRequest(method, data, "GET", function (result) { onComplete(result) }, function () { onComplete(false); });
        }
    }

    function loadCategoryDetails(element, pushHistory = true, categoryName = null){

        let currentElement = $(element);
        var catName = currentElement.attr("data-category-name");
        if(categoryName !== null){
            catName = categoryName;
        }

        let requestData = {"name":catName};

        if(pushHistory){
            window.history.pushState("", catName, "category/"+catName);
        }




        API.getRequest(function (result){
            let parentElement = $("#categoryDetails");

            if(parentElement.length){
                $('.section').hide();
                parentElement.show();
                parentElement.find("#categoryName").html(catName);
                parentElement.find(".products.container .row").empty();

                for(let ii=0; ii<result.products.length; ii++){
                    let product = result.products[ii];
                    let html = "<div class=\"col-sm-4\">";
                        html += "<div class=\"panel panel-primary\">";
                            html += "<div class=\"panel-heading\"><h3>"+product.name+"</h3></div>";
                            html += "<div class=\"panel-body\"><img src=\""+product.imageURL+" \" class=\"img-responsive\" style=\"width:100%\" alt=\""+product.name+" \"> </div>";
                        html += "</div>";
                        html += "</div>";

                    parentElement.find(".products.container .row").append(html);
                }

            }

        }, "category/findCategoryByName", requestData);
    }


    function ActionFormatter(value) {
        return '<a href="javascript:void(0)" data-category-name="'+ value +'" onclick="loadCategoryDetails(this)">'+ value +'</a>';
    }


</script>

</body>
</html>


