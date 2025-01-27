<?php
namespace Packages\Commands\Core\Generators;

use Packages\Commands\Core\Generator;



class Migration extends Generator
{
	protected $name        = 'generator:migration';
	protected $description = 'Generate a Migration';
	
	public function start()
	{
		// Set default timezone if we use a timestamp migration version.
		date_default_timezone_set('UTC');
		
		$migration_regex = ($this->getOption('timestamp') !== FALSE)
			? '/^\d{14}_(\w+)$/'
			: '/^\d{3}_(\w+)$/';
		
		$filename   = $this->getArgument('filename');
		$basepath   = rtrim(preg_replace(['/migrations/','/migration/'], ['',''], $this->getOption('path')),'/');
		$migrations = array();

		$basepath.='/migrations/';

		if ($this->_filesystem->exists($basepath)) 
		{
			// And now let's figure out the migration target version
			if ($handle = opendir($basepath))
			{
				while (($entry = readdir($handle)) !== FALSE)
				{
					if ($entry == "." && $entry == "..")
					{
						continue;
					}
					if (preg_match($migration_regex, $file = basename($entry, '.php')))
					{
						$number = sscanf($file, '%[0-9]+', $number)? $number : '0';
						if (isset($migrations[$number]))
						{
							throw new \RuntimeException("Cannot be duplicate migration numbers");
						}
						$migrations[$number] = $file;
					}
				}
				closedir($handle);
				ksort($migrations);
			}		
		}
			
		$versions = array_keys($migrations);
		end($versions);
	
		$target_version = ($this->getOption('timestamp') !== FALSE)
			? date("YmdHis")
			: sprintf('%03d', abs(end($versions)) + 1);

		// Maybe something wrong with timestamp?
		if ($target_version <= current($versions))
		{
			$this->note("There's something wrong with the target version, we need to replace it with a new one.");
			$target_version = abs(current($versions)) + 1;
		}
		
		$target_file = $target_version."_".$filename.".php";

		$this->text('Migration path: <comment>'.$basepath.'</comment>');
		$this->text('Filename: <comment>'.$target_file.'</comment>');
	
		// Confirm the action
		if($this->confirm('Do you want to create a '.$filename.' Migration?', TRUE))
		{
			// We could try to create a directory if doesn't exist.
			(! $this->_filesystem->exists($basepath)) && $this->_filesystem->mkdir($basepath);	
			
			$test_file = $basepath.$target_file;
	       	# Set the migration template arguments
	       	list($_type) = explode('_', $this->getArgument('filename'));

	      	$options = array(
	           	'NAME'     => ucfirst($this->getArgument('filename')),
	           	'FILENAME' => $target_file,
	          		'PATH'       => $test_file,
	          		'TABLE_NAME' => str_replace($_type.'_', '', $this->getArgument('filename')),
	          		'FIELDS'     => (array) $this->getArgument('options')
	       	);

	      	switch ($_type) 
	       	{
	           		case 'add':
	           		case 'create':
	           		case 'new':
	               		$template_name = 'Create.php.twig'; 
	               		break;
	          
	           		case 'update':
	           		case 'modify':
	               		$template_name = 'Modify.php.twig';
	               		empty($options['FIELDS']) && $options['FIELDS'] = array('column_name:column_type');
	               		break;
	           		default:
	               		$template_name = 'Default.php.twig';
	               		break;
	       	}

			if ($this->make($test_file, __DIR__.'/../Templates/Migrations/', $options, $template_name))
			{
				$this->success('Migration created successfully!');
			}	        
		}
		else
		{
			$this->warning('Process aborted!');
		}
	}
}