# 1️⃣ Créer un projet Symfony
symfony new library-api --version=6.3 --webapp
cd library-api

# 2️⃣ Installer Doctrine et MakerBundle
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle

# 3️⃣ Copier les fichiers fournis (Book.php, User.php, LibraryController.php)
# et les placer dans src/Entity et src/Controller

# 4️⃣ Configurer la base de données (dans .env)
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

# 5️⃣ Créer la base et générer les migrations
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate

# 6️⃣ Démarrer le serveur
symfony server:start
