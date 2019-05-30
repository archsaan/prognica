<!-- Set title -->
@section('title', 'My Profile')

@extends('layouts.default')

<!-- this page CSS -->
@section('page-css')
<style type="text/css">
    .full-width{
        float:left;width:100%;
        /*        margin-top:30px;*/
        min-height:100px;position:relative;
    }
    .form-style-fake{position:absolute;top:0px;}
    .form-style-base{position:absolute;top:0px;z-index: 999;opacity: 0;}
    .imgCircle{border-radius: 50%;}
    /*            .form-control{padding: 10px 50px;}
                .form-input{height:50px;border-radius: 0px;margin-top: 20px;}*/
    .Profile-input-file{
        height:180px;width:180px;left:33%;
        position: absolute;
        top: 0px;
        z-index: 999;
        opacity: 0 !important;  
        cursor: pointer;
    }
    .mg-auto{
        margin:0 auto;max-width: 200px;overflow: hidden;
    }
    .fake-styled-btn{
        background: #006cad;
        padding: 10px;
        color: #fff;
    }
    #main-input{width:250px;}
    .input-place{
        position: absolute;top:35px;left: 20px;font-size:23px;color:gray;}
    .margin{margin-top:10px;margin-bottom:10px;}
    .truncate {
        width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /*            .bg-form{
                    float:left;width:100%;
                    position:relative;
                    background: url("http://lorempixel.com/200/200/abstract/");
                    background-repeat: no-repeat;
                    background-size: cover;
                    margin-top: 0px;
                }*/
    /*            .bg-transparent{
                    background: rgba(0,0,0,0.5);
                    float: left;
                    width: 100%;margin-top: 0px;
                }*/
    /*            .container{
                    background: url("http://lorempixel.com/800/800/nature/");
                    background-repeat: no-repeat;
                    background-size: cover;
                }*/
    .custom-form{float: left;width:100%;border-radius: 20px;box-shadow: 0 0 16px #fff;overflow: hidden;
                 background: rgba(255,255,255,0.6);
    }
    .img-section{
        float: left;width: 100%;padding-top: 15px;padding-bottom: 15px;background: rgba(0,0,0,0.7);position: relative;
    }
    .img-section h4{color:#fff;}
    #PicUpload{
        color: #ffffff;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.4);
        padding: 100px;
        position: absolute;
        left: 30.5%;
        border-radius: 50%;
        display: none;
        top:15px;
    }
    .camera{
        font-size: 50px;
        color: #333;
    }
    .custom-btn{
        margin-top: 15px;
        border-radius: 0px;
        padding: 10px 60px;
        margin-bottom: 15px;
    }
    #checker{
        opacity: 0;
        position: absolute;
        top: 0px;
/*        cursor: pointer;*/
    }
    .color{
        color:#fff;
    }
    #checker:disabled{
        cursor: not-allowed;
    }
    #submit:disabled{
        cursor: not-allowed;
    }
    /*====== style for placeholder ========*/

    /*            .form-control::-webkit-input-placeholder {
                    color:lightgray;
                    font-size:18px;
                }
                .form-control:-moz-placeholder {
                    color:lightgray;
                    font-size:18px;
                }
                .form-control::-moz-placeholder {
                    color:lightgray;
                    font-size:18px;
                }
                .form-control:-ms-input-placeholder {
                    color:lightgray;
                    font-size:18px;
                }    */
</style>
@endsection

@section('content')
<!-- start: page -->
<header class="page-header">
    <div class="row">
        <div class="col-xs-10 col-xs-push-1" style="margin-top: 0.8%;">
            {{ HTML::link('/dashboard', "Dashboard", ['style' => "color:white; text-decoration:underline; margin-top: 1%;"])}}
            <span style="color: white;"> < </span>
            <span style="color: #49ad9e;">My Profile</span>
        </div>  
    </div>
</header>
<div class="row">
    <div class="col-xs-10 col-xs-push-1">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session($msg))
        <div class="alert alert-{{ $msg }}">{{ session($msg) }}</div>
        @endif
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-xs-5 col-sm-push-1">
        <section class="panel panel-primary">
            <div class="panel-heading">
                <h4 style="text-transform: uppercase"><strong>Your Profile</strong></h4>
            </div>
            <div class="panel-body">       
                {{ FORM::open(array('url' => 'update', 'method' => 'post', 'id' => 'profileForm'))}} 
                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::text('name', $user->profile->name, ['class' => 'form-control input-lg', 'placeholder' => 'Full Name', 'id' => 'name', 'required' => 'true', 'inputCheck' => '[a-zA-Z\s]+']) }}
                        @if ($errors->has('name'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <label id="name-error" class="error" for="name" style="display:inline;"></label>
                </div>

                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::email('email', $user->email, ['class' => 'form-control input-lg', 'placeholder' => 'Email Address', 'id' => 'email', 'required' => 'true', 'CustomEmail' => 'true', 'autocomplete'=>"false"]) }}
                        @if ($errors->has('email'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif                
                    </div>
                    <label id="email-error" class="error" for="email" style="display: inline;"></label>
                </div>

                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::text('phone',$user->profile->phone, ['class' => 'form-control input-lg', 'placeholder' => 'Phone Number', 'id' => 'phone', 'required' => 'false']) }}
                        @if ($errors->has('phone'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                        @endif
                    </div>
                    <label id="phone-error" class="error" for="phone" style="display:inline;"></label>
                </div>

                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::text('mobile', $user->profile->mobile, ['class' => 'form-control input-lg', 'placeholder' => 'Mobile Number', 'id' => 'mobile', 'required' => 'true', 'PhoneCheck' => '([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})']) }}
                        @if ($errors->has('mobile'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif
                    </div>
                    <label id="mobile-error" class="error" for="mobile" style="display:inline;"></label>
                </div>
                {{ FORM::select('organisation_id', $organisation, $user->profile->organisation_id, ['class' => 'form-control input-sm mb-md', 'id' => 'organisation_id', 'required' => 'true'])}}
                @if ($errors->has('organisation_id'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('organisation_id') }}</strong>
                </span>
                @endif
                <label id="organisation_id-error" class="error" for="organisation_id" style="display:inline;"></label>

                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::text('organisation_name', $user->profile->organisation_name, ['class' => 'form-control input-lg', 'placeholder' => 'Organisation Name', 'id' => 'organisation_name']) }}
                        @if ($errors->has('organisation_name'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('organisation_name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <label id="organisation_name-error" class="error" for="organisation_name" style="display:inline;"></label>
                </div>
                <?php
                $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                ?>
                {{ FORM::select('country', array_merge(['' => 'Please Select your country'], array_combine($countries, $countries)), $user->profile->country, ['class' => 'form-control input-sm mb-md', 'id' => 'country'])}}
                @if ($errors->has('country'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('country') }}</strong>
                </span>
                @endif
                <label id="country-error" class="error" for="country" style="display:inline;"></label>

                <div class="mb-xs text-center">
                    {{ FORM::submit('Update Profile', ['class' => 'mb-xs mt-xs mr-xs btn btn-primary btn-sm btn-block']) }}
                </div>                
                {{ FORM::close() }}
            </div>
        </section>
    </div>

    <div class="col-xs-5 col-sm-push-1">
        {{ FORM::open([ 'url' => 'ajax_upload_pic', 'method' => 'post', 'id' => 'avatarForm', 'files' =>'true'])}}
<!--        <form action="{{ url('/ajax_upload_pic') }}" method="post" enctype="multipart/form-data" id="avatarForm">-->
            <div class="full-width">                
                <div class="custom-form">
                    <div class="text-center bg-form">
                        <div class="img-section">
                            <?php $profile_pic = isset($user->profile->profile_pic) ? $user->profile->profile_pic : "avatar.png"; ?>
                            {{ HTML::image('images/avatar/'. $profile_pic, 'Profile picture', ['class' => 'imgCircle', 'width' => '200', 'height' => '200'])}}
                            <span class="fake-icon-edit" id="PicUpload" style="color: #ffffff;"><span class="glyphicon glyphicon-camera camera"></span></span>
                            <div class="col-lg-12">
                                <h4 class="text-right col-lg-12">
                                    <!--                                    @if($profile_pic != 'avatar.png')
                                                                        <span class="glyphicon glyphicon-edit"></span> Edit Profile
                                                                        @else
                                                                        <span class="glyphicon glyphicon-edit"></span> Add Profile
                                                                        @endif-->
                                    <button class="btn btn-info btn-md" disabled id='submit'><span class="glyphicon glyphicon-save"></span> Save</button>
                                </h4>
<!--                                <input type="checkbox" class="form-control" id="checker" disabled="">-->
                            </div>
                        </div>
                        <input type="file" name="profile_pic" id="image-input" onchange="readURL(this);" accept="image/*" disabled class="form-control form-input Profile-input-file" >
                    </div>
                    <div class="col-lg-12 col-md-12 text-center">
                        <p></p>
<!--                        <button type="submit" class="btn btn-info btn-lg custom-btn" id="submit" disabled=""><span class="glyphicon glyphicon-save"></span> Save</button>-->
                    </div>
                </div>
            </div>
        {{ FORM::close() }}
    </div>

    <div class="col-xs-5 col-sm-push-1">
        <section class="panel panel-primary">
            <div class="panel-heading">
                <h4 style="text-transform: uppercase"><strong>Change Password</strong></h4>
            </div>
            <div class="panel-body">      
                {{ FORM::open(array('url' => 'change_password', 'method' => 'post', 'id' => 'RegisterForm'))}} 
                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::password('password', ['class' => 'form-control input-lg', 'placeholder' => 'New Password', 'id' => 'password', 'required' => 'true', 'minlength' => '8']) }}
                        @if ($errors->has('password'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <label id="password-error" class="error" for="password" style="display:inline;"></label>
                </div>

                <div class="form-group mb-lg">
                    <div class="input-group input-group-icon">
                        {{ FORM::password('confirm_password', ['class' => 'form-control input-lg', 'placeholder' => 'Confirm Password', 'id' => 'confirm_password', 'required' => 'true', 'equalTo' => '#password']) }}
                        @if ($errors->has('confirm_password'))
                        <span class="invalid-feedback alert-danger" role="alert">
                            <strong>{{ $errors->first('confirm_password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <label id="confirm_password-error" class="error" for="confirm_password" style="display:inline;"></label>
                </div>
                <div class="mb-xs text-center">
                    {{ FORM::submit('Submit', ['class' => 'mb-xs mt-xs mr-xs btn btn-primary btn-sm btn-block']) }}
                </div>
                {{ FORM::close() }}
            </div>
        </section>
    </div>    
</div>					


<!-- end: page -->
@endsection

<!-- this page JS -->
@section('page-JS')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });</script>
<script type="text/javascript">

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.imgCircle')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(200);
            };
            reader.readAsDataURL(input.files[0]);
            $('#submit').removeAttr('disabled');
            $('#checker').removeAttr('disabled');
        }
    }


    var userImage = document.getElementById('image-input');
    var UserSend = document.getElementById('submit');
    var editPic = document.getElementById('PicUpload');
    userImage.disabled = false;
    editPic.style.display = "block";
    UserSend.onclick = function () {
        $('#avatarForm').submit();
    }

</script>
@endsection