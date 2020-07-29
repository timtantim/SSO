@component('mail::message')
# 帳號驗證成功通知

請各系統管理員盡速完成權限設定.

@component('mail::button', ['url' => 'http://sso.com/login'])
檢視用戶
@endcomponent
用戶帳號 : {{ $users['account'] }}<br>
{{-- 用戶密碼 : {{ $users['password'] }}<br> --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
