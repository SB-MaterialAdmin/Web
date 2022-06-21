<?php

class AppOptions extends ExtendedObject {
    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $changed;

    public function __construct($options)
    {
        $this->options = $options;
        $this->changed = [];
    }
    /**
     * {@inheritdoc}
     */
    public function get($option)
    {
        if (!array_key_exists($option, $this->options))
        {
            return null;
        }

        return $this->options[$option];
    }

    /**
     * {@inheritdoc}
     */
    public function set($option, $value)
    {
        if (!$this->exists($option))
        {
            throw new \LogicException("You need initialize this option manually.");
        }

        if (!in_array($option, $this->changed))
        {
            $this->changed[] = $option;
        }

        $this->options[$option] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        throw new \LogicException("You can't delete options.");
    }

    /**
     * Commits all changes in DB.
     */
    public function commit()
    {
        $query = "
            REPLACE INTO
                `{{prefix}}settings`
                (`setting`, `value`)
            VALUES
        ";
        $subquery = [];
        $data = [];

        foreach ($this->changed as $option)
        {
            $subquery[] = "(?, ?)";

            $data[] = $option;
            $data[] = $this->options[$option];
        }

        $query .= implode(', ', $subquery);

        $db = \App::db();
        $db->Prepare($query);
        $db->Finish(true, $data);
    }
}