<?php


namespace Composer\Plugin;

use Composer\EventDispatcher\Event;


class CommandEvent extends Event
{


    private $commandName;


    private $input;


    private $output;


    public function __construct($name, $commandName, $input, $output, array $args = array(), array $flags = array())
    {
        parent::__construct($name, $args, $flags);
        $this->commandName = $commandName;
        $this->input = $input;
        $this->output = $output;
    }


    public function getInput()
    {
        return $this->input;
    }


    public function getOutput()
    {
        return $this->output;
    }


    public function getCommandName()
    {
        return $this->commandName;
    }
}
