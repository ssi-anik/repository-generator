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
	protected $signature = 'make:repository {class_name} {--dir=default} {--namespace=default} {--model=default}';

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
		// get the model, if given
		$model = trim($this->option("model"));

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
		// get the model to put on template holder
		if ( $model == "default" ) {
			$model = '';
		}

		$this->generate_repository_class($class, $namespace, $dir, $model);
		$this->info(sprintf('Class "%s" created with namespace "%s" in directory "%s"', $class, $namespace, $dir));
	}

	/**
	 * Generate the repository class
	 *
	 * @param $class
	 * @param $namespace
	 * @param $dir
	 * @param $model
	 */
	private function generate_repository_class ($class, $namespace, $dir, $model)
	{
		$stub = $this->get_stub();
		$replaced_text = $this->replacer($namespace, $class, $stub, $model);
		$this->create_dir($dir);
		file_put_contents(sprintf("%s/%s.php", $dir, $class), $replaced_text);
	}

	/**
	 * Replace the stub template with the actual data
	 *
	 * @param        $namespace
	 * @param        $class
	 * @param        $stub
	 * @param string $model
	 *
	 * @return mixed
	 */
	private function replacer ($namespace, $class, $stub, $model = '')
	{
		$model_namespace = '';
		$return_type = '';

		if ( !empty( $model ) ) {
			// get the model namespace from config
			$config_model_namespace = trim(Config::get('generator.model_namespace'));
			// replace the backslash or forward slash
			$config_model_namespace = str_replace([
				'\\',
				'/',
			], "", $config_model_namespace);
			// generate a model name with namespace
			$model_namespace = sprintf("%s\\%s", $config_model_namespace, $model);
			// check if the model exists or not
			if ( class_exists($model_namespace) ) {
				$model_namespace = sprintf("use %s;\n", $model_namespace);
				$return_type = sprintf("return %s::class;", $model);
			} else {
				$this->warn(sprintf("Model \"%s\" doesn't exist. Return manually.", $model));
				$model_namespace = '';
			}
		}

		return str_replace([
			'@@NAMESPACE@@',
			'@@CLASS@@',
			'@@MODEL_NAMESPACE@@',
			'@@RETURN@@',
		], [
			$namespace,
			$class,
			$model_namespace,
			$return_type,
		], $stub);
	}

	private function create_dir ($dir)
	{
		if ( !file_exists($dir) ) {
			mkdir($dir, 0755, true);
		}
	}

	/**
	 * Get the stub
	 * @return string
	 */
	private function get_stub ()
	{
		return file_get_contents(__DIR__ . "/stubs/repository.stub");
	}

	/**
	 * Validate if the given class name string is a valid identifier
	 *
	 * @param $class
	 *
	 * @return int
	 */
	private function validate_class_name ($class)
	{
		return preg_match("/^[_a-zA-Z][_a-zA-Z0-9]*$/", $class);
	}
}