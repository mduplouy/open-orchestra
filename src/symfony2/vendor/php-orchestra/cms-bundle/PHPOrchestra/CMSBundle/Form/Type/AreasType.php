<?php
/**
 * This file is part of the PHPOrchestra\CMSBundle.
 *
 * @author Noël Gilain <noel.gilain@businessdecision.com>
 */

namespace PHPOrchestra\CMSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PHPOrchestra\CMSBundle\Form\DataTransformer\jsonToAreasTransformer;

class AreasType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new jsonToAreasTransformer();
    	$builder->addModelTransformer($transformer);
    }
	
    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'orchestra_areas';
    }
    
}