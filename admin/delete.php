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
            $values['id']=checkInput($_GET['id']);
        }
    
        function checkInput($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
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
            $pdo->connection=null;
        }

        

        if(!empty($_POST)){
            $values['id']=$_POST['id'];
            $pdo = database::connect();
            $stmt = $pdo->prepare("DELETE FROM items WHERE id='".$values['id']."'");
            $stmt->execute();
            $pdo->connection=null;
            header('Location: index.php');
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
        <h3 class="justify-content-center text-center"><strong> Supprimer un produit<br>"<?php echo $values['name'];?>"</strong></h3>
    </div>
    <!-- END TITLE  -->   
    
    <!-- CONTENU  -->
        <div class="row">
            
            <form class="form" role="form" action="delete.php" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $values['id']; ?>">
                
                <p class="alert-warning p-4 border"><span class='fa fa-trash'></span> Êtes-vous sûr de vouloir supprimer : <strong><?php echo $values['name']; ?> ?</strong></p>             
                      
                
                <div class="form-actions text-center">
                    <button id="oui" name="oui" type="submit" class="btn btn-success me-3 pe-2 mx-auto" style="width:100px;"><span class='fa fa-user-check'></span> Oui</button>  
                 
                    <a href="index.php" class="btn btn-primary mx-auto" style="width:100px;"><span class='fa fa-arrow-left'></span> Annuler</a>
                    
                </div>    
                    
                </form>

    </div>
        </div>
    </body>
</html>