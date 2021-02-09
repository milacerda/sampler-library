var app = angular.module("sampler-routes", ["ngRoute"]);

app.config(function($routeProvider, $locationProvider) {
    $locationProvider.html5Mode(true);
    $routeProvider
        .when("/", {
            templateUrl: "/views/books.html",
            controller: "bookController",
            restricted: true
        })
        .when("/login", {
            templateUrl: "/views/login.html",
            controller: "loginController",
            restricted: false
        })
        .when("/register", {
            templateUrl: "/views/register.html",
            controller: "registerController",
            restricted: false
        })
        .otherwise({
            redirectTo: "/login"
        });
});
