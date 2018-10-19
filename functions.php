<?php
// shows all users
function showUserData($connection)
{
    // select all from users
    $query = "SELECT * FROM users";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query Failed' . mysqli_error());
    }

    while ($row = mysqli_fetch_assoc($result)) {

        $id = $row['id'];

        echo "<option value='$id'>$id</option>";

    }

}

function retrievePosts($connection)
{
    $query = "SELECT * FROM posts";

    $posts = mysqli_query($connection, $query);

    if (!$posts) {
        die("query failed: " . mysqli_error($connection));
    }

    return $posts;
}

function displayPost($connection, $post, $parent = null)
{
    foreach ($post as $key => $value) {
        // only show relevant rows
        if ($key !== "id" && $key !== "parent") {
            echo "<h3>" . $key . "</h3>";
            echo "<BLOCKQUOTE>";
            echo $value;
            echo "</BLOCKQUOTE>";
            echo "<br>";
        }
    }

    $id = $post['id'];
    $author = $post['author'];
    echo "ID: $id <br>";

    if ($author == $_COOKIE['username']) {
        ?><a href="deletePost.php?id=<?php echo $id ?>&parent=<?php echo $parent ?>">Delete</a><br><?php
} else {
        ?><a href="post.php?parent=<?php echo $id ?>&author=<?php echo $author ?>">Reply</a><br><?php
}

    ?>
        <a href="index.php?parent=<?php echo $id ?>">Show Comments</a>
    <?php
/* */

}

function displayPosts($connection, $parent = null)
{
    if ($parent) {
        ?><h1>PARENT</h1><?php
$query = "SELECT * FROM posts WHERE id = '$parent'";

        $post = mysqli_fetch_assoc(mysqli_query($connection, $query));

        displayPost($connection, $post, $parent);
        echo "<br><br><br>";

        $query = "SELECT * FROM posts WHERE parent = '$parent'";
    } else {
        $query = "SELECT * FROM posts WHERE parent IS NULL";
    }

    $posts = mysqli_query($connection, $query);

    if (!$posts) {
        die("query failed: " . mysqli_error($connection));
    }

    $post_count = mysqli_num_rows($posts);
    echo "There are " . $post_count . " posts";
    if ($post_count == 0) {
        return false;
    }

    echo "<br>";
    while ($post = mysqli_fetch_assoc($posts)) {
        ?><BLOCKQUOTE><?php

        ?><h1>POST</h1><?php
displayPost($connection, $post, $parent);
        ?><br><br><br><?php

        ?></BLOCKQUOTE><?php
}
    return true;
}

function user()
{
    if (isset($_COOKIE['email']) && isset($_COOKIE['username'])) {
        $email = $_COOKIE['email'];
        $username = $_COOKIE['username'];

        echo $email?> logged in
        <br>
        Welcome <?php echo $username ?>
        <br>
        <a href=editAccount.php>edit</a>
        <br>
        <a href=logout.php>logout</a>
        <?php

    } else {
        ?><a href=login.php>login</a>   or  <a href=register.php>register</a><?php
}
}

// sets cookie
function login($connection)
{
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $password = hash('sha512', $password);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $query);

    echo "<br>";

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            if ($user['password'] == $password) {
                $username = $user['username'];
                echo "$email logged in<br>Welcome $username";

                // TODO: set email and username cookies
                setcookie('email', $email, time() + (86400 * 30), "/");
                setcookie('username', $username, time() + (86400 * 30), "/");

                header("Location: index.php");
                die();
            } else {
                // ultra secure convinience line right here!
                echo "Incorrect password: $password...<br>did you mean: " . $user['password'] . "?";
            }
        }
    }
}

function registerUser($connection)
{
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $password = hash('sha512', $password);

    $email_query = "SELECT * FROM users WHERE email = '$email'";

    $email_result = mysqli_query($connection, $email_query);

    $user_by_email = mysqli_fetch_assoc($email_result);

    if (!$user_by_email) {
        $name_query = "SELECT * FROM users WHERE username = '$username'";

        $name_result = mysqli_query($connection, $name_query);

        $user_by_name = mysqli_fetch_assoc($name_result);

        if (!$user_by_name) {

            $query = "INSERT INTO users(email, firstname, lastname, username, password) ";
            $query .= "VALUES ('$email', '$firstname', '$lastname', '$username', '$password')";

            $result = mysqli_query($connection, $query);

            if (!$result) {
                die('Query Failed' . mysqli_error($connection));
            } else {
                echo "Congrats $firstname $lastname, you created an account with<br>email: $email<br>and<br>username: $username!";

                setcookie('email', $email, time() + (86400 * 30), "/");
                setcookie('username', $username, time() + (86400 * 30), "/");

                header("Location: index.php");
                die();
            }
        } else {
            echo "$username already exists, try another.";
        }
    } else {
        echo "$email already exists";
    }
}

function editUser($connection)
{
    $prev_email = $_COOKIE['email'];
    $prev_username = $_COOKIE['username'];
    $query = "SELECT * FROM users WHERE email = '$prev_email'";
    $user = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($user);
    $id = $row['id'];

    $new_email = mysqli_real_escape_string($connection, $_POST['email']);
    $new_username = mysqli_real_escape_string($connection, $_POST['username']);

    $query = "UPDATE users SET email='$new_email' WHERE id='$id'";
    $result = mysqli_query($connection, $query);
    //check result

    $query = "UPDATE users SET username='$new_username' WHERE id='$id'";
    $result = mysqli_query($connection, $query);
    //check result

    // change all posts to correct author
    //$query = "SELECT * FROM posts WHERE author = '$prev_username'";
    $query = "UPDATE posts SET author='$new_username' WHERE author='$prev_username'";
    $result = mysqli_query($connection, $query);

    setcookie('email', $new_email, time() + (86400 * 30), "/");
    setcookie('username', $new_username, time() + (86400 * 30), "/");
}

function post($connection, $parent = null)
{
    $title = $_POST['title'];
    $author = $_COOKIE['username'];
    $date = date("Y-m-d");
    $body = $_POST['body'];

    $query = "";

    /* */
    if ($parent) {
        $query .= "INSERT INTO posts(parent, title, author, date, body) ";
        $query .= "VALUES ($parent, '$title', '$author', '$date', '$body')";
    } else {
        $query .= "INSERT INTO posts(title, author, date, body) ";
        $query .= "VALUES ('$title', '$author', '$date', '$body')";
    }
    /* */

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query Failed: ' . mysqli_error($connection));
    } else {
        header("Location: index.php?parent=$parent");
        die();
    }
}

function deletePost($connection, $id)
{
    $query = "DELETE FROM posts WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die('Query Failed: ' . mysqli_error($connection));
    }
}

?>