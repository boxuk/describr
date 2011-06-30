<?php
namespace BoxUK\Describr;

/**
 * Although Describr is primarily intended to be used as a PHP library in projects,
 * it can also be run on the CLI. This class wraps that up, providing access to
 * the facade, help text, example usage and graceful error handling so as not
 * to throw exceptions up to the console when users are trying it out.
 * 
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.5
 * @see       Facade If you are working in a PHP project, you should probably
 *                   use the Facade class
 */
class Cli {
    
    /**
     * @var Facade
     */
    protected $facade;
    
    /**
     * Create an instance of the describr facade - this is the class that wraps all
     * the functionality of describr and should be the only class you need most
     * of the time
     */
    public function __construct() {
        $this->facade = new \BoxUK\Describr\Facade();    
    }
    
    /**
     * Run the CLI - check the input and analyse the file that it finds
     *
     * @param array $arguments All the arguments passed in from the command line
     */
    public function run(array $arguments) {
        $argumentCount = count($arguments);
        
        if($argumentCount <= 1) {
            $this->showHelpText();
        }
        
        for ($i = 1; $i < $argumentCount; $i++) {
            $this->analyseArgument($arguments[$i]);
        }
    }
    
    /**
     * Analyse a command line argument
     * @param type $argument Generally a path to a file, absolute or relative
     */
    protected function analyseArgument($argument) {
        $argument = trim($argument);
        
        if(is_file($argument)) {
            echo "Analysing $argument...\n";
            $this->analyseFile($argument);
        } else {
            if(in_array($argument, array('help', '--help', '-h'))) {
                $this->showHelpText();
            } else {
                echo "File '$argument' not found\n";
            }
        }
        
        echo "\n";
    }
    
    /**
     * Run analysis on a file and vardump the output to stdout
     * 
     * Show what describr was able to work out about this file
     * 
     * @param type $file Path to the file that is to be analysed
     */
    protected function analyseFile($file) {
        try {
            $response = $this->facade->describeFileAsArray($file);

            var_dump($response);
        } catch(FileNotFoundException $e) {
            echo "\nUnable to read file $file";
        }
    }
    
    /**
     * Default action that just shows the help text/example usage for Describr
     * being run in CLI mode
     */
    protected function showHelpText() {
        echo "describr - tell me about your file...\n";
        echo "(c) 2011 Box UK\n";
        echo "Usage: describr [path to file]\n";
        echo "       describr [path to file 1] [path to file 2] ... [path to file N]\n";
    }
}