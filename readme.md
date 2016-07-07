# Repository generator is a PHP class generator for the [Repository pattern - Repottern](https://github.com/ssi-anik/repottern)
Repository generator generates the Repository class that's how it's required for the Repottern. Have a look at [repottern](https://github.com/ssi-anik/repottern)

## Requirements: 
* Laravel >= 5
* PHP >= 5.5

## How to install?
1. `composer require anik/repository-generator`
2. After the installation, run `php artisan vendor:publish`. This will copy the config file to Laravel's config directory named `generator.php`

## Configuration:
1. namespace: The namespace you want to have for the class.
2. dir: The path the file will be saved
3. model_namespace: The model namespace, if you want to return from the class on the fly. Don't append any slash. 

## Usages: 
The artisan console has one mandatory argument and 3 optional arguments.
* Mandatory argument
  1. Class name: The class name you want as a repository. Must be a valid identifier.
* Optional arguments
  1. --dir=dir_name. if specified, the new class will be created on that directory.
  2. --namespace=namespace. if specified, then class will have that namespace.
  3. --model=model. if specified, will lookup using the config file's model_namespace. If found, then will be returned from the implemented model() method. Otherwise, will be left blank.

## Example: 
`php artisan make:repository UserRepository`
`php artisan make:repository UserRepository --namespace=Repository`
`php artisan make:repository UserRepository --model=User`
`php artisan make:repository UserRepository --namespace=Repository --model=User`

## Note:
_Here, the optional arguments can be used at any position_ 
_If the --dir argument is specified, then the directory will be created regarding the current path_
## License:
Repository Generator is released under the MIT Licence.

#Bugs and Issues:
Well, I'll always appreciate if you find any bug or issue. Feel free to inform. Anyway, forks are welcomed too.