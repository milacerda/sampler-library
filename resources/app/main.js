var app = angular.module("sampler", ["sampler-routes", "angularMoment", "angular-jwt"],
    [
        "$httpProvider",
        function($httpProvider) {
            $httpProvider.interceptors.push("authInterceptor");
        }
    ]
)
.run(function($rootScope, $location, $route, authService) {
    $rootScope.$on("$locationChangeStart", function() {
        var nextRoute = $route.routes[$location.path()];
        $rootScope.currentPath = $location.path();
        if (nextRoute.restricted) {
            if(!authService.isLoggedIn()) {
                $rootScope.currentPath = '/login';
                $location.path("/login");
            }
        }
    });
    $rootScope.$on('unauthorized', () => {
        authService.removeToken();
        $location.path("/login");
    });
})
.constant("moment", moment)
.filter('camelCase', function () {
    var camelCaseFilter = function (input) {
        var string = '';
        if (input) {
            input.toLowerCase();
            string = input.slice(0,1).toUpperCase() + input.slice(1);
        }
        return string;
    };
    return camelCaseFilter;
})
.filter('action', function () {
    var actionFilter = function (input) {
        var string = '';
        if (input === 'CHECKIN') {
            string = 'checked in';
        } else {
            string = 'checked out';
        }
        return string;
    };
    return actionFilter;
})

app.directive("validateEmail", function() {
    var regex = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/;

    return {
        require: "ngModel",
        link: function(scope, elm, attrs, ctrl) {
            if (ctrl && ctrl.$validators.email) {
                ctrl.$validators.email = function(modelValue) {
                    return ctrl.$isEmpty(modelValue) || regex.test(modelValue);
                };
            }
        }
    };
});

app.directive("validateDob", function() {
    return {
        require: "ngModel",
        link: function(scope, elm, attrs, ctrl) {
            ctrl.$validators.dob = function(modelValue) {
                if (moment(modelValue).isAfter(moment())) {
                    return false;  
                } else {
                    return true;
                }
            };
        }
    };
});

app.factory('authInterceptor', function($injector) {
      return {
        request: function(config) {
            const token = localStorage.getItem('token');
            config.headers = config.headers || {};
            if (token != null) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        },
        response: function(response) {
            if (
                response.data.status === "Token is expired" ||
                response.data.status === "Token is invalid" ||
                response.data.status === "Authorization Token not found"
            ) {
                var rScope = $injector.get('$rootScope');
                rScope.$broadcast("unauthorized");
            }
            return response;
        },
        responseError: function(rejection) {
            if (
                rejection.data.status === "Token is expired" ||
                rejection.data.status === "Token is invalid" ||
                rejection.data.status === "Authorization Token not found"
            ) {
                var rScope = $injector.get('$rootScope');
                rScope.$broadcast("unauthorized");
            }
            return rejection;
        }
      };
    }
);

app.controller('mainController', function($scope, authService) {

    $scope.init = function () {
        $scope.loggedIn = authService.isLoggedIn();
        $scope.route = window.location.pathname;
    }

    $scope.logout = function() {
        authService.logout().then(() => {
            authService.removeToken();
            window.location = "/login";
        });
    };
});

app.controller('bookController', function($scope, $http, jwtHelper, authService) {
    $scope.errorFields = [];

    $scope.list = function(page = 1){
        $http.get(`/api/books?page=${page}`)
        .then((response) => {
            if (response.data.data) {
                var books = response.data.data.map((book) => {
                    book.publication_date = moment(book.publication_date).toDate();
                    return book;
                })
                $scope.books = books;

                $scope.current_page = response.data.current_page;
                $scope.total = response.data.total;
                $scope.last_page = response.data.last_page;
                $scope.pages = new Array(response.data.total / 5);

            }
        },
        (e) => {
            $scope.error = e.data.message;
            $('#modalError').modal('show');
        });
    }

    $scope.sendAction = function() {
        var data = $scope.bookAction;
        var token = jwtHelper.decodeToken(authService.getToken());
        data.user_id = token.sub;

        $http.post("/api/logs", data)
        .then((response) => {

            if (response.status !== 201) {
                if (response.data.errors) {
                    $scope.errors = response.data.errors;
                    $('#modalError').modal('show');
                } else if (response.data.message) {
                    $scope.error = response.data.message;
                    $('#modalError').modal('show');
                }
                return;
            }

            $scope.list();
            $('#modalSuccess').modal('show');
        },
        (e) => {
            if (e.data.errors) {
                $scope.errors = e.data.errors;
            }
            if (e.data.message) {
                $scope.error = e.data.message;
            }
            $('#modalError').modal('show');
        });
    }
});

app.factory('authService', function($http, $window) {
	return {
        login: function(data) {
            return $http.post("/api/auth/login", data);
        },
        logout: function() {
            return $http.post("/api/logout");
        },
        register: function(data) {
            return $http.post("/api/users", data);
        },
        getToken: function() {
            return $window.localStorage.getItem("token");
        },
        setToken: function(token) {
            $window.localStorage.setItem("token", token);
        },
        removeToken: function(token) {
            $window.localStorage.removeItem("token");
            this.setHeaders();
        },
        isLoggedIn: function() {
            if ($window.localStorage.getItem("token") === null) {
                return false;
            } else {
                return true;
            }
        },
        setHeaders: function(token) {
            if (token) {
                $http.defaults.headers.common["Authorization"] = 'Bearer ' + token;
            } else {
                delete $http.defaults.headers.common["Authorization"];
            }
        }
    };
});

app.controller('loginController', function($scope, authService) {

    $scope.login = function(){
        authService.login($scope.user)
        .then((response) => {
            if (response.data.access_token) {
                authService.setToken(response.data.access_token);
                authService.setHeaders(response.data.access_token);
                window.location = '/';
            }
        },
        (e) => {
            $scope.error = e.data.error;
            console.log(e);
        });
    }

});

app.controller('registerController', function($scope, authService) {
    $scope.register = function(){
        authService.register($scope.user)
        .then((response) => {
            if(response.status === 201) {
                window.location.href = '/login';
            } else {
                $scope.error = response.data.error;
            }
        },
        (e) => {
            console.log(e);
            $scope.error = e.data.error;
        });
    }
});