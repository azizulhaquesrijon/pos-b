<!DOCTYPE html>
<html>

<head>
    <title>Smart-Dokani Desktop</title>
    <link rel="icon" href="{{ $fav_icon ?? 'icon.png' }}" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
        integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110599322-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-110599322-1');
    </script>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            background: whitesmoke;
        }

        .user_card {
            height: 450px;
            width: 350px;
            margin-top: auto;
            margin-bottom: auto;
            background: #e7e9ff;
            position: relative;
            display: flex;
            justify-content: center;
            flex-direction: column;
            padding: 10px;
            border: 1px solid rgb(212, 196, 196);
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 5px;

        }

        .brand_logo_container {
            position: absolute;
            height: 170px;
            width: 170px;
            top: -75px;
            border-radius: 50%;
            /* background: url(images/pattern.jpg) #DFE0E2; */
            background: #fff;
            padding: 10px;
            text-align: center;
        }

        .brand_logo {
            margin-left: 4px;
            height: 150px;
            width: 150px;
            /* border-radius: 50%; */
            border: 0px solid white;
        }

        .form_container {
            margin-top: 100px;
        }

        .login_btn {
            width: 100%;
            background: #FB693B !important;
            color: white !important;
            font-size: 18px;
            font-weight: bold;
            font-family: 'Sansita Swashed', cursive;
            text-shadow: 1px 1px rgb(71, 70, 70);
        }

        .login_btn:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }

        .login_container {
            padding: 0 2rem;
        }

        .input-group-text {
            background: #f74107 !important;
            color: white !important;
            border: 0 !important;
            border-radius: 0.25rem 0 0 0.25rem !important;
            font-weight: 800;
            text-shadow: 1px 1px rgb(71, 70, 70);

        }

        .input_user,
        .input_pass:focus {
            box-shadow: none !important;
            outline: 0px !important;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
            background-color: #c0392b !important;
        }

    </style>
</head>
<!--Coded with love by Mutiullah Samim-->

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="/assets/images/logo.png" class="brand_logo" alt="Logo">
                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form action="{{ route('signin') }}" method="POST">
                        @csrf
                        @if (\Session::has('message'))
                            <div class="alert alert-danger" style="padding: 6px;margin: 0px;">
                                {!! \Session::get('message') !!}
                            </div>
                        @endif
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-phone fa-flip-horizontal"></i></span>
                            </div>
                            <input type="text" {{-- maxlength="11" --}} name="mobile" class="form-control input_user"
                                placeholder="ex. 01xxxxxxxx" {{-- onkeypress="return onlyNumber(event)" --}} required>

                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key fa-flip-horizontal"></i></span>
                            </div>
                            <input type="password" name="pin" class="form-control input_pass" value=""
                                placeholder="Password" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" class="btn login_btn">Login</button>
                        </div>
                        <div>
                            <p class="text-danger" style="margin-top: 10px;"><strong> Forget Password ? <a
                                        href="https://play.google.com/store/apps/details?id=com.smartsoftwarebd.dokani"
                                        target="_blank">Check Mobile App</a> </strong></p>
                            <p class="text-info" style="margin-top: 10px;"><strong> Don't Have Account ? <a
                                        href="https://www.smart-dokani.com/signup" target="_blank">Register Here</a>
                                </strong></p>
                        </div>
                    </form>
                </div>
                <div>
                    <hr />
                    <p style="color: blueviolet; text-align:center;">
                        <strong> Develop By: Smart Software Ltd.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function onlyNumber(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                alert("Invalid");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
