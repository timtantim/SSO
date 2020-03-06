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
    <form class="form-signin">
        <h1>請輸入帳號密碼</h1>
        <input id="email" type="email" name="email" class="form-control" placeholder="登入帳號" required autofocus>
        <br>
        <input id="password" type="password" name="password" class="form-control" placeholder="登入密碼" required>
        <div class="checkbox mb-3">
        </div>
        <button class="btn" type="submit" id="btn_login">登入</button>
        <p class="mt-5 mb-3 text-muted">&copy; 金智洋科技有限公司開發</p>
    </form>
</body>
<script>
    $( document ).ready(function() {
        let before=document.referrer;
        console.log(window.location.host);
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
                   url: 'https://'+window.location.host+'/api/login',
                   data: user,   
                      success: function(data)
                      {
                        // let expire_days = 1; // 過期日期(天)
                        // var d = new Date();
                        // d.setTime(d.getTime() + (expire_days * 24 * 60 * 60 * 1000));
                        // var expires = "expires=" + d.toGMTString();
                        // document.cookie = "sso="+data.data.access_token + "; " + expires + ';domain=.erp.com; path=/';
                        location.href = before+'?sso=' + data.data.access_token;
                        // location.href = before+'?sso=123';
                      },
                      error: function(e)
                      {
                        alert('帳密錯誤');
                      }
                  });
           }
        });

    });
</script>

</html>