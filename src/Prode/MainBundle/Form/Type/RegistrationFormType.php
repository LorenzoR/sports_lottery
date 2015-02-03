<?php

namespace Prode\MainBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('dni', 'number');
        $builder->add('businessUnit', 'choice', array(
            'empty_value' => false,
            'choices' => array('ACA BIO' => 'ACA BIO',
                'Adm. y Finanzas' => 'Adm. y Finanzas',
                'CDC' => 'CDC',
                'Comercio Exterior' => 'Comercio Exterior',
                'Consejo de Adm. - Mesa Directiva' => 'Consejo de Adm. - Mesa Directiva',
                'Direccion y Asesoramiento' => 'Direccion y Asesoramiento',
                'Hacienda' => 'Hacienda',
                'Insumos' => 'Insumos',
                'Lanas' => 'Lanas',
                'Mutual' => 'Mutual',
                'Organos de Ejecución Gerencial' => 'Organos de Ejecución Gerencial',
                'Productos Agricolas' => 'Productos Agricolas',
                'Puertos' => 'Puertos'),
            'required' => false,
        ));

        $builder->add('workplace', 'choice', array(
            'empty_value' => false,
            'choices' => array(
                'Casa Central' => 'Casa Central',
                'Casa Central - Imprenta'=> 'Casa Central - Imprenta',
                'CDC Arroyito' => 'CDC Arroyito',
                'CDC Bragado' => 'CDC Bragado',
                'CDC Cañada de Gomez' => 'CDC Cañada de Gomez',
                'CDC Canals' => 'CDC Canals',
                'CDC Carlos M. Naón' => 'CDC Carlos M. Naón',
                'CDC Colonia Baron' => 'CDC Colonia Baron',
                'CDC Concepcion' => 'CDC Concepcion',
                'CDC Despeñaderos' => 'CDC Despeñaderos',
                'CDC Desvio Fepsa' => 'CDC Desvio Fepsa',
                'CDC Eduardo Castex' => 'CDC Eduardo Castex',
                'CDC Germania' => 'CDC Germania',
                'CDC Gualeguay' => 'CDC Gualeguay',
                'CDC Gualeguaychu' => 'CDC Gualeguaychu',
                'CDC Huanguelen' => 'CDC Huanguelen',
                'CDC Hughes' => 'CDC Hughes',
                'CDC Iriarte' => 'CDC Iriarte',
                'CDC James Craik' => 'CDC James Craik',
                'CDC La Chispa' => 'CDC La Chispa',
                'CDC Laboulaye' => 'CDC Laboulaye',
                'CDC Las Flores' => 'CDC Las Flores',
                'CDC Las Rosas' => 'CDC Las Rosas',
                'CDC MANFREDI' => 'CDC MANFREDI',
                'CDC Obispo Trejo' => 'CDC Obispo Trejo',
                'CDC Olavarria' => 'CDC Olavarria',
                'CDC Oliva' => 'CDC Oliva',
                'CDC Pehuajo' => 'CDC Pehuajo',
                'CDC Pergamino' => 'CDC Pergamino',
                'CDC Pilar' => 'CDC Pilar',
                'CDC Rufino' => 'CDC Rufino',
                'CDC Sacanta' => 'CDC Sacanta',
                'CDC San Francisco' => 'CDC San Francisco',
                'CDC San Genaro' => 'CDC San Genaro',
                'CDC San José de la Esquina' => 'CDC San José de la Esquina',
                'CDC Sargento Cabral' => 'CDC Sargento Cabral',
                'CDC Serrano' => 'CDC Serrano',
                'CDC Tio Pujio' => 'CDC Tio Pujio',
                'CDC Totoras' => 'CDC Totoras',
                'CDC Venado Tuerto' => 'CDC Venado Tuerto',
                'CDC Villa Bordeu' => 'CDC Villa Bordeu',
                'CDC Villa del Rosario' => 'CDC Villa del Rosario',
                'CDC Villegas' => 'CDC Villegas',
                'CDC Wheelwright' => 'CDC Wheelwright',
                'CDC Winifreda' => 'CDC Winifreda',
                'CDC Zavalla' => 'CDC Zavalla',
                'Colonia Barón' => 'Colonia Barón',
                'Criadero Cabildo' => 'Criadero Cabildo',
                'Criadero Pergamino' => 'Criadero Pergamino',
                'Deposito de Miel' => 'Deposito de Miel',
                'Fábrica San Nicolás' => 'Fábrica San Nicolás',
                'Fabrica Silos Bolsa' => 'Fabrica Silos Bolsa',
                'Fca. Rio Tercero' => 'Fca. Rio Tercero',
                'Ferticentro - Bahía Blanca' => 'Ferticentro - Bahía Blanca',
                'Filial Junin' => 'Filial Junin',
                'Filial Parana' => 'Filial Parana',
                'Filial Pergamino' => 'Filial Pergamino',
                'Filial Santa Fe' => 'Filial Santa Fe',
                'Filial Tres Arroyos' => 'Filial Tres Arroyos',
                'Filial Tucuman' => 'Filial Tucuman',
                'Gral. Pico' => 'Gral. Pico',
                'Mercado de Liniers' => 'Mercado de Liniers',
                'Of. Tec. Pergamino' => 'Of. Tec. Pergamino',
                'Oficina Comercial La Laguna' => 'Oficina Comercial La Laguna',
                'Oficina Comercial Oncativo' => 'Oficina Comercial Oncativo',
                'Oficina Cruz Alta' => 'Oficina Cruz Alta',
                'Oficina de Gral. Lavalle' => 'Oficina de Gral. Lavalle',
                'Pergamino' => 'Pergamino',
                'Pergamino - Suelo Fértil' => 'Pergamino - Suelo Fértil',
                'Piedritas' => 'Piedritas',
                'Planta Almacenaje Sa' => 'Planta Almacenaje Sa',
                'Planta Campana' => 'Planta Campana',
                'Planta Los Frentones' => 'Planta Los Frentones',
                'Planta Regional Los Quirquinchos' => 'Planta Regional Los Quirquinchos',
                'Planta Regional Murphy' => 'Planta Regional Murphy',
                'Planta Rio III' => 'Planta Rio III',
                'Planta Selva' => 'Planta Selva',
                'Puerto Quequen' => 'Puerto Quequen',
                'Puerto San Lorenzo' => 'Puerto San Lorenzo',
                'Puertos Vilelas' => 'Puertos Vilelas',
                'Sin Asignación Específica' => 'Sin Asignación Específica',
                'Sucursal Bahía Blanca' => 'Sucursal Bahía Blanca',
                'Sucursal Cordoba' => 'Sucursal Cordoba',
                'Sucursal Rosario' => 'Sucursal Rosario',
                'Tres Arroyos' => 'Tres Arroyos',
                'Tres Arroyos - Suelo Fértil' => 'Tres Arroyos - Suelo Fértil',
                'Villa María' => 'Villa María'
            ),
            'required' => false,
        ));
    }

    public function getName() {
        return 'prode_user_registration';
    }

}

?>