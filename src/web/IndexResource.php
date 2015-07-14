<?php
namespace rtens\blog\web;

use watoki\curir\Container;
use watoki\deli\Request;

class IndexResource extends Container {

    public function respond(Request $request) {
        return parent::respond($request);
    }

    public function doGet() {
        return "Welcome to my blog <a style='float:right' href='admin/'>Admin</a>";
    }
}