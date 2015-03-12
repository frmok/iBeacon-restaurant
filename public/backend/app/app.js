// Make sure to include the `ui.router` module as a dependency
var app = angular.module('backendApp', 
    [
    'ui.router',
    'angularFileUpload',
    'nvd3ChartDirectives',
    'smart-table',
    'ngSanitize',
    ])
.run(['$rootScope', '$state', '$stateParams', '$http', 
    function ($rootScope, $state, $stateParams, $http) 
    {
        $http.defaults.headers.common.UserToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjIsImlhdCI6MTQyNjE2ODk2NywiZXhwIjoxNDI2NDI4MTY3fQ.pXa1JedOqooEJLfxim81cEkniWvi_2G51IZUYRMJcHc';
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
        $http.get('/api/QueueType').then(function (res){
            $rootScope.queueTypes = res.data;
        });
    }])
.config(['$stateProvider', '$urlRouterProvider',
    function ($stateProvider, $urlRouterProvider) 
    {
        $urlRouterProvider.otherwise('/');
        $stateProvider.state("home", 
        {
            url: "/",
            template: '<b>asa</b>'
        });
        $stateProvider.state("backend_table", 
        {
            templateUrl: "/backend/partials/table.html",
            url: "/table",
            controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Table',
            function($rootScope, $scope, $stateParams, WebSocket, Table){
                $rootScope.currentAction = "Table Management";
                Table.all().then(
                    function(res){
                        $scope.tables = res.data;
                    });
                $scope.deleteTable = function(index){
                    Table.delete($scope.tables[index]);
                    $scope.tables.splice(index, 1);
                }

                WebSocket.receive().then(null, null, function(message) {
                    if(message.action === 'table.update'){
                        for(var i = 0; i < $scope.tables.length; i++){
                            if($scope.tables[i].id == message.table.id){
                                $scope.tables[i].table_status = message.table.table_status;
                                if(message.table.table_status == 1){
                                    $scope.tables[i].bills[0] = message.bill;
                                }
                            }
                        }
                    }
                });
            }]

        });

$stateProvider.state("backend_table_detail", 
{
    templateUrl: "/backend/partials/table_detail.html",
    url: "/table/{tid}",
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Table',
    function($rootScope, $scope, $stateParams, WebSocket, Table){
        $scope.table = {};
        if($stateParams.tid){
            Table.get($stateParams.tid).then(
                function(res){
                    $scope.crud_action = $rootScope.currentAction = "Modify Table";
                    $scope.table = res.data;
                })
        }else{
            $scope.crud_action = $rootScope.currentAction = "Add Table";
        }

        $scope.updateTable = function(){
            if($scope.table.id){
                Table.update($scope.table).then(
                    function(res){
                        $scope.msg = "Successfully updated";
                    });
            }else{
                Table.add($scope.table).then(
                    function(res){
                        $scope.msg = "Successfully added";
                        $scope.crud_action = $rootScope.currentAction = "Modify Table";
                        $scope.table.id = res.data.id;
                    });
            }
        }


    }]
});

$stateProvider.state("backend_queue", 
{
    templateUrl: "/backend/partials/queue.html",
    url: "/queueType/{qid}",
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Ticket',
    function($rootScope, $scope, $stateParams, WebSocket, Ticket){
        Ticket.all($stateParams.qid).then(
            function (res){
                $scope.tickets = res.data.queue.tickets;
                $rootScope.currentAction = "Queue - "+res.data.queue.name;
            });
        $scope.updateTicket = function(ticket){
            var status = parseInt(ticket.ticket_status);
            //transit status
            ticket.ticket_status = ++status;
            Ticket.update(ticket);
        }
        $scope.getWaiting = function(){
            Ticket.getWaiting($stateParams.qid).then(
                function(res) {
                    $scope.waiting = res.data.waiting;
                });
        }
        $scope.getCurrentNo = function(){
            Ticket.getCurrentNo($stateParams.qid).then(
                function(res) {
                    $scope.currentNo = res.data.current;
                });
        }
        $scope.getAvgWaitingTime = function(){
            Ticket.getAvgWaitingTime($stateParams.qid).then(
                function(res) {
                    console.log(res);
                    $scope.avgWaitingTime = res.data.value;
                });
        }
        $scope.getWaiting();
        $scope.getCurrentNo();
        $scope.getAvgWaitingTime();
        WebSocket.receive().then(null, null, function(message) {
            if(message.action === 'ticket.update'){
                for(var i = 0; i < $scope.tickets.length; i++){
                    if($scope.tickets[i].id == message.ticket.id){
                        $scope.tickets[i].ticket_status = message.ticket.ticket_status;
                        $scope.getWaiting();
                        $scope.getCurrentNo();
                        $scope.getAvgWaitingTime();
                    }
                }
            }else if(message.action === 'ticket.create'){
                $scope.tickets.push(message.ticket);
                $scope.getWaiting();
                $scope.getCurrentNo();
                $scope.getAvgWaitingTime();
            }
        });
    }]
});

$stateProvider.state("backend_bill", 
{
    templateUrl: "/backend/partials/bill.html",
    url: "/bill",
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Bill',
    function($rootScope, $scope, $stateParams, WebSocket, Bill){
        $rootScope.currentAction = "Bill Management";
        Bill.all().then(
            function(res){
                $scope.bills = res.data;
                $scope.displayedCollection = [].concat($scope.bills);
            });
    }]
});

$stateProvider.state("backend_bill_detail", 
{
    templateUrl: "/backend/partials/bill_detail.html",
    url: "/bill/{bid}",
    resolve:{
        bill: ['Bill', '$stateParams', function(Bill, $stateParams){
            return Bill.get($stateParams.bid);
        }]
    },
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Bill', 'bill',
    function($rootScope, $scope, $stateParams, WebSocket, Bill, bill){
        $rootScope.currentAction = "Bill Detail";
        $scope.bill = bill.data;
    }]
});

$stateProvider.state("backend_category", 
{
    templateUrl: "/backend/partials/category.html",
    url: "/category",
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Category',
    function($rootScope, $scope, $stateParams, WebSocket, Category){
        $rootScope.currentAction = "Category Management";
        $scope.itemsByPage = 10;
        Category.all().then(
            function(res){
                $scope.categories = res.data;
                $scope.displayedCollection = [].concat($scope.categories);
            });
        $scope.deleteCategory = function(index){
            Category.delete($scope.categories[index]);
            $scope.categories.splice(index, 1);
        }
    }]
});

$stateProvider.state("backend_category_detail", 
{
    templateUrl: "/backend/partials/category_detail.html",
    url: "/category/{cid}",
    resolve:{
        category: ['Category', '$stateParams', function(Category, $stateParams){
            if($stateParams.cid){
                return Category.get($stateParams.cid);
            }
            return;
        }]
    },
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Category', 'category',
    function($rootScope, $scope, $stateParams, WebSocket, Category, category){
        $scope.category = {};
        if($stateParams.cid){
            $scope.crud_action = $rootScope.currentAction = "Modify Category";
            $scope.category = category.data;
        }else{
            $scope.crud_action = $rootScope.currentAction = "Add Category";
        }
        $scope.updateCategory = function(){
            if($scope.category.id){
                Category.update($scope.category, $scope.file).then(
                    function(res){
                        $scope.msg = "Successfully updated";
                        $scope.category = res.data;
                    });
            }else{
                Category.add($scope.category, $scope.file).then(
                    function(res){
                        $scope.msg = "Successfully added";
                        $scope.crud_action = $rootScope.currentAction = "Modify Category";
                        $scope.category.id = res.data.id;
                        $scope.category.category_img = res.data.category_img;
                    });
            }
        }
    }]
});

$stateProvider.state("backend_order", 
{
    templateUrl: "/backend/partials/order.html",
    url: "/order",
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Order',
    function($rootScope, $scope, $stateParams, WebSocket, Order){
        $rootScope.currentAction = "Order Management";
        $scope.itemsByPage = 10;
        Order.all().then(
            function(res){
                $scope.orders = res.data;
            });
        $scope.updateOrder = function(index){
            var status = parseInt($scope.orders[index].order_status);
            $scope.master= angular.copy($scope.orders[index]);
            $scope.master.order_status = ++status;
            Order.update($scope.master);
        }
        WebSocket.receive().then(null, null, function(message) {
            if(message.action === 'order.update'){
                for(var i = 0; i < $scope.orders.length; i++){
                    if($scope.orders[i].id == message.order.id){
                        $scope.orders[i].order_status = message.order.order_status;
                        $scope.orders[i].item.item_name = message.order.item.item_name;
                        $scope.orders[i].quantity = message.order.quantity;
                    }
                }
            }else if(message.action === 'order.create'){
                $scope.orders.unshift(message.order);
            }
        });
    }]
});

$stateProvider.state("backend_order_detail", 
{
    templateUrl: "/backend/partials/order_detail.html",
    url: "/order/{id}",
    resolve:{
        items: ['Item', function(Item){
            return Item.all();
        }],
        order: ['Order', '$stateParams', function(Order, $stateParams){
            if($stateParams.id){
                return Order.get($stateParams.id);
            }
        }],
    },
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Order', 'items', 'order',
    function($rootScope, $scope, $stateParams, WebSocket, Order, items, order){
        $scope.order = {};
        $scope.items = items.data;
        if($stateParams.id){
            $scope.crud_action = $rootScope.currentAction = "Modify Order";
            $scope.order = order.data;
        }
        $scope.updateOrder = function(){
            if($scope.order.id){
                Order.update($scope.order).then(
                    function(res){
                        $scope.msg = "Successfully updated";
                        $scope.order = res.data;
                    });
            }
        }
    }]
});

$stateProvider.state("backend_item", 
{
    templateUrl: "/backend/partials/item.html",
    url: "/item",
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Item',
    function($rootScope, $scope, $stateParams, WebSocket, Item){
        $rootScope.currentAction = "Item Management";
        $scope.itemsByPage = 10;
        Item.all().then(
            function(res){
                $scope.items = res.data;
                $scope.displayedCollection = [].concat($scope.items);
            });
        $scope.deleteItem = function(index){
            Item.delete($scope.items[index]);
            $scope.items.splice(index, 1);
        }
    }]
});

$stateProvider.state("backend_item_detail", 
{
    templateUrl: "/backend/partials/item_detail.html",
    url: "/item/{id}",
    resolve:{
        item: ['Item', '$stateParams', function(Item, $stateParams){
            if($stateParams.id){
                return Item.get($stateParams.id);
            }
            return;
        }],
        categories: ['Category', '$stateParams', function(Category, $stateParams){
            return Category.all();
        }]
    },
    controller: [ '$rootScope', '$scope', '$stateParams', 'WebSocket', 'Item', 'item', 'categories',
    function($rootScope, $scope, $stateParams, WebSocket, Item, item, categories){
        $scope.item = {};
        $scope.categories = categories.data;
        $scope.item.category_id = $scope.categories[0].id;
        if($stateParams.id){
            $scope.crud_action = $rootScope.currentAction = "Modify Item";
            $scope.item = item.data;
        }else{
            $scope.crud_action = $rootScope.currentAction = "Add Item";
        }
        $scope.updateItem = function(){
            if($scope.item.id){
                Item.update($scope.item, $scope.file).then(
                    function(res){
                        $scope.msg = "Successfully updated";
                        $scope.item = res.data;
                    });
            }else{
                Item.add($scope.item, $scope.file).then(
                    function(res){
                        $scope.msg = "Successfully added";
                        $scope.crud_action = $rootScope.currentAction = "Modify Item";
                        $scope.item.id = res.data.id;
                        $scope.item.item_img = res.data.item_img;
                    });
            }
        }
    }]
});

$stateProvider.state("backend_stat", 
{
    templateUrl: "/backend/partials/stat.html",
    url: "/stat",
    controller: [ '$rootScope', '$scope', '$stateParams', 'Stat',
    function($rootScope, $scope, $stateParams, Stat){
        $rootScope.currentAction = 'Overall Statistics';
        $scope.ee = [
        {"values":[[1,0],[2,0],[3,21],[4,0],[5,0],[6,0],[7,0],[8,0],[9,0],[10,0],[11,0],[12,0],[13,0],[14,0],[15,0],[16,0],[17,0],[18,0],[19,0],[20,0],[21,0],[22,0],[23,0],[24,0],[25,0],[26,0],[27,0],[28,0],[29,0],[30,0],[31,0]],"key":"Profit of this month"}
        ]
        Stat.best_selling_item().then(function(res){
            $scope.best_selling_item = [res.data];
        });
        Stat.profit().then(function(res){
            $scope.profit = [res.data];
        });
    }]
});
}]
);

app.factory('Table', ['$http', function ($http) {
    var factory = {};
    factory.all = function () {
        return $http.get('/api/table/');
    };
    factory.get = function (id) {
        return $http.get('/api/table/'+id);
    };
    factory.add = function (table) {
        return $http.post('/api/table/add', table);
    };
    factory.update = function (table) {
        return $http.post('/api/table', table);
    };
    factory.delete = function(id){
        return $http.post('/api/table/delete', id);
    }
    return factory;
}]);

app.factory('Item', ['$http','$upload', function ($http, $upload) {
    var factory = {};
    factory.all = function () {
        return $http.get('/api/items/');
    };

    factory.add = function (item, file) {
        return $upload.upload({
            url: '/api/item/add',
            headers: {
                UserToken: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjIsImlhdCI6MTQyNjE2ODk2NywiZXhwIjoxNDI2NDI4MTY3fQ.pXa1JedOqooEJLfxim81cEkniWvi_2G51IZUYRMJcHc'
            },
            file: file,
            fileFormDataName: 'item_img',
            fields: item
        });
    };
    factory.update = function (item, file) {
        return $upload.upload({
            url: '/api/item',
            headers: {
                UserToken: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOjIsImlhdCI6MTQyNjE2ODk2NywiZXhwIjoxNDI2NDI4MTY3fQ.pXa1JedOqooEJLfxim81cEkniWvi_2G51IZUYRMJcHc'
            },
            file: file,
            fileFormDataName: 'item_img',
            fields: item
        });
    };
    factory.get = function (id) {
        return $http.get('/api/item/'+id);
    };
    factory.delete = function(id){
        return $http.post('/api/item/delete', id);
    }
    return factory;
}]);

app.factory('Category', ['$http','$upload', function ($http, $upload) {
    var factory = {};
    factory.all = function () {
        return $http.get('/api/categories/');
    };

    factory.add = function (category, file) {
        //return $http.post('/api/category', category);
        return $upload.upload({
            url: '/api/category/add',
            file: file,
            fileFormDataName: 'category_img',
            fields: category
        });
    };
    factory.update = function (category, file) {
        //return $http.post('/api/category', category);
        return $upload.upload({
            url: '/api/category',
            file: file,
            fileFormDataName: 'category_img',
            fields: category
        });
    };
    factory.get = function (id) {
        return $http.get('/api/category/'+id);
    };
    factory.delete = function(id){
        return $http.post('/api/category/delete', id);
    }
    return factory;
}]);

app.factory('Order', ['$http', function ($http) {
    var factory = {};
    factory.all = function () {
        return $http.get('/api/order/');
    };
    factory.get = function (id) {
        return $http.get('/api/order/'+id);
    };
    factory.update = function (order) {
        return $http.post('/api/order', order);
    };
    return factory;
}]);

app.factory('Stat', ['$http', function ($http) {
    var factory = {};
    factory.best_selling_item = function () {
        return $http.get('/stat/ajax_best_selling_item');
    };
    factory.profit = function (id) {
        return $http.get('/stat/ajax_profit');
    };
    return factory;
}]);
app.factory('Bill', ['$http', function ($http) {
    var factory = {};
    factory.all = function () {
        return $http.get('/api/bill/');
    };
    factory.get = function (id) {
        return $http.get('/api/bill/'+id);
    };
    return factory;
}]);
app.factory('Ticket', ['$http', function ($http) {
    var factory = {};
    factory.all = function (type) {
        return $http.get('/api/QueueType/'+type);
    };
    factory.update = function (ticket) {
        return $http.post('/api/ticket', ticket);
    };
    factory.getWaiting = function (type) {
        return $http.get('/api/waitingPeople/'+type);
    };
    factory.getCurrentNo = function (type) {
        return $http.get('/api/currentTicket/'+type);
    };
    factory.getAvgWaitingTime = function (type) {
        return $http.get('/api/avgWaitingTime/'+type);
    };
    return factory;
}]);
app.service("WebSocket", function($q, $timeout) {
    var service = {}, conn, listener = $q.defer();
    var initialize = function() {
        conn = new WebSocket('ws://localhost:9999');
    };
    initialize();
    conn.onmessage = function(e) 
    {
        var data = JSON.parse(e.data);
        listener.notify(data);
    };
    service.receive = function() {
        return listener.promise;
    };
    return service;
});

app.filter('ticketStatus', 
    function(){
        return function (status){
            var status = parseInt(status);
            if(status === 0){
                return 'Waiting';
            }else if(status === 1){
                return 'Dequeued';
            }else{
                return 'Entered';
            }
        }
    });
app.filter('orderStatus', 
    function(){
        return function (status){
            var status = parseInt(status);
            if(status === 0){
                return 'Ordered';
            }else if(status === 1){
                return 'Processing';
            }else if(status === 2){
                return 'Done';
            }else{
                return 'Paid';
            }
        }
    });
app.filter('orderAction', 
    function(){
        return function (status){
            var status = parseInt(status);
            if(status === 0){
                return 'Receive';
            }else if(status === 1){
                return 'Deliver';
            }
            return;
        }
    });

app.filter('ticketAction', 
    function(){
        return function (status){
            var status = parseInt(status);
            if(status === 0){
                return 'Dequeue';
            }else if(status === 1){
                return 'Enter';
            }
        }
    });
app.filter('tempAmount', 
    function(){
        return function (orders){
            var tempAmount = 0;
            orders.forEach(function (order){
                tempAmount += order.quantity * order.item.price;
            });
            return '$'+tempAmount;
        }
    });

app.filter('billStatus', 
    function(){
        return function (status){
            var status = parseInt(status);
            if(status === 0){
                return '<strong>Not Paid</strong>';
            }else if(status === 1){
                return 'Paid';
            }
        }
    });
app.filter('matachAvail', 
    function() {
        return function( tables, availability) {
            var filtered = [];
            angular.forEach(tables, function(table) {
                if(availability === "all"){
                    filtered.push(table);
                }
                if(table.table_status == 0 && availability === "avil"){
                    filtered.push(table);
                }
            });
            return filtered;
        };
    });

app.filter('matachCapacity', 
    function() {
        return function( tables, capacity) {
            var filtered = [];
            angular.forEach(tables, function(table) {
                if(table.capacity >= capacity){
                    filtered.push(table);
                }
            });
            return filtered;
        };
    });
app.filter('capitalizeFirst', 
    function () {
        return function (input, scope) {
            var text = input.substring(0, 1).toUpperCase() + input.substring(1).toLowerCase();
            return text;
        }
    });

app.controller('mainController', 
    ['$rootScope', '$scope', '$http',
    function ($rootScope, $scope, $http) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            $scope.loading = true;
            $scope.finish = false;
        });
        $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
            $scope.loading = false;
            $scope.finish = true;
        });
    }]);