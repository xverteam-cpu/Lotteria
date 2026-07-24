<?php

return [
    'idpNames' => ['google'],
    'useRoutes' => true,
    'routesPrefix' => '/saml2',
    'routesMiddleware' => ['web'],
    'retrieveParametersFromServer' => false,
    'logoutRoute' => '/login',
    'loginRoute' => '/login',
    'errorRoute' => '/login',
    'proxyVars' => true,
];
