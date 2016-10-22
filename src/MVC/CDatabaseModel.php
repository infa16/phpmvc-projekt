<?php

namespace Anax\MVC;

class CDatabaseModel implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Get the table name.
     *
     * @return string with the table name.
     */
    public function getSource()
    {
        return implode('', array_slice(explode('\\', get_class($this)), -1));
    }

    /**
     * Get object properties.
     *
     * @return array with object properties.
     */
    public function getProperties()
    {
        $properties = get_object_vars($this);
        unset($properties['di']);
        unset($properties['db']);

        return $properties;
    }

    /**
     * Set object properties.
     *
     * @param array $properties with properties to set.
     *
     * @return voId
     */
    public function setProperties($properties)
    {
        // Update object with incoming values, if any
        if (!empty($properties)) {
            foreach ($properties as $key => $val) {
                $this->$key = $val;
            }
        }
    }

    /**
     * Find and return all.
     *
     * @return array
     */
    public function findAll()
    {
        $this->db->select()
            ->from($this->getSource());

        $this->db->execute();
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }

    /**
     * Find and return specific.
     *
     * @return CDatabaseModel
     */
    public function find($Id)
    {
        $this->db->select()
            ->from($this->getSource())
            ->where("Id = ?");

        $this->db->execute([$Id]);
        return $this->db->fetchInto($this);
    }

    /**
     * Create new row.
     *
     * @param array $values key/values to save.
     *
     * @return boolean true or false if saving was ok.
     */
    public function create($values)
    {
        $keys = array_keys($values);
        $values = array_values($values);

        $this->db->insert(
            $this->getSource(),
            $keys
        );

        $res = $this->db->execute($values);

        $this->Id = $this->db->lastInsertId();

        return $res;
    }

    /**
     * Save current object/row.
     *
     * @param array $values key/values to save, or empty to use object properties.
     *
     * @return boolean true or false if saving was ok.
     */
    public function save($values = [])
    {
        $this->setProperties($values);
        $values = $this->getProperties();

        if (!empty($values['Id'])) {
            return $this->update($values);
        } else {
            return $this->create($values);
        }
    }

    /**
     * Update row.
     *
     * @param array $values key/values to save.
     *
     * @return boolean true or false if saving was ok.
     */
    public function update($values)
    {
        $keys = array_keys($values);
        $values = array_values($values);

        // Its update, remove Id and use as where-clause
        unset($keys['Id']);
        $values[] = $this->Id;

        $this->db->update(
            $this->getSource(),
            $keys,
            "Id = ?"
        );

        return $this->db->execute($values);
    }

    /**
     * Delete row.
     *
     * @param integer $Id to delete.
     *
     * @return boolean true or false if deleting went okey.
     */
    public function delete($Id)
    {
        $this->db->delete(
            $this->getSource(),
            'Id = ?'
        );

        return $this->db->execute([$Id]);
    }

    /**
     * Build a select-query.
     *
     * @param string $columns which columns to select.
     *
     * @return $this
     */
    public function query($columns = '*')
    {
        $this->db->select($columns)
            ->from($this->getSource());

        return $this;
    }

    /**
     * Build the where part.
     *
     * @param string $condition for building the where part of the query.
     *
     * @return $this
     */
    public function where($condition)
    {
        $this->db->where($condition);

        return $this;
    }

    /**
     * Build the where part.
     *
     * @param string $condition for building the where part of the query.
     *
     * @return $this
     */
    public function andWhere($condition)
    {
        $this->db->andWhere($condition);

        return $this;
    }

    /**
     * Execute the query built.
     *
     * @param string $query custom query.
     *
     * @return $this
     */
    public function execute($params = [])
    {
        $this->db->execute($this->db->getSQL(), $params);
        $this->db->setFetchModeClass(__CLASS__);

        return $this->db->fetchAll();
    }
}
