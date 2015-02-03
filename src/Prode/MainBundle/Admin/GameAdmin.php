<?php

namespace Prode\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GameAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->add('id', 'text', array('label' => 'ID'))
                ->add('home', 'entity', array('label' => 'Local', 'class' => 'Prode\MainBundle\Entity\Team'))
                ->add('away', 'entity', array('label' => 'Visitante', 'class' => 'Prode\MainBundle\Entity\Team'))
                ->add('result', 'entity', array('label' => 'Resultado', 'class' => 'Prode\MainBundle\Entity\Team'));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('id')
                ->add('home')
                ->add('away');
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('id')
                ->add('home', 'entity', array('label' => 'Local', 'class' => 'Prode\MainBundle\Entity\Team'))
                ->add('away', 'entity', array('label' => 'Visitante', 'class' => 'Prode\MainBundle\Entity\Team'));
    }

}