<?php

require_once "Router.php";

$router = new Router();
require_once "../controllers/AuthController.php";
require_once "../controllers/UserController.php";

$router->post("login", [AuthController::class, "login"]);
$router->get("users", [UserController::class, "index"]);
$router->post("register", [UserController::class, "store"]);
$router->put("users", [UserController::class, "update"]);
$router->delete("users", [UserController::class, "destroy"]);

$router->run();