<?php

namespace Engine\Rx\Entity;

trait PopulateAbility
{
    /**
     * @param array $data
     * @param array $exceptFields
     *
     * @return $this
     */
    public function populate(array $data, array $exceptFields = [])
    {
        foreach ($exceptFields as $field) {
            unset($data[$field]);
        }

        foreach ($data as $field => $value) {
            $methodName = 'set'.ucfirst($field);
            if (method_exists($this, $methodName)) {
                $this->$methodName($data[$field]);
            }
        }

        return $this;
    }
}
