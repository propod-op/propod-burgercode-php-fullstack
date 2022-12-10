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
            <h3 class="justify-content-center text-center"><strong> Ajouter un produit</strong></h3>
        </div>
        <!-- END TITLE -->
    <?php
        require 'database.php';
        
        $values=array();
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
        
        $values['image_path']="";
        $values['image_extension']="";
        
        $isUploadSuccess=true;
        
 
        
        if(!empty($_POST)){
        
            $values['name']=checkInput($_POST['name']);
            $values['description']=checkInput($_POST['description']);
            $values['category']=checkInput($_POST['category']);
            $values['price']=checkInput($_POST['price']);
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
            checkImage($values,$errors);
            
            // ENVOI DU FICHIER IMAGE
            if($isSuccess && $isUploadSuccess){
                //echo "L'opération est un succès !";
                $pdo = database::connect();
                

                $sql="INSERT INTO items (`id`,`name`,`description`,`category`,`price`,`image`) VALUES (null, :name, :description, :category, :price, :image)";
                $stmt=$pdo->prepare($sql);
                
                $stmt->bindParam('name', $values['name'], PDO::PARAM_STR);
                $stmt->bindParam('description', $values['description'], PDO::PARAM_STR);
                $stmt->bindParam('price', $values['price'], PDO::PARAM_STR);
                $stmt->bindParam('category', $values['category'], PDO::PARAM_INT);
                $stmt->bindParam('image', $values['image'], PDO::PARAM_STR);
                
                $stmt->execute();
                database::disconnect();
                header('Location: index.php');
            }
            
            
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
    ?>  
        <!-- CONTENT -->
        <div class="row">
            <div class="col-sm-6 mx-auto">
                
                <form class="form" role="form" action="insert.php" method="POST" enctype="multipart/form-data">
                    
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
                
                    
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <input id="category" type="text" class="form-control" name="category" placeholder="Catégorie du produit" value="<?php echo $values['category'];?>">
                    <span class="help-inline"><?php echo $errors['category']; ?></span>
                </div>
                    
                <div class="form-group">
                    <label for="image">Image</label>
                    <input id="image" type="file" class="form-control" name="image" placeholder="Image du produit" value="<?php echo $values['image'];?>">
                    <span class="help-inline"><?php echo $errors['image']; ?></span>
                </div>              
                
                <div class="form-actions text-center">
                    <button id="ajouter" name="ajouter" type="submit" class="btn btn-success mt-3 mx-auto  w-25"><span class='fa fa-plus-circle'></span> Ajouter</button>  
                 
                <a href="index.php" class="btn btn-primary mt-3 ms-2 w-25"><span class='fa fa-arrow-left'></span> Retour</a>
                </div>
                    
                </form>
            </div>
        </div>
        <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
    </body>
    
    
</html>