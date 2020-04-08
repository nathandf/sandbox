<?php

$routes = [
	[ "", [ "controller" => "home", "action" => "index" ] ],
	[ "{action}", [ "controller" => "home" ] ],
	[ "{path:[a-zA-Z0-9-/]+}/{controller:[a-zA-Z0-9-]*}/{id:[0-9]+}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{controller:[a-zA-Z0-9-]*}/{id:[0-9]+}", [ "action" => "index" ] ],
	[ "{controller}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{path:[a-zA-Z0-9-/]+}/{controller}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{controller:[a-zA-Z0-9-]*}/{id:[0-9]+}/{action:[a-zA-Z0-9-]*}", [] ]
];
