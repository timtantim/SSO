<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>註冊</title>
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

            <div class="row"><h1>註冊用戶</h1></div>
            <div class="row"><input id="name" type="text" name="name" class="form-control" placeholder="姓名" required autofocus></div>
            <div class="row"><input id="email" type="email" name="email" class="form-control" placeholder="Email" required autofocus></div>
            <div class="row"><input id="password" type="password" name="password" class="form-control" placeholder="密碼" required></div>
            <div class="row"><input id="password-confirm" type="password" name="password-confirm" class="form-control" placeholder="密碼確認" required></div>
            <div class="row">
                <div class="form-group">
                    <select class="form-control" id="default_factory"></select>
                </div>
            </div>
            <div class="row">
               <select multiple class="form-control" id="user_system_select" style="height:100px;">
               </select>
            </div>
            <div class="row">
                <div class="form-group">
                    <select class="form-control" id="default_system"></select>
                </div>
            </div>
            <div class="row"><button class="btn" type="submit" id="btn_register">註冊</button></div>
            <div class="row"><p class="mt-5 mb-3 text-muted">&copy; 金智洋科技有限公司開發</p></div>
        </div>
</body>
<script>
    $( document ).ready(function() {
        let default_website;
        let token;
        let before=document.referrer;
        //console.log(window.location.host);
        //檢查是否有Cookie，如果有的話就自動轉跳
        value_or_null = (document.cookie.match(/^(?:.*;)?\s*sso\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1];
        if(value_or_null){
            location.href = before+'?sso=' + value_or_null;
        }

        // 
        $.ajax({
                   type: 'POST',
                   url: 'http://'+window.location.host+'/api/loadfactory',
                   success: function(data)
                   {
                    //  alert(JSON.stringify(data));
                    let row=`
                    <option value="-1">請選擇廠區</option>
                    <option value="0">不限</option>
                    `;
                    $('#default_factory').append(row);
                     for(let i=0; i<data.length;i++){
                         let row=`<option value="${data[i].id}">${data[i].name}</option>`;
                         $('#default_factory').append(row);
                     }
          
                   },error: function (xhr, status, error) {
                         let err = JSON.parse(xhr.responseText);
                         alert(err.message);
                   }
                });
   

             

                $('#default_factory').change(function(){
     
                    let get_default_website=$('#default_factory option:selected').val();
                    if(get_default_website!='-1'){
                        $('#default_system').empty();
                        $('#user_system_select').empty();
                        let factory_data={
                        factory:get_default_website
                        };
                        $.ajax({
                            type: 'POST',
                            url: 'http://'+window.location.host+'/api/loadchildwebsite',
                            data:factory_data,
                            success: function(data)
                            {
                             //  alert(JSON.stringify(data));
                             let row=`<option value="-1">請選擇預設系統</option>`;
                             $('#default_system').append(row);
                              for(let i=0; i<data.length;i++){
                                  let row=`<option value="${data[i].url}">${data[i].name}</option>`;
                                  $('#default_system').append(row);
                                  let row_user_system=`<option value="${data[i].id}">${data[i].name}</option>`;
                                  $('#user_system_select').append(row_user_system);
                              }
                            },error: function (xhr, status, error) {
                             let err = JSON.parse(xhr.responseText);
                             alert(err.message);
                            }
                        });
                    }else{
                        $('#default_system').empty();
                    }
                    
                });

        $('#btn_register').click(function(){
            
           let get_email=$('#email').val();
           let get_password=$('#password').val();
           let get_password_confirm=$('#password-confirm').val();
           let get_name=$('#name').val();
           let get_default_website=$('#default_system option:selected').val();
           let get_default_factory=$('#default_factory option:selected').val();
           let get_user_system=$('#user_system_select').val();


           if(get_email !="" && get_password!="" && get_default_factory!='-1' && get_default_website!='-1' && get_user_system.length!=0)
           {
               let user={
                    email:get_email,
                    password:get_password,
                    name:get_name,
                    password_confirmation:get_password_confirm,
                    default_system:get_default_website,
                    default_factory:get_default_factory,
                    user_system:get_user_system
               }
                // url: 'https://'+window.location.hostname+'/api/login',
                //http://erp.com/api/public/library
                 $.ajax({
                   type: 'POST',
                   url: 'http://'+window.location.host+'/api/register',
                   async:false,
                   data: user,   
                      success: function(data)
                      {
                        alert('註冊成功!');
                        $('#name').val('');
                        $('#email').val('');
                        $('#password').val('');
                        $('#password-confirm').val('');
                        $('#default_factory').val('1');
                        $('#default_system').val('1');
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