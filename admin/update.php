<?php
        require 'database.php';
        
        $values=array();
        $values['id']="";
        $values['name']="";
        $values['description']="";
        $values['category']="";
        $values['price']="";
        $values['image']="";

        $errors=array();
        $errors['name']="";
        $errors['description']="";
        $errors['category']="";
        $errors['price']="";
        $errors['image']="";
        
        $isUploadSuccess=true;
        $isSuccess =true;
        $isImageUpdated=false;

        if(!empty($_GET['id'])){
            $values['id']=$_GET['id'];
        }
    
        function loadInformations(){
            global $values;
            $pdo = database::connect();
            $stmt = $pdo->prepare("SELECT * FROM items WHERE id={$values['id']}");
            $stmt->execute();
            $res=$stmt->fetch();
            
            $values['name']=$res['name'];
            $values['description']=$res['description'];
            $values['category']=$res['category'];
            $values['price']=$res['price'];
            $values['image']="/images/".$res['image'];
        }    
        
        function checkInput($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        function checkImage($vals,$errs){
            global $values,$errors,$isUploadSuccess;
            if($values['image_extension'] != "jpg"
               && $values['image_extension'] != "png"
               && $values['image_extension'] !="jpeg"
               && $values['image_extension'] != "gif")
            {
                $errors['image'] = "Les fichiers autorisés sont : .jpg, .jpeg, .png, .gif";
                $isUploadSuccess = false;
            }

            if(file_exists($values['image_path'])){
                $errors['image']="Le fichier existe déjà.";
                $isUploadSuccess = false;
            }

            if($_FILES['image']['size']>500000){
                $errors['image']="Le fichier doit faire moins de 500KB.";
                $isUploadSuccess = false;
            }else{
                $isUploadSuccess = true;
            }

            if($isUploadSuccess){
                if(!move_uploaded_file($_FILES['image']['tmp_name'],$values['image_path'])){
                    $errors['image']= "Il y eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                }
            }
           return true;
        }

        function prear($array){
            echo '<pre>';
            var_dump($array);
            echo '</pre>';
            return true;
        }
    
        // -> DEUXIEME PASSAGE
        if(!empty($_POST)){

            $values['name']=checkInput($_POST['name']);
            $values['description']=checkInput($_POST['description']);
            $values['price']=checkInput($_POST['price']);
            $values['category']=checkInput($_POST['category']);
            
            $values['image']=checkInput($_FILES['image']['name']);
            
            $values['image_path']='../images/'.basename($values['image']);
            $values['image_extension']=pathinfo($values['image_path'], PATHINFO_EXTENSION);
            
            $isSuccess = true;
            $isUploadSuccess=true;
            $defMessError="Le champ ne peut pas être vide.";
            
            
            // CHECK DES INPUTS
            if(empty($values['name'])){
                $errors['name']= $defMessError;
                $isSuccess = false;
            }
            
            if(empty($values['description'])){
                $errors['description']= $defMessError;
                $isSuccess = false;
            }
            
            if(empty($values['price'])){
                $errors['price']= $defMessError;
                $isSuccess = false;
            }
            
            if(empty($values['category'])){
                $errors['category']= $defMessError;
                $isSuccess = false;
            }
            
            // CHECK DU FICHIER IMAGE
            //checkImage($values,$errors);
            
            // ENVOI DU FICHIER IMAGE
            if(($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated))
            {
                $pdo = database::connect();
                if($isImageUpdated)
                {
                    /*$stmt = $pdo->prepare("UPDATE items SET name=:name, description=:description, price=:price, category=:category, image=:image WHERE id=:id");
                    $stmt->bindParam('name', $values['name'], PDO::PARAM_STR);
                    $stmt->bindParam('description', $values['description'], PDO::PARAM_STR);
                    $stmt->bindParam('price', $values['price'], PDO::PARAM_STR);
                    $stmt->bindParam('category', $values['category'], PDO::PARAM_INT);
                    $stmt->bindParam('image', $values['image'], PDO::PARAM_STR);
                    $stmt->execute();
                    */
                }else{
                                    
                    $stmt = $pdo->prepare("UPDATE items SET name=:name, description=:description, price=:price, category=:category WHERE id=:id");
                    $stmt->bindParam('name', $_POST['name'], PDO::PARAM_STR);
                    $stmt->bindParam('description', $_POST['description'], PDO::PARAM_STR);
                    $stmt->bindParam('price', $_POST['price'], PDO::PARAM_STR);
                    $stmt->bindParam('category', $_POST['category'], PDO::PARAM_INT);
                    $stmt->bindParam('id', $values['id'], PDO::PARAM_INT);
                    $stmt->execute();
                }

                $pdo->connection=null;
                //header('Location: index.php');
            }
            elseif($isImageUpdated && !$isUploadSuccess){
                
            }
            
        // -> PREMIER PASSAGE  
        }else{
            loadInformations();
        }

?>

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
    
    <!-- TITLE  -->
    <div class="row mb-4">
        <h3 class="justify-content-center text-center"><strong> Modifier un produit<br>"<?php echo $values['name'];?>"</strong></h3>
    </div>
    <!-- END TITLE  -->   
    
    <!-- CONTENU  -->
        <div class="row">
            
             <!-- COLONNE GAUCHE -->
            <div class="col-md-6">
                
                <form class="form" role="form" action="<?php echo 'update.php?id='.$values['id']; ?>" method="POST" enctype="multipart/form-data">
                    
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input id="name" type="text" class="form-control" name="name" placeholder="Nom du produit" value="<?php echo $values['name'];?>">
                    <span class="help-inline"><?php echo $errors['name']; ?></span>
                </div>
            
                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="description" type="text" class="form-control" name="description" placeholder="Description du produit" value="<?php echo $values['description'];?>">
                    <span class="help-inline"><?php echo $errors['description']; ?></span>
                </div>
                    
                <div class="form-group">
                    <label for="price">Prix</label>
                    <input id="price" type="text" class="form-control" name="price" placeholder="Prix du produit" value="<?php echo $values['price'];?>">
                    <span class="help-inline"><?php echo $errors['price']; ?></span>
                </div>
                
                <!-- CATEGORIE -->    
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select class="form-control" id="category" name="category">
                            <?php
                                
                                $pdo=database::connect();    
                                $stmt=$pdo->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                while($row = $stmt->fetch()){
                                        echo "<option value='".$row['id']."'>".$row['name']."</option>";
                                
                                }
                                

                            ?>
    
                    </select>
                    <span class="help-inline"><?php echo $errors['category']; ?></span>
                </div>
                    
                <div class="form-group">
                    <label for="image">Fichier image</label>
                    <input id="image" type="file" class="form-control" name="image" placeholder="Image du produit" value="<?php echo $values['image'];?>">
                    <span class="help-inline"><?php echo $errors['image']; ?></span>
                </div>              
                
                <div class="form-actions">
                    <button id="ajouter" name="ajouter" type="submit" class="btn btn-success mt-3 mx-auto  w-25"><span class='fa fa-pencil-alt me-2'></span>Modifier</button>  

                </div>
                    
                </form>
                
            </div>
             <!-- END COLONNE GAUCHE -->
            
            
            <!-- COLONNE DROITE -->
            <div class="col-md-6">
                <div class="card">
                    
                    <img class="card-img-top" src="../<?php echo $values['image'];?>" alt="menu-item"/>
                    
                    
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
             <!-- END COLONNE DROITE -->
            
        </div>
         <!-- END CONTENU  -->
        
         <!-- RETOUR  -->
        <div class="row mt-5 text-center">
                <div class="form-actions">
                    <a href="index.php" class="btn btn-primary"><span class='fa fa-arrow-left'></span> Retour</a>
                </div>
        </div>
         <!-- END RETOUR  -->
        
        </div>
        <!-- END MAIN CONTAINER -->
    </body>
</html>