<?php

if ( $options['provider'] == "" ) $options['provider'] = 'custom';

echo '<b>Provider</b> : ' . $options['provider'] . ' <b>Action</b> : ' . $options['action'];
