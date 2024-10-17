<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanact Media</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/"><img src="https://kanact.com/image/kanact-logo.png" class="w-100 logo"
                    alt="kanact-logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">


            </div>
        </div>
    </nav>
    <div class="container topRated">
        <div class="results-wrap mt-5">
            <h2 class="text-dark">Channels</h2>
            <hr class="bg-dark">

            <div class="channels-list my-2">
                @foreach($channels as $channel)
                <a href="{{$channel->url}}" class="text-decoration-none">
                    <div class="single-channel">
                        <img src="https://kanact-media.fra1.cdn.digitaloceanspaces.com/{{$channel->logo}}" alt="">
                    </div>
                    <p class="text-dark mt-2 text-center">{{$channel->name}}</p>
                </a>
                @endforeach
            </div>

        </div>

    </div>


    <div id="toast">
        <div id="desc">Content Restricted for this User</div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>


    <script>
        function launch_toast() {
            var x = document.getElementById("toast")
            x.className = "show";
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 5000);
        }
    </script>
</body>

</html>
