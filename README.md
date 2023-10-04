# WordPress Plugin Template

This plugin is a template for creating a WordPress plugin. It is a fully working plugin itself, but it has very limited functionality. It only includes a starting point for features that you may want to implement in your own plugin. Remove features that you will not need and then extend the features you keep or add your own.

The goal is to provide a reliable, portable codebase with well-defined dependencies and minimal time for someone to make their first contribution even if they have never seen this code before.

**Table of Contents**

-   [Getting Started](#getting-started)
-   [Directory Structure](#directory-structure)
-   [Commands](#commands)
-   [Tests](#tests)
-   [Installing System Requirements for Development](#system-requirements-for-development)
-   [Further Reading](#further-reading)

## Getting Started

Follow these steps to create your own WordPress plugin from this template:

0. If you have not done so already, rename git's default branch: `git config --global init.defaultBranch main`
1. `git clone https://github.com/ttitamu/com-wp-plugin-template.git your-plugin-slug`
2. `cd your-plugin-slug`
3. `rm -rf .git`
4. `git init`
5. `git add .`
6. `git commit -m "Initial commit"`
7. `git branch -M main`
8. `git remote add origin https://github.com/ttitamu/your-plugin-slug.git`
9. `git push -u origin main`
10. `npm run template` - Run this command once to replace the template plugin's information with that of your new plugin.
11. `npm install`
12. `npm start`
13. Open http://localhost:8888 in your web browser.
14. If you need to log in, your username and password are `admin -> password`.

## Directory Structure

1. **.bin** - Custom scripts for local development.
2. **.config** - Configuration files for development tools used in this project.
3. **.github** - GitHub integration files such as Actions workflows.
4. **.vscode** - Visual Studio Code integration files.
5. **.wp-env** - WordPress development environment default content.
6. **common** - Common WordPress feature implementations that you can copy and modify for your plugin.
7. **docs** - Documentation files going in depth on different aspects of this project or WordPress development.
8. **src** - Source code for the plugin.  
   a. **advanced-custom-fields** - Advanced Custom Fields field registration and import files.  
   b. **assets** - JavaScript, CSS, images, fonts, and other static files.  
   c. **views** - File content output to the browser by the plugin. This is where you should put most or all of the HTML output from your plugin, to make that content easier to find and change.
9. **test** - Plugin code tests.  
   a. **e2e** - Browser tests using Playwright.  
   b. **jest** - JavaScript tests using Jest.  
   c. **phpunit** - WordPress PHP code tests using PHPUnit.
10. **index.php** - The entrypoint for the plugin's source code, which is loaded by WordPress if the plugin is activated for a site.

## Commands

The commands you will use the most frequently for developing a plugin with this repository are listed below.

For a complete list of commands, refer to [package.json](package.json) and [composer.json](composer.json). For descriptions of what these commands do, see here: [docs/commands.md](docs/commands.md)

| Command            | Description                                                  |
| ------------------ | ------------------------------------------------------------ |
| `npm install`      | Install your project dependencies for the first time.        |
| `npm start`        | Start the development environment                            |
| `npm run lint`     | Check JS and CSS code style using WordPress coding standards |
| `npm run lint:php` | Check PHP code style using WordPress coding standards        |
| `npm run test`     | Test JavaScript and PHP                                      |
| `npm run stop`     | Stop the development environment                             |

## Tests

This plugin has a small set of tests to show you how to create your own. Core WordPress code is tested, so only test the code you write.

Categories of test included in this theme:

1. **Unit** tests examine the behavior of a small unit of code.
2. **End to end** tests examine what the end user sees.
3. **Integration** tests examine compatibility between separate systems.

## System Requirements for Development

You will need the following tools installed on your computer:

-   [Docker](https://www.docker.com/products/docker-desktop)
-   [Node.js](https://nodejs.org/en/download/) or [NVM](https://github.com/nvm-sh/nvm)
-   [git](https://git-scm.com/downloads)

To make this easier, you can use an installer included in this repository by saving it to your computer and making it executable.
You must have administrator rights to run these installers.

### Mac Installation

1. Copy this file to your computer: [.bin/installers/install-mac.sh](.bin/installers/install-mac.sh)
2. Open your terminal and navigate to the directory where you saved the file.
3. Make the file executable: `chmod +x install-mac.sh`
4. Run the file: `./install-mac.sh`

### Windows Installation

1. Copy this file to your computer: [.bin/installers/install-windows.bat](.bin/installers/install-windows.bat)
2. Open your terminal and navigate to the directory where you saved the file.
3. Run the file: `install-windows.bat`

## Further Reading

The links below describe important WordPress code concepts you may need to know when developing your WordPress plugin.

-   [Action Hooks](https://developer.wordpress.org/plugins/hooks/actions/)
-   [Filter Hooks](https://developer.wordpress.org/plugins/hooks/filters/)
-   [Shortcodes](https://developer.wordpress.org/plugins/shortcodes/)
-   [Options API](https://developer.wordpress.org/plugins/settings/options-api/)
-   [Custom Post Types](https://developer.wordpress.org/plugins/post-types/)
-   [Custom Taxonomies](https://developer.wordpress.org/plugins/taxonomies/)
-   [`@wordpress/env`](https://github.com/WordPress/gutenberg/tree/trunk/packages/env)
