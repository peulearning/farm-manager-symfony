# criar projeto
composer create-project symfony/skeleton (nome do projeto)
cd farm-manager-symfony

# instalar web full stack (Twig, Security, Doctrine etc.)
composer require webapp

# instalar Doctrine migrations e maker bundle
composer require doctrine maker doctrine/migrations

# instalar validator e form (normalmente já com webapp)
composer require symfony/validator symfony/form

# instalar KnpPaginatorBundle
composer require knplabs/knp-paginator-bundle

# (opcional) para fixtures
composer require --dev orm-fixtures

# iniciar o servidor (opcional)
symfony server:start
