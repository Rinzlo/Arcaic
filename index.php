<?php 
declare(strict_types=1);
//include_once "/Applications/MAMP/htdocs/forum/constants.php";
include_once "db.php";
include_once "functions.php";
//ini_set('display_errors', 1);
//session_start();

$parent;
if(!isset($_GET['parent'])){
    $parent = NULL;
}else{
    $parent = $_GET['parent'];
}

echo "<br>";

user();


?>

<?php include "includes/header.php";?>
<body>
    
    <div id="container">
        <br>
        
        <?php 
            $anyPosts = displayPosts($connection, $parent);
            if($anyPosts){
                if(!$parent){
                    echo "<a href=\"post.php?parent=" . $parent . "\">Post</a>";
                }else{
                    echo "<a href=\"post.php?parent=" . $parent . "\">Post a Reply</a>";
                }
            }
        ?>
    </div>

</body>
</html>