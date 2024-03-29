<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登入</title>
    <!-- <script src="source/jquery.js"></script>
    <link rel="stylesheet" href="source/bootstrap.css"> -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <style>
        html,
        body {
            font-family: Microsoft JhengHei;
            height: 100%;
        }

        h1 {
            font-weight: normal;

        }

        body {
            background-color: #f5f5f5;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center
        }

        .form-signin {
            margin-top: -100px;
            width: 300px;
            text-align: center;

        }

        .form-control {
            position: relative;
            text-indent: 10px;
            width: 100%;
            height: 35px;
            margin-bottom: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #eee
        }

        .btn {
            width: 100%;
            height: 35px;
            border-radius: 5px;
            background-color: rgb(255, 129, 25);
            color: #fff;
            border: 1px solid #eee
        }
    </style>
</head>

<body>
    {{-- <form class="form-signin"> --}}
        <div class="container">
            <div class="row"><img src="img/kimaker_logo.png" width="260px;" alt=""></div>
            <div class="row" style="margin-top: 60px;"><center><h1>請輸入帳號密碼</h1></center></div>
            <div class="row"><input id="email" type="email" name="email" class="form-control" placeholder="登入帳號" required autofocus></div>
            <div class="row"><input id="password" type="password" name="password" class="form-control" placeholder="登入密碼" required></div>
            <div class="row" id="login_container"><button class="btn" type="submit" id="btn_login">登入</button></div>
            <div class="row" id="update_container" style="display:none;"><button class="btn" id="update_password">更新</button></div>
            <div class="row"><center><p class="mt-5 mb-3 text-muted">&copy; 金智洋科技有限公司開發</p></center></div>
        </div>
</body>
<script>
    $( document ).ready(function() {
        let default_website;
        let token;
        let before=document.referrer;
        console.log(window.location.host);
        //檢查是否有Cookie，如果有的話就自動轉跳
        value_or_null = (document.cookie.match(/^(?:.*;)?\s*sso\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1];
        if(value_or_null){
            location.href = before+'?sso=' + value_or_null;
        }
        $('#btn_login').click(function(){
           let get_email=$('#email').val();
           let get_password=$('#password').val();
           if(get_email !="" && get_password!="")
           {
               let user={
                    email:get_email,
                    password:get_password
               }
                // url: 'https://'+window.location.hostname+'/api/login',
                //http://erp.com/api/public/library
                 $.ajax({
                   type: 'POST',
                   url: 'http://'+window.location.host+'/api/login',
                   async:false,
                   data: user,   
                      success: function(data)
                      {
                        token=data.data.access_token;
                        default_website=data.default_website;
                        //document.cookie = `sso=${token}; max-age=3600; path=/;`;
                        if(before==""){
                            location.href = default_website+'?sso=' + token;
                        }else if(before.split('?')[0] != "https://192.168.39.73:8885/KingMaker" && before.split('?')[0] !='https://211.22.242.18:8885/KingMaker'){
                            location.href = before+'?sso=' + token;
                        }else{
                            location.href = default_website+'?sso=' + token;
                        }
                        // if(before==""){
                        //     location.href = default_website+'?sso=' + token;
                        // }else{
                        //     location.href = before+'?sso=' + token;
                        // }
                      },error: function (xhr, status, error) {
                            let err = JSON.parse(xhr.responseText);
                            alert(err.message);
                            if(err.message=='請更新密碼')
                            {
                                $('#login_container').css('display','none');
                                $('#update_container').css('display','');
                                $('#password').val('');
                            }
                      }
                  });
           }
        });

        $('#update_password').click(function(){
            let get_email=$('#email').val();
           let get_password=$('#password').val();
           if(get_email !="" && get_password!="")
           {
               let user={
                    email:get_email,
                    password:get_password
               }
                 $.ajax({
                   type: 'POST',
                   url: 'https://'+window.location.host+'/api/updatepass',
                   async:false,
                   data: user,   
                      success: function(data)
                      {
                        token=data.data.access_token;
                        default_website=data.default_website;
                        //document.cookie = `sso=${token}; max-age=3600; path=/;`;
                        if(before==""){
                            location.href = default_website+'?sso=' + token;
                        }else if(before.split('?')[0] != "https://192.168.39.73:8885/KingMaker" && before.split('?')[0] !='https://211.22.242.18:8885/KingMaker'){
                            location.href = before+'?sso=' + token;
                        }else{
                            location.href = default_website+'?sso=' + token;
                        }
                      },error: function (xhr, status, error) {
                            let err = JSON.parse(xhr.responseText);
                            alert(err.message);
                      }
                  });
           }
        });
    });
</script>

</html>