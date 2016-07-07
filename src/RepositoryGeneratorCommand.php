<?php
/**
 * Created by PhpStorm.
 * User: anik
 * Date: 7/7/16
 * Time: 12:57 PM
 */

namespace Anik\Generator;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class RepositoryGeneratorCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'make:repository {class_name} {--dir=default} {--namespace=default}';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Create a new repository class.';

	/**
	 * Create a new command instance.
	 */
	public function __construct ()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 * @throws \Anik\Generator\RepositoryGeneratorException
	 */
	public function handle ()
	{
		// get the class name
		$class = trim($this->argument('class_name'));
		// get the path, if given
		$dir = trim($this->option("dir"));
		// get the namespace, if given
		$namespace = trim($this->option("namespace"));

		// check if the class name is valid
		if ( !$this->validate_class_name($class) ) {
			throw new RepositoryGeneratorException(sprintf("Invalid class name - \"%s\".", $class));
		}
		// get the directory to save the file
		if ( $dir == "default" ) {
			$dir = Config::get('generator.dir');
		}
		// get the namespace to save the file
		if ( $namespace == "default" ) {
			$namespace = Config::get('generator.namespace');
		}

		$this->generate_repository_class($class, $namespace, $dir);
		$this->info(sprintf('Class "%s" created with namespace "%s" in directory "%s"', $class, $namespace, $dir));
	}

	private function generate_repository_class ($class, $namespace, $dir)
	{
		$stub = $this->get_stub();
		$this->create_dir($dir);
		$replaced_text = $this->replacer($namespace, $class, $stub);

		file_put_contents(sprintf("%s/%s.php", $dir, $class), $replaced_text);
	}

	private function replacer ($namespace, $class, $stub)
	{
		return str_replace([
			'@@NAMESPACE@@',
			'@@CLASS@@',
		], [
			$namespace,
			$class,
		], $stub);
	}

	private function get_stub ()
	{
		return file_get_contents(__DIR__ . "/stubs/repository.stub");
	}

	private function create_dir ($directory)
	{
		if ( !file_exists($directory) ) {
			mkdir($directory, 0755, true);
		}
	}

	private function validate_class_name ($class)
	{
		return preg_match("/^[_a-zA-Z][_a-zA-Z0-9]*$/", $class);
	}
}