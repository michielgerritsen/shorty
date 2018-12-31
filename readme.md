# Shorty

## Local commands

Shorty is created to make it easy to create shortcuts to commands. Just run for example:

`shorty add symfony bin/console`

Now you have the `symfony` command available globally. When you switch to another folder, the m2 command will still work and be executed in the folder where you added the Shorty command.

## Global commands

You can also add global commands. Shorty will search for this command in all underlying folders. So for example, when running:

`shorty global symfony bin/console`

You can then execute the `symfony` command in any folder within your Symfony project. So when you are in `config/packages/dev` for example, you can run:

`symfony server:run`

And the server will be started. This will work for any Symfony project.

## Installation

Shorty is meant to be installed global using Composer:

`composer global require michielgerritsen/shorty`

### Roadmap/Whislist

- tests - tests - tests.
- Better execution of commands: Laravel's artisan isn't executable in a typical installation.
- Default configuration for software like Laravel/Symfony/Magento/etc.
- Add your own suggestion by opening an issue or sending a pull request.

Build with ❤ by [@michielgerritsen](https://www.michielgerritsen.com)