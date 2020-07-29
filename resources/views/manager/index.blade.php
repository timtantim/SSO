@extends('layouts.app')
@section('assets')

@endsection
@section('content')
<div class="container-fluid" >
  <div class="row" style="margin-top:15px;">
    <div class="table table-responsive">
       <table class="table table-bordered" id="table">
          <tr>
             <th>編號</th>
             <th>姓名</th>
             <th>Email</th>
             <th>密碼</th>
             <th>預設系統</th>
             <th>廠區編號</th>
             <th>密碼更新月份</th>
             <th>授權狀態</th>
             <th>建立時間</th>
             <th>修改時間</th>
             <th class="text-center"width="150px">
               編輯
                {{-- <a href="#" class="create-modal btn  btn-success btn-sm">
                    <i class="glyphicon glyphicon-plus"></i>
                </a> --}}
                {{-- <a href="#" class="create-modal btn  btn-success btn-sm">
                 <i class="glyphicon glyphicon-plus"></i>
                </a> --}}
             </th>
          </tr>
          {{ csrf_field() }}
          @foreach($users as $key => $value)
             <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->password_no_hash}}</td>
                <td>{{$value->default_system}}</td>
                <td>{{$value->sso_factory_id}}</td>
                <td>{{$value->update_month}}</td>
                <td>{{($value->set_auth=='0')?'尚未授權':'已授權'}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->updated_at}}</td>
                <td>

                     <a href="#" class="edit-modal btn btn-warning btn-sm" data-id="{{$value->id}}" data-name="{{$value->name}}" data-set_auth="{{$value->set_auth}}">
                      <i class="fa fa-pencil" aria-hidden="true"></i>
                     </a>

                     <a href="#" class="delete-modal btn btn-danger btn-sm" data-id="{{$value->id}}" data-name="{{$value->name}}" data-set_auth="{{$value->set_auth}}">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                     </a>
                </td>
             </tr>
          @endforeach
       </table>
    </div>
    {{$users->links()}}
 </div>

</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"></h4>
              <button type="butoon" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
              <form class="form-horizontal" role="modal">
                  <div class="form-group">
                      <label class="control-label col-sm-2" for="id">ID</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" id="fid" disabled>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-2" for="chinese">姓名</label>
                      <div class="col-sm-10">
                          <input type="name" class="form-control" id="user_name">
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-2" for="english">授權狀態</label>
                      <div class="col-sm-10">
                        <select class="form-control" id="user_verify">
                          <option value="1">已授權</option>
                          <option value="0">尚未授權</option>
                        </select>
                      </div>
                  
                  </div>
              </form>
              {{--form delete post--}}
              <div class="deletecontent">
                  您確定要刪除 <span class="english"></span>?
                  <span class="hidden id"></span>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn actionBtn" id="verify_user_button">
                  <span id="footer_action_button" class="glyphicon"></span>
              </button>
              <button type="button" class="btn btn-warning" data-dismiss="modal">
                  <span class="glyphicon glyphicon"></span>Close
              </button>
          </div>
      </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    $('.edit-modal').bind('click',function(){
      console.log($(this).data('chinese'));
            $('#footer_action_button').text('驗證');
            $('#footer_action_button').addClass('glyphicon-check');
            $('#footer_action_button').removeClass('glyphicon-trash');
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').addClass('edit');
            $('.modal-title').text('驗證用戶');
            $('.deletecontent').hide();
            $('.form-horizontal').show();
            $('#fid').val($(this).data('id'));
            $('#user_name').val($(this).data('name'));
            $('#user_verify').val($(this).data('set_auth'));
            $('#myModal').modal('show');
    });

    $('#verify_user_button').bind('click',function(){
      let get_verify_select=$( "#user_verify option:selected" ).val();
      if(get_verify_select=='1'){
        let user_data={
                    user_id:$('#fid').val(),
                    verify:get_verify_select
               }
        $.ajax({
                       type: 'POST',
                       url: 'http://'+window.location.host+'/api/setauthuser',
                       async:false,
                       data: user_data,   
                          success: function(data)
                          {
                            alert('驗證成功!');
                            $('#myModal').modal('hide');
                          },error: function (xhr, status, error) {
                                let err = JSON.parse(xhr.responseText);
                                alert(err.message);
                          }
                      });
        }
      });
     
  });
  </script>
@stop