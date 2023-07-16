<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blew It</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="container">
        <div class="header-div">
            <h1 class="header-title">Blew It</h1>
            <div class="header-links">
                <button class="nav"><a href="login.php">Login</a></button>
                <button class="nav"><a href="register.php">Register</a></button>
            </div>
        </div>

        <div class="upload-div">
            <form action="upload.php" method="post" class="upload-form">

                <textarea name="upload-val" id="upload-text" cols="30" rows="5" placeholder="Text" required></textarea>
                <!-- We will maybe change the format on how the user chooses a sublewit. Maybe text input or loop through sublewit for select -->
                <select name="sublewit-val" id="upload-sublewit" required>
                    <option value="sports">Sports</option>
                    <option value="news">News</option>
                    <option value="other">Other</option>
                </select>

                <button class="upload-button" type="submit">Post</button>
            </form>
        </div>

        <div class="posts-container">
            <div class="post-div">
                <span class="topspan">
                    <h2 class="post-user">Packersfan</h2>
                    <p class="post-sublewit"><i>Sports</i></p>
                </span>
                <span class="topspan">
                    <p class="post-content">The packers are a very cool team! <a href="post.php"> Click to see post</a></p>
                    <!-- Use a get to go to post hrer -->
                    
                </span>
                <span class="bottomspan">
                    <p class="post-upvotes">Upvotes</p>
                    <p class="post-upvotes-total">0</p> 
                </span>
                <span class="bottomspan">
                    <p class="post-downvotes">Downvotes</p>
                    <p class="post-downvotes-total">0</p>
                </span>
                
                    
                
            </div>

            <div class="post-div">
                <span class="topspan">
                    <h2 class="post-user">Packersfan</h2>
                    <p class="post-sublewit"><i>Sports</i></p>
                </span>
                <span class="topspan">
                    <p class="post-content">The packers are a very cool team! <a href="post.php"> Click to see post</a></p>
                    <!-- Use a get to go to post hrer -->
                    
                </span>
                <span class="bottomspan">
                    <p class="post-upvotes">Upvotes</p>
                    <p class="post-upvotes-total">0</p> 
                </span>
                <span class="bottomspan">
                    <p class="post-downvotes">Downvotes</p>
                    <p class="post-downvotes-total">0</p>
                </span>
        </div>
    </div>
</body>

</html>