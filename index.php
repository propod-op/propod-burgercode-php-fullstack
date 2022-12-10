<?php
require 'admin/database.php';

$values=array();
$values['name']="";
$values['description']="";
$values['category']="";
$values['price']="";
$values['image']="";


function checkInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function prear($array){
    echo '<pre>';
    echo $array;
    echo '</pre>';
}

function loadValues($itemId){
    global $values;
    $pdo= database::connect();
    $stmt=$pdo->prepare("SELECT * FROM items WHERE id='".$itemId."'");
    $stmt->execute();
    $res = $stmt->fetch();
    
    $values=array();
    $values['name']=$res['name'];
    $values['description']=$res['description'];
    $values['category']=$res['category'];
    $values['price']=$res['price'];
    $values['image']=$res['image'];
    
    $pdo->connection=null;
    
}

function addNav(){
    echo '<div class="row my-3">
    <ul class="nav nav-pills" id="myTab" role="tablist">';
    $pdo= database::connect();
    $stmt=$pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    $res = $stmt->fetchAll();
    
    $active="active";
    foreach($res as $row){
            echo '<li class="nav-item ' .$active .'"><a class="nav-link" data-bs-toggle="tab" href="#'.strtolower($row['name']).'">'.$row['name'].' </a></li>';
            $active="";
        
    }
    echo '</ul></div>';
    $pdo->connection=null;
}


function addTabPanes(){

    $pdo= database::connect();
    $stmt=$pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    $res = $stmt->fetchAll();
    $active="active";
    
    foreach($res as $row){
            //echo '<p class="alert-warning">Category : '.$row['name'].'</p>';
            echo '<div class="tab-pane '. $active . ' fade show" id="'.strtolower($row['name']).'"><div class="row">';
            addItems($row['id']);
            echo '</div></div>';
            $active="";
    }

    $pdo->connection=null;
    
}

function addItems($categoryId){
    
    $pdo= database::connect();
    $stmt=$pdo->prepare("SELECT * FROM items WHERE category='".intval($categoryId)."'");
    $stmt->execute();
    $res = $stmt->fetchAll();
    
   
    foreach($res as $row){
         //echo '<p class="alert-primary">Category : '.$row['category'].' - Name : ' .$row['name'] .'</p>';
        echo'<!-- BURGER CLASSIC -->
            <div class="col-md-4 mt-5">
                <div class="card">
                    <img class="card-img-top" src="images/'.$row['image'].'"" alt="menu-burger-1">
                    <div class="card-body">
                        <h4 class="card-title">"'.$row['name'].'"</h4>
                        <div class="card-text">"'.$row['description'].'"</div>
                        <div class="ribbon-box">
                            <p class="card-price ribbon right-bottom">'.number_format($row['price'],2,'.','').' €</p></div>
                        
                        <a href="#" class="btn btn-order">
                            <span class="fas fa-shopping-basket"></span> Commander
                        </a>   
                    </div>
                </div>
            </div>
            <!-- END BURGER CLASSIC -->';
            
    }

    $pdo->connection=null;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger Code</title>
</head>
    <script src="js/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="js/bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <script type="text/javascript" src="js/bootstrap/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

    
    <!-- FONTS GOOGLE et AWESOMEFONT  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Bree+Serif&display=swap">
    <link rel="stylesheet" href="css/style.css"/>
    
    <script src="https://kit.fontawesome.com/9b26a2995a.js" crossorigin="anonymous"></script>
<body>
    
    <div class="container site">
        
    <div class="row justify-content-center">
        <div class="w-auto"><h1 class="text-red-to-yellow"><span class="fas fa-utensils text-yellow-to-red"></span> Burger Code <span class="fas fa-utensils text-yellow-to-red"></span></h1></div>
    </div>    

        
<!-- RH: this is bootstrap 5 tabbed panel -->
<div class="row">
    <?php addNav(); ?>
</div>        
        
<div class="tab-content mb-5">
  <?php addTabPanes(); ?>
    
    
    
</div>
        
        
</div> <!-- END CONTAINER SITE -->        
</body>
    <script>
    function testJQuery() {
        if (window.jQuery) {  
            // jQuery is loaded  
            alert("Yeah!");
        } else {
            // jQuery is not loaded
            alert("Doesn't Work");
        }
    }
    //testJQuery();
    </script>
</html>

