<?php
/**
 * @author   Krzysztof Suszyński <k.suszynski@mediovski.pl>
 * @version  0.2
 * @package  php.manager.crontab
 *
 * Copyright (c) 2012 Krzysztof Suszyński <k.suszynski@mediovski.pl>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

namespace Crontab\Service\Vendor;

/**
 *
 */
class CliTool
{
    /**
     * @var bool
     */
    protected $_verbose = false;

    /**
     * @var array
     */
    private $_opts;

    /**
     * @var string|null
     */
    private $_methodName = null;

    /**
     * @var string|null
     */
    protected $_sudo = null;

    /**
     * @var string
     */
    protected $_targetFile;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_progName = $_SERVER['argv'][0];
    }

    /**
     * Dispaches to an CLI action
     *
     * @return \ReflectionMethod
     */
    private function _dispach()
    {
        $cls = new \ReflectionClass($this);
        $method = $cls->getMethod($this->_methodName);
        return $method;
    }

    /**
     * Parse cli opt params
     *
     * @return CliTool
     * @throws \UnexpectedValueException
     */
    private function _parseOpts()
    {
        $longOpts = array(
            'enable:',
            'disable:',
            'user:',
            'help',
            'usage',
            'verbose'
        );
        $opts = getopt('e:d:hvu:', $longOpts);
        $this->_opts = $opts;
        $this->_verbose = $this->_isSet('verbose', 'v');
        if ($this->_isSet('usage') || $this->_isSet('help', 'h')) {
            $this->_methodName = 'usage';
            return $this;
        }
        if ($this->_isSet('user', 'u')) {
            $this->_sudo = $this->_getOpt('user', 'u');
        }
        if (
            ($this->_isSet('enable', 'e') && $this->_isSet('disable', 'd')) ||
            ($this->_isNotSet('enable', 'e') && $this->_isNotSet('disable', 'd'))
            ) {
            throw new \UnexpectedValueException(
                '--enable|-e or --disable|-d opt is required, but only one of them'
            );
        }
        if ($this->_isSet('enable', 'e')) {
            $this->_methodName = 'enable';
            $this->_targetFile = $this->_getOpt('enable', 'e');
            return $this;
        }
        if ($this->_isSet('disable', 'd')) {
            $this->_methodName = 'disable';
            $this->_targetFile = $this->_getOpt('disable', 'd');
            return $this;
        }

        return $this;
    }

    /**
     * Checks if opt is set
     *
     * @param string $name
     * @param string|null $alias
     * @return bool
     */
    protected function _isSet($name, $alias = null)
    {
        return isset($this->_opts[$name]) ||
            ($alias && isset($this->_opts[$alias]));
    }

    /**
     * Checks if opt is not set
     *
     * @param string $name
     * @param string|null $alias
     * @return bool
     */
    protected function _isNotSet($name, $alias = null)
    {
        return !isset($this->_opts[$name]) &&
            !($alias && isset($this->_opts[$alias]));
    }

    /**
     * Gets opt if is set
     *
     * @param string $name
     * @param string|null $alias
     * @param mixed|null $default
     * @return bool
     */
    protected function _getOpt($name, $alias = null, $default = null)
    {
        if ($this->_isSet($name, $alias)) {
            if ($this->_isSet($name)) {
                return $this->_opts[$name];
            } else {
                return $this->_opts[$alias];
            }
        }
        return $default;
    }

    /**
     * Runs tool from CLI
     *
     * @return integer
     * @static
     */
    public static function run()
    {
        $cli = self::_instantinate();
        try {
            $cli->_parseOpts();
            $method = $cli->_dispach();
            $out = $method->invoke($cli);
            if ($out) {
                $cli->_out($out);
            }
        } catch (\UnexpectedValueException $optExc) {
            $cli->_err("Invalid option: " . $optExc->getMessage() . "\n");
            $cli->_err($cli->usage());
            return 1;
        } catch (\Exception $exc) {
            if ($cli->_verbose) {
                $cli->_err($exc . "\n");
            } else {
                $cli->_err("Error: " . $exc->getMessage() . "\n");
            }
            return 2;
        }
        return 0;
    }

    /**
     * Echoes to PHP STDOUT
     *
     * @param string $message
     */
    protected function _out($message)
    {
        fprintf(STDOUT, $message . "\n");
    }

    /**
     * Echoes to PHP STDERR
     *
     * @param string $message
     */
    protected function _err($message)
    {
        fprintf(STDERR, $message . "\n");
    }

    /**
     * CrontabManager factory method
     *
     * @return CrontabManager
     */
    protected function _createManager()
    {
        return new CrontabManager();
    }

    /**
     * CliTool factory method
     *
     * @return CliTool
     */
    protected static function _instantinate()
    {
        return new self();
    }

    /**
     * Enable CRON file action
     */
    public function enable()
    {
        $this->_loadClasses();
        $manager = $this->_createManager();
        if ($this->_sudo) {
            $manager->user = $this->_sudo;
            $this->_out(sprintf('Using "%s" user\'s crontab with `sudo\' command', $this->_sudo));
        }
        $manager->enableOrUpdate($this->_targetFile);
        $manager->save();

        $this->_out(sprintf('File "%s" merged into crontab', $this->_targetFile));
    }

    /**
     * Enable CRON file action
     */
    public function disable()
    {
        $this->_loadClasses();
        $manager = $this->_createManager();
        if ($this->_sudo) {
            $manager->user = $this->_sudo;
            $this->_out(sprintf('Using "%s" crontab with `sudo\' command', $this->_sudo));
        }
        $manager->disable($this->_targetFile);
        $manager->save();

        $this->_out(sprintf('File "%s" removed from crontab', $this->_targetFile));
    }

    /**
     * Usage for cli
     *
     * @return string
     */
    public function usage()
    {
        $usage = "Usage: cronman <--enable|-e FILE,--disable|-d FILE> [--user|-u USER] [--verbose|-v] [--help|-h] [--usage]";
        $usage .= "\n\n";
        $usage .= "Required params:\n";
        $usage .= "   --enable|-e FILE   Enable target FILE to crontab, replace it if already set\n";
        $usage .= "   --disable|-d FILE  Disable target FILE from crontab\n";
        $usage .= "\n";
        $usage .= "Optional params:\n";
        $usage .= "   --user|-u USER     For which user to run this program\n";
        $usage .= "   --verbose|-v       Display more massages\n";
        $usage .= "   --help|-h,--usage  Displays this help\n";

        return $usage;
    }

    /**
     * Loads classes if they are not present
     *
     */
    private function _loadClasses()
    {
        if (!class_exists('php\manager\crontab\CrontabManager')) {
            require_once __DIR__ . '/CrontabManager.php';
        }
        if (!class_exists('php\manager\crontab\CronEntry')) {
            require_once __DIR__ . '/CronEntry.php';
        }
    }

}

if (PHP_SAPI == 'cli' && isset($_SERVER['argv'])
    && realpath($_SERVER['argv'][0]) == __FILE__) {
    exit(CliTool::run());
}
