<?php

namespace Lthrt\EntityBundle\Entity;

/**
 * GetSet Trait.
 *
 * For discussion see: http://www.epixa.com/2010/05/the-best-models-are-easy-models.html
 */
trait EntityGetSetTrait
{
    use GetTrait;
    use SetTrait;

    /**
     * Map a call to a non-existent mutator or accessor directly to its
     * corresponding property.
     *
     * this method is the overwrites basic _call to support doctrine
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     *
     * @throws \BadMethodCallException If no mutator/accessor can be found
     */
    public function __call(
        $name,
        $arguments
    ) {
        if (0 === strpos($name, 'get')) {
            $property = lcfirst(substr($name, 3));

            return $this->$property;
        }

        if (0 === strpos($name, 'set')) {
            $property = lcfirst(substr($name, 3));

            $this->$property = array_shift($arguments);

            return $this;
        }

        // doctrine specific
        if (0 === strpos($name, 'add')) {
            $property  = lcfirst(substr($name, 3));
            $arguments = array_shift($arguments);
            if ($this->$property->contains($arguments)) {
            } else {
                $this->$property->add($arguments);
                // doctrine only persists from the owning side
                if (method_exists($this, 'inverse' . ucfirst($property))) {
                    // accomodates associations without same name as entity
                    // example:
                    // public function inverseProperty()
                    // {
                    //     return "UniqueAssociationName";
                    // }
                    $mapped = 'inverse' . ucfirst($property);
                    $method = $this->$mapped();
                } else {
                    $method = lcfirst(strrev(strstr(strrev(get_class($this)), '\\', true)));
                }
                if (method_exists($arguments, $method)) {
                    $arguments->$method->add($this);
                }
            }

            return $this;
        }

        // doctrine specific
        if (0 === strpos($name, 'remove')) {
            $property  = lcfirst(substr($name, 3));
            $arguments = array_shift($arguments);
            if ($this->$property > contains($arguments)) {
                $this->$property->removeElement($arguments);
                // doctrine only persists from the owning side
                if (method_exists($this, 'inverse' . ucfirst($property))) {
                    // accomodates associations without same name as entity
                    // example:
                    // public function inverseProperty()
                    // {
                    //     return "UniqueAssociationName";
                    // }
                    $mapped = 'inverse' . ucfirst($property);
                    $method = $this->$mapped();
                } else {
                    $method = lcfirst(strrev(strstr(strrev(get_class($this)), '\\', true)));
                }
                if (method_exists($arguments, $method)) {
                    $arguments->$method()->removeElement($this);
                }
            }

            return $this;
        }

        if (0 === strpos($name, 'clear')) {
            $property = lcfirst(substr($name, 3));
            if ('object' == gettype($this->name)) {
                $this->$property->clear();
            } else {
                $this->$property = null;
            }

            return $this;
        }

        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new \BadMethodCallException(sprintf(
            'No method or property named `%s` exists',
            $name
        ));
    }
}
