<!DOCTYPE html>
<html lang="$locale">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web Call Center</title>
    <link rel="stylesheet" href="{{asset(mix('css/vendors.css', 'vendor/wcc'))}}" type="text/css">
    <link rel="stylesheet" href="{{asset(mix('css/login.css', 'vendor/wcc'))}}" type="text/css">
    <script type="text/javascript" src="{{asset(mix('js/manifest.js', 'vendor/wcc'))}}"></script>
    <script type="text/javascript" src="{{asset(mix('js/vendors.js', 'vendor/wcc'))}}"></script>
</head>
<body class="bg-blue-grey-10">
<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-5 col-lg-4" id="login">
        <div class="card bg-white border-blue-3">
            @if(isset($fail))
                <div class="alert alert-danger alert-dismissible fade show mb-0">
                    <span class="close p-0" data-dismiss="alert">&times;</span>
                    Wrong username and / or password!
                </div>
            @endif
            <div class="card-body">
                <form action="" method="post" enctype="application/x-www-form-urlencoded">
                    {{csrf_field()}}
                    <input type="hidden" name="timezone" value="Asia/Kuwait">
                    <div class="row">
                        <div class="col-sm-12 form-group"><input type="text" class="form-control" name="username" placeholder="Username"></div>
                        <div class="col-sm-12 form-group"><input type="password" class="form-control" name="password" placeholder="Password"></div>
                        <div class="col-sm-12"><button class="btn btn-block btn-primary" type="submit">Login</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
