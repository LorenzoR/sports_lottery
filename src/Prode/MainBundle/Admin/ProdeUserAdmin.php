<?php

namespace Prode\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProdeUserAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('username', 'text', array('label' => 'Nombre de Usuario'))
                ->add('firstname', 'text', array('label' => 'Nombre'))
                ->add('lastname', 'text', array('label' => 'Apellido'))
                ->add('forecasts', 'entity', array('class' => 'Prode\MainBundle\Entity\Forecast'))
                ->add('isPlaying', 'text', array('label' => 'Activo'));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('username')
                ->add('firstname')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('username')
                ->add('firstname')
                ->add('lastname')
                ->add('email');
;
    }

}