# Commands

Development commands available from [package.json](../package.json) are listed below along with descriptions.

|----------------------------------------------------------------------------------------|
| Command | Description |
| ------------------------ | ----------------------------------------------------------- |
| `npm run start` | Start the development environment |
| `npm run start:debug` | Start the development environment with Xdebug activated |
| `npm run start:update` | Start the development environment and update WordPress |
| `npm run stop` | Stop Docker containers |
| `npm run clean` | Reset the database and restart the environment |
| `npm run destroy` | Destroy the Docker containers |
| `npm run lint` | Check code style using Prettier |
| `npm run format` | Fix code style using Prettier |
| `npm run lint:php` | Check code style and logic using WordPress coding standards |
| `npm run format:php` | Fix code style and logic using WordPress coding standards |
| `npm run logs` | Watch the PHP and Docker logs in real time |
| `npm run test` | Test JavaScript and PHP |
| `npm run test:js` | Test JavaScript |
| `npm run test:php` | Test PHP |
| `npm run wp` | Run a WP-CLI command in the environment |
| `npm run seed:php` | Run the database seeder in `./.wp-env/database.php` |
| `npm run seed:sql` | Run the database seeder in `./.wp-env/database.sql` |
| `npm run composer` | Use Composer in the WordPress Docker environment |
| `npm run query [string]` | Run a query string against the database |
| `npm run wp-env` | Run the base `wp-env` command |
| `docker ps` | See all running Docker containers |
| `.bin/gitprune` | Remove all local branches that have been merged |
|----------------------------------------------------------------------------------------|
