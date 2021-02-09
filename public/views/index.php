<!DOCTYPE html>
<html lang="en" ng-app="sampler">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sampler Library</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/app.css">

    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/all.js"></script>

    <base href="/">
</head>

<body>

    <header class="border-bottom shadow-sm bg-dark" ng-controller="mainController">
        <div class="container p-3 px-md-4" ng-init="init()">
            <nav class="navbar navbar-dark navbar-expand-lg justify-content-between">
                <a class="font-weight-normal text-white h3" href="/">Sampler Library</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active" ng-if="loggedIn">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li ng-if="!loggedIn && currentPath !== '/register'">
                            <a class="btn btn-primary btn-sm ml-3" href="/register">Register</a>
                        </li>
                        <li ng-if="!loggedIn && currentPath !== '/login'">
                            <a class="btn btn-primary btn-sm ml-3" href="/login">Login</a>
                        </li>
                        <li ng-if="loggedIn">
                            <a class="btn btn-primary btn-sm ml-3" href="#" ng-click="logout()">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <ng-view></ng-view>

</body>

</html>