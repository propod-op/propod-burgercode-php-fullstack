<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burger Code</title>
</head>
    <script src="../js/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="../js/bootstrap/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <script type="text/javascript" src="js/bootstrap/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

        <!-- FONTS GOOGLE et AWESOMEFONT  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Bree+Serif&display=swap">
     
    <!-- MES STYLES  -->
    <link rel="stylesheet" href="../css/style.css"/>
    
    <script src="https://kit.fontawesome.com/9b26a2995a.js" crossorigin="anonymous"></script>
    <body>
    
    <!-- MAIN CONTAINER -->
    <div class="container admin bg-light mt-5">
        
        <!-- TITLE -->
        <div class="row mb-4">
            <h3 class="justify-content-center text-center"><strong> Description du produit</strong></h3>
        </div>
        <!-- END TITLE -->
        
    <?php
        require 'database.php';
        
        if(!empty($_GET['id'])){
            $id=checkInput($_GET['id']);
            
            $pdo=database::connect();
            
            $stmt = $pdo->prepare('SELECT items.id, items.name, items.description, items.price,items.image, categories.name AS category FROM items LEFT JOIN categories ON items.category = categories.id WHERE items.id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $item = $stmt->fetch();
            
            database::disconnect();
        }

        function checkInput($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    ?>  
        <!-- CONTENU -->
        <div class="row">
            <div class="col-md-6">
                
                <form>

                
                <div class="form-group">
                <label><strong>Description :</strong> <?php echo '  '.$item['description']; ?></label>
                </div>
                
                <div class="form-group">
                <label>
                    <strong>prix : </strong><?php echo '  '.number_format($item['price'],2,'.','')." €"; ?></label>
                </div>
                
                <div class="form-group">
                    <label><strong>Catégorie : </strong>strong><?php echo '  '.$item['category']; ?> </label>
                </div>
            
                </form>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    
                    <img class="card-img-top" src="../images/<?php echo $item['image'];?>" alt="menu-item"/>
                    
                    
                    <div class="card-body">
                        <h4 class="card-title">Menu Classic</h4>
                        <div class="card-text">Sandwich : Burger, salade, Tomate, Cornichon + Frites</div>
                        <div class="ribbon-box"><p class="card-price ribbon right-bottom">8.90 €</p></div>
                        
                        <a href="#" class="btn btn-order">
                            <span class="fas fa-shopping-basket"></span> Commander
                        </a>   
                    </div>
                </div>
            </div>      
        </div>
        <!-- END CONTENU -->
        
        <!-- RETOUR -->
        <div class="row mt-5 text-center">
                <div class="form-actions">
                    <a href="index.php" class="btn btn-primary"><span class='fa fa-arrow-left'></span> Retour</a>
                </div>
        </div>
        <!-- RETOUR -->
        </div>
        <!-- END MAIN CONTAINER -->
    </body>
    
    
</html>