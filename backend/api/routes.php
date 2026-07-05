<?php

require_once "Router.php";

$router = new Router();
require_once "../controllers/AuthController.php";
require_once "../controllers/UserController.php";
require_once __DIR__ . "/../controllers/AbsensiController.php";
require_once __DIR__ . "/../controllers/DivisiController.php";


$router->post("login", [AuthController::class, "login"]);
$router->get("users", [UserController::class, "index"]);
$router->post("register", [UserController::class, "store"]);
$router->put("users", [UserController::class, "update"]);
$router->delete("users", [UserController::class, "destroy"]);

$router->get("absensi", [AbsensiController::class, "index"]);
$router->get("absensi/show", [AbsensiController::class, "show"]);
$router->post("absensi", [AbsensiController::class, "store"]);
$router->put("absensi", [AbsensiController::class, "update"]);
$router->delete("absensi", [AbsensiController::class, "destroy"]);

$router->get("divisi", [DivisiController::class, "index"]);
$router->get("divisi/show", [DivisiController::class, "show"]);
$router->post("divisi", [DivisiController::class, "store"]);
$router->put("divisi", [DivisiController::class, "update"]);
$router->delete("divisi", [DivisiController::class, "destroy"]);

$router->run();
