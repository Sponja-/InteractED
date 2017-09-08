<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title></title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../css/input.css">
        <link rel="stylesheet" href="../css/font.css">
    </head>
    <body>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 810" preserveAspectRatio="xMinYMin slice" style="position: absolute; width: 100%; height: 100%;">
            <path fill="#efefee" d="M592.66 0c-15 64.092-30.7 125.285-46.598 183.777C634.056 325.56 748.348 550.932 819.642 809.5h419.672C1184.518 593.727 1083.124 290.064 902.637 0H592.66z"></path>
            <path fill="#f6f6f6" d="M545.962 183.777c-53.796 196.576-111.592 361.156-163.49 490.74 11.7 44.494 22.8 89.49 33.1 134.883h404.07c-71.294-258.468-185.586-483.84-273.68-625.623z"></path>
            <path fill="#f7f7f7" d="M153.89 0c74.094 180.678 161.088 417.448 228.483 674.517C449.67 506.337 527.063 279.465 592.56 0H153.89z"></path>
            <path fill="#fbfbfc" d="M153.89 0H0v809.5h415.57C345.477 500.938 240.884 211.874 153.89 0z"></path>
            <path fill="#ebebec" d="M1144.22 501.538c52.596-134.583 101.492-290.964 134.09-463.343 1.2-6.1 2.3-12.298 3.4-18.497 0-.2.1-.4.1-.6 1.1-6.3 2.3-12.7 3.4-19.098H902.536c105.293 169.28 183.688 343.158 241.684 501.638v-.1z"></path>
            <path fill="#e1e1e1" d="M1285.31 0c-2.2 12.798-4.5 25.597-6.9 38.195C1321.507 86.39 1379.603 158.98 1440 257.168V0h-154.69z"></path>
            <path fill="#e7e7e7" d="M1278.31,38.196C1245.81,209.874 1197.22,365.556 1144.82,499.838L1144.82,503.638C1185.82,615.924 1216.41,720.211 1239.11,809.6L1439.7,810L1439.7,256.768C1379.4,158.78 1321.41,86.288 1278.31,38.195L1278.31,38.196z"></path>
        </svg>

        <div class="valign-wrapper" style="height: 100%; width: 100%; position: absolute;">
            <div class="row" style="width: 100%;">
                <div class="col s12 m6 offset-m3">
                    <div class="card">
                        <div class="card-content black-text">
                            <span style="font-size: 1.5rem; font-family: and_black;" class="blue-text">InteractED</span>
                            <h1 style="font-size: 24px; font-weight: 400; line-height: 32px; margin: 0">Iniciar sesi&oacute;n</h1>
                            <div class="input-field" style="margin-top: 10%;">
                                <input id="user" type="text">
                                <label for="user">Usuario o correo electr&oacute;nico</label>
                            </div>
                            <div class="input-field" style="margin-bottom: 10%;">
                                <input id="password" type="password">
                                <label for="password">Contrase&ntilde;a</label>
                            </div>
                            <a class="btn-flat blue-text waves-effect">Registrarse</a>
                            <a id="next" class="btn blue waves-effect waves-light right">Siguiente</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script>
            $( "#next" ).click(function() {
                var User = $( "#user" ).val();
                var Password = $( "#password" ).val();

                $.ajax({
                    url: "login.php",
                    type: "POST",
                    data: { User: User, Password: Password } ,
                    success: function (response) {
                        if (response == '1')
                            window.location.replace("../");
                        else
                            alert("Error");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });
        </script>
    </body>
</html>