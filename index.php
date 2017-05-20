<?php
/*
 * Blog.IO — cеместровая работа по дисциплине "Базы данных"
 * (с) Святослав Шульц, группа 1290 (2016-2017 гг.)
 */

declare(strict_types = 1);

require './system/Application.php';

new System\Application('{
    "directories" : {
        "controllers" : "./application/controllers/",
        "models"      : "./application/models/",
        "services"    : "./application/services/"
    },

    "debugMode" : true,

    "services" : [
        {
            "name"       : "DataBase",
            "parameters" : {
                "host"     : "localhost",
                "user"     : "root",
                "password" : "",
                "name"     : "BlogIO",
                "charset"  : "utf8"
            }
        },
        {
            "name" : "User"
        },
        {
            "name"       : "Storage",
            "parameters" : {
                "directory" : "./application/storage/"
            }
        },
        {
            "name"       : "Renderer",
            "parameters" : {
                "directory" : "./application/layouts/"
            }
        }
    ],

    "errorPagesFileNames" : {
        "403" : "./ui/403.html",
        "404" : "./ui/404.html",
        "500" : "./ui/500.html"
    },

    "routes" : [
        {
            "controller" : "Install",
            "action"     : "Index",
            "pattern"    : "/install/"
        },
        {
            "controller" : "Main",
            "action"     : "Index",
            "pattern"    : "/"
        },
        {
            "controller" : "Show",
            "action"     : "Article",
            "pattern"    : "/show/Articles/{id:int}/"
        },
        {
            "controller" : "UserMain",
            "action"     : "Index",
            "pattern"    : "/user/"
        },
        {
            "controller" : "Login",
            "action"     : "ShowForm",
            "pattern"    : "/login/"
        },
        {
            "controller" : "Login",
            "action"     : "Logout",
            "pattern"    : "/login/logout/"
        },
        {
            "controller" : "Login",
            "action"     : "ProcessForm",
            "method"     : "POST",
            "pattern"    : "/login/"
        },
        {
            "controller" : "Registration",
            "action"     : "ShowForm",
            "pattern"    : "/register/"
        },
        {
            "controller" : "Registration",
            "action"     : "DeleteUser",
            "pattern"    : "/register/delete/"
        },
        {
            "controller" : "Registration",
            "action"     : "ProcessForm",
            "method"     : "POST",
            "pattern"    : "/register/"
        },
        {
            "controller" : "AdminMain",
            "action"     : "Index",
            "pattern"    : "/admin/"
        },
        {
            "controller" : "AdminEdit",
            "action"     : "ShowForm",
            "pattern"    : "/admin/edit/{table:string}/"
        },
        {
            "controller" : "AdminEdit",
            "action"     : "ShowForm",
            "pattern"    : "/admin/edit/{table:string}/{id1:int}/"
        },
        {
            "controller" : "AdminEdit",
            "action"     : "ProcessForm",
            "method"     : "POST",
            "pattern"    : "/admin/edit/{table:string}/{id1:int}/"
        },
        {
            "controller" : "AdminEdit",
            "action"     : "ShowForm",
            "pattern"    : "/admin/edit/{table:string}/{id1:int}-{id2:int}/"
        },
        {
            "controller" : "AdminEdit",
            "action"     : "ProcessForm",
            "method"     : "POST",
            "pattern"    : "/admin/edit/{table:string}/{id1:int}-{id2:int}/"
        }
    ]
}');
?>