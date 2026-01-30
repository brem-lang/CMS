<?php

namespace App;

enum NavigationGroup: string
{
    case sales = 'Sales & Operations';
    case products = 'Products & Content';
    case user = 'User Management';
}
