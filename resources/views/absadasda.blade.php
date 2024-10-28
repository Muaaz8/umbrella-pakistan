<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script

    src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
    integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    ></script>
</head>
<body>
        Hello World
        <div class="error">

        </div>
</body>

<script>
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "https://chatbot.umbrellamd-video.com/chat",
            data: JSON.stringify({ message: "backpain"}),
            contentType:"application/json;",
            dataType: "json",
            success: function (response) {
                $('.error').append(response.error);
                console.log(response);
            }
        });
    });

</script>
</html>
