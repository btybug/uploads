<?php


namespace Composer\DependencyResolver;


class RuleSet implements \IteratorAggregate, \Countable
{

    const TYPE_PACKAGE = 0;
    const TYPE_JOB = 1;
    const TYPE_LEARNED = 4;
    protected static $types = array(
        255 => 'UNKNOWN',
        self::TYPE_PACKAGE => 'PACKAGE',
        self::TYPE_JOB => 'JOB',
        self::TYPE_LEARNED => 'LEARNED',
    );
    public $ruleById;
    protected $rules;
    protected $nextRuleId;

    protected $rulesByHash;

    public function __construct()
    {
        $this->nextRuleId = 0;

        foreach ($this->getTypes() as $type) {
            $this->rules[$type] = array();
        }

        $this->rulesByHash = array();
    }

    public function getTypes()
    {
        $types = self::$types;
        unset($types[255]);

        return array_keys($types);
    }

    public function add(Rule $rule, $type)
    {
        if (!isset(self::$types[$type])) {
            throw new \OutOfBoundsException('Unknown rule type: ' . $type);
        }

        $hash = $rule->getHash();


        if (isset($this->rulesByHash[$hash])) {
            $potentialDuplicates = $this->rulesByHash[$hash];
            if (is_array($potentialDuplicates)) {
                foreach ($potentialDuplicates as $potentialDuplicate) {
                    if ($rule->equals($potentialDuplicate)) {
                        return;
                    }
                }
            } else {
                if ($rule->equals($potentialDuplicates)) {
                    return;
                }
            }
        }

        if (!isset($this->rules[$type])) {
            $this->rules[$type] = array();
        }

        $this->rules[$type][] = $rule;
        $this->ruleById[$this->nextRuleId] = $rule;
        $rule->setType($type);

        $this->nextRuleId++;

        if (!isset($this->rulesByHash[$hash])) {
            $this->rulesByHash[$hash] = $rule;
        } elseif (is_array($this->rulesByHash[$hash])) {
            $this->rulesByHash[$hash][] = $rule;
        } else {
            $originalRule = $this->rulesByHash[$hash];
            $this->rulesByHash[$hash] = array($originalRule, $rule);
        }
    }

    public function count()
    {
        return $this->nextRuleId;
    }

    public function ruleById($id)
    {
        return $this->ruleById[$id];
    }

    public function getIterator()
    {
        return new RuleSetIterator($this->getRules());
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getIteratorFor($types)
    {
        if (!is_array($types)) {
            $types = array($types);
        }

        $allRules = $this->getRules();
        $rules = array();

        foreach ($types as $type) {
            $rules[$type] = $allRules[$type];
        }

        return new RuleSetIterator($rules);
    }

    public function getIteratorWithout($types)
    {
        if (!is_array($types)) {
            $types = array($types);
        }

        $rules = $this->getRules();

        foreach ($types as $type) {
            unset($rules[$type]);
        }

        return new RuleSetIterator($rules);
    }

    public function __toString()
    {
        return $this->getPrettyString(null);
    }

    public function getPrettyString(Pool $pool = null)
    {
        $string = "\n";
        foreach ($this->rules as $type => $rules) {
            $string .= str_pad(self::$types[$type], 8, ' ') . ": ";
            foreach ($rules as $rule) {
                $string .= ($pool ? $rule->getPrettyString($pool) : $rule) . "\n";
            }
            $string .= "\n\n";
        }

        return $string;
    }
}
