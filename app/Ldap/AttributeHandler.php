<?php

namespace App\Ldap;

use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use App\Models\User as DatabaseUser;

class AttributeHandler
{
    public function handle(LdapUser $ldap, DatabaseUser $database)
    {
        $database->name          = $ldap->getFirstAttribute('cn');
        $database->username      = $ldap->getFirstAttribute('samaccountname');
        $database->email         = str_replace(".net", ".com", $ldap->getFirstAttribute('userprincipalname'));
        //$ldap->getFirstAttribute('userprincipalname');
        $database->id_estado     = $database->id_estado;
        $database->id_rol        = $database->id_rol;
    }
}
