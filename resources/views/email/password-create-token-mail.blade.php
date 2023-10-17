
@extends('email.app')
@section('content')
<tr>
    <td style="padding:0 35px;">
        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">Dear {{$user->full_name}}, </h1>
        <h3 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">Create your password</h3>
        <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
            We cannot simply send you your password. A unique link to create your
            password has been generated for you. To create your password, click the
            following link and follow the instructions.
        </p>
        <a href="{{ route('create.password.get', $token) }}" style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Create Password</a>
    </td>
</tr>
@endsection