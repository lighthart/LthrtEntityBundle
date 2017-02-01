# EntityBundle
Generic Crud and JSON representations for entities for Symfony Bundles with Backbone support

This bundle uses php traits and annotations to build as much as possible automatically
# LthrtEntityBundle

Entities that can be routed to the generic controllers are registered in an alias.yml file.
These are to be placed in Resorces/config and have the format:

entity: Namespace\entity.php

To turn on full logging, each entity should 
implement \Lthrt\EntityBundle\Entity\EntityLog

For just updated time stamps/created
implement \Lthrt\EntityBundle\Entity\EntityLedger

To log partial records (in a controller):
        $this->get('lthrt.entity.partial.logger')->partial($entity);
# LthrtEntityBundle
