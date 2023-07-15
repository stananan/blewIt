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
                <h2 class="header-login"><a href="login.php">Login</a></h2>
                <h2 class="header-register"><a href="register.php">Register</a></h2>
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
                <h2 class="post-user">Packersfan</h2>
                <h4 class="post-sublewit"><i>Sports</i></h4>
                <p class="post-content">The packers are a very cool team</p>
                <!-- Use a get to go to post hrer -->
                <p class="post-link"><a href="post.php">Click to see post</a></p>

                <h3 class="post-upvotes">Upvotes</h3>
                <h3 class="post-upvotes-total">0</h3>
                <h3 class="post-downvotes">Downvotes</h3>
                <h3 class="post-downvotes-total">0</h3>
            </div>

            <div class="post-div">
                <h2 class="post-user">Packersfan</h2>
                <h4 class="post-sublewit">Sports</h4>
                <p class="post-content">The packers are a very cool team</p>
                <!-- Use a get to go to post hrer -->
                <p class="post-link"><a href="post.php">Click to see post</a></p>

                <h3 class="post-upvotes">Upvotes</h3>
                <h3 class="post-upvotes-total">0</h3>
                <h3 class="post-downvotes">Downvotes</h3>
                <h3 class="post-downvotes-total">0</h3>
            </div>
        </div>
    </div>
</body>

</html>