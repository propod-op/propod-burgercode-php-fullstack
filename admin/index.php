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
    <link rel="stylesheet" href="../css/style.css"/>
    
    <script src="https://kit.fontawesome.com/9b26a2995a.js" crossorigin="anonymous"></script>
<body>
    
    <!-- TITLE BURGER CODE -->
    <div class="row justify-content-center">
        <div class="w-auto"><h1 class="text-red-to-yellow"><span class="fas fa-utensils text-yellow-to-red"></span> Burger Code <span class="fas fa-utensils text-yellow-to-red"></span></h1></div>
    </div>   
    <!-- TITLE BURGER CODE -->
    <div class="container admin bg-light">
        <div class="row">
            <h3><strong> Liste des items</strong> <a href="insert.php" class="btn btn-success ms-2"><span class="fas fa-plus-circle"></span> Ajouter</a></h3>
        
        </div>
    
        <div class="row">
            <table class="table table-striped table-bordered ">
               <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Catégorie</th>
                    <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                    require "database.php";
                    $db = database::connect();
                    $stmt=$db->query("SELECT items.id, items.name, items.description, items.price, categories.name AS category
                     FROM items LEFT JOIN categories ON items.category = categories.id
                     ORDER BY items.id DESC");
                    while($item = $stmt->fetch()){
                        
                        echo "<tr>";
                        echo "<td>{$item['id']}</td>";
                        echo "<td><strong>{$item['name']}</strong></td>";
                        echo "<td>{$item['description']}</td>";
                        echo "<td>".number_format($item['price'],2,'.','')." €"."</td>";
                        echo "<td>{$item['category']}</td>";
                                            
                        
                        echo '<td width=350>';
                        
                        echo "<a href='view.php?id={$item['id']}' class='btn btn-light border-secondary me-2' role='button'><span class='fa fa-eye'></span> Voir</a>";
                        
                        echo "<a href='update.php?id={$item['id']}' class='btn btn-primary me-2' role='button'><span class='fa fa-pen'></span> Modifier</a>";
                        
                        echo "<a href='delete.php?id={$item['id']}' class='btn btn-danger' role='button'><span class='fa fa-trash-alt'></span> Supprimer</a>";
                        
                        echo '</td></tr>';
                    }
                  database::disconnect();

                ?>
                <tr>


                </tr>
              </tbody>
            </table>
        </div>
    
    </div>
    
    
    
</body>
</html>